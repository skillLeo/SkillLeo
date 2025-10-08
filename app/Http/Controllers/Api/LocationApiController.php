<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;

class LocationApiController extends Controller
{
    private const CN_BASE = 'https://countriesnow.space/api/v0.1/countries';

    /**
     * Unified search endpoint - searches across countries, states, and cities
     * GET /api/location/search?q=karachi
     */
    public function search(Request $req)
    {
        $q = Str::lower(trim($req->query('q', '')));
        
        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $results = [];

        // Search countries
        $countries = $this->searchCountries($q);
        foreach ($countries as $country) {
            $results[] = [
                'type' => 'country',
                'name' => $country['name'],
                'display' => $country['name'],
                'country' => $country['name'],
                'state' => null,
                'city' => null,
                'iso2' => $country['iso2'] ?? null,
            ];
        }

        // Search states (from popular countries)
        $states = $this->searchStates($q);
        foreach ($states as $state) {
            $results[] = [
                'type' => 'state',
                'name' => $state['name'],
                'display' => "{$state['name']}, {$state['country']}",
                'country' => $state['country'],
                'state' => $state['name'],
                'city' => null,
            ];
        }

        // Search cities (from popular countries/states)
        $cities = $this->searchCities($q);
        foreach ($cities as $city) {
            $results[] = [
                'type' => 'city',
                'name' => $city['name'],
                'display' => "{$city['name']}, {$city['state']}, {$city['country']}",
                'country' => $city['country'],
                'state' => $city['state'],
                'city' => $city['name'],
            ];
        }

        // Limit results and sort by relevance
        $results = collect($results)
            ->sortBy(function($item) use ($q) {
                // Prioritize exact matches and cities
                $name = Str::lower($item['name']);
                if ($name === $q) return 0;
                if (Str::startsWith($name, $q)) return 1;
                return 2;
            })
            ->take(15)
            ->values()
            ->all();

        return response()->json($results);
    }

    /** Get countries (ISO2 + name). */
    public function countries(Request $req)
    {
        $q = Str::lower(trim($req->query('q', '')));

        $data = Cache::remember('loc:countries', now()->addHours(12), function () {
            $resp = Http::timeout(15)->get(self::CN_BASE.'/iso');
            if ($resp->ok() && isset($resp['data'])) {
                return collect($resp['data'])->map(fn ($c) => [
                    'name' => $c['name'] ?? $c['country'] ?? null,
                    'iso2' => strtoupper($c['Iso2'] ?? $c['iso2'] ?? $c['iso'] ?? ''),
                    'iso3' => strtoupper($c['Iso3'] ?? $c['iso3'] ?? ''),
                ])->filter(fn($c) => $c['name'] && $c['iso2'])->values()->all();
            }

            $fallback = Http::timeout(15)->get(self::CN_BASE);
            $list = ($fallback->ok() && isset($fallback['data'])) ? $fallback['data'] : [];
            return collect($list)->map(fn($c) => [
                'name' => $c['country'] ?? $c['name'] ?? null,
                'iso2' => null,
                'iso3' => null,
            ])->filter(fn($c) => $c['name'])->values()->all();
        });

        if ($q !== '') {
            $data = collect($data)->filter(fn($c) =>
                Str::contains(Str::lower($c['name']), $q) ||
                ($c['iso2'] && Str::contains(Str::lower($c['iso2']), $q))
            )->values()->all();
        }

        return response()->json($data);
    }

    /** Get states of a country. */
    public function states(Request $req)
    {
        $country = $this->resolveCountryName($req);
        abort_if(!$country, 422, 'country or iso2 required');

        $cacheKey = 'loc:states:'.Str::slug($country);
        $states = Cache::remember($cacheKey, now()->addHours(12), function () use ($country) {
            $resp = Http::timeout(20)->post(self::CN_BASE.'/states', ['country' => $country]);
            if ($resp->ok()) {
                $payload = $resp->json();
                $list = data_get($payload, 'data.states', []);
                return collect($list)->map(fn($s) => [
                    'name' => $s['name'],
                    'state_code' => strtoupper($s['state_code'] ?? Str::slug($s['name'], '_')),
                ])->values()->all();
            }
            return [];
        });

        if (empty($states)) $states = $this->fallbackStates($country);

        return response()->json($states);
    }

    /** Get cities inside a state of a country. */
    public function cities(Request $req)
    {
        $country = $this->resolveCountryName($req);
        $state   = trim((string) $req->query('state'));
        abort_if(!$country || !$state, 422, 'country and state required');

        $cacheKey = 'loc:cities:'.md5($country.'|'.$state);
        $cities = Cache::remember($cacheKey, now()->addHours(12), function () use ($country, $state) {
            $resp = Http::timeout(25)->post(self::CN_BASE.'/state/cities', [
                'country' => $country, 'state' => $state
            ]);
            if ($resp->ok()) {
                $payload = $resp->json();
                $list = data_get($payload, 'data', []);
                return collect($list)->filter()->map(fn($name) => ['name' => $name])->values()->all();
            }
            return [];
        });

        if (empty($cities)) $cities = $this->fallbackCities($country, $state);

        return response()->json($cities);
    }

    /** Reverse geocode */
    public function reverse(Request $req)
    {
        $lat = (float) $req->query('lat');
        $lng = (float) $req->query('lng');
        abort_if(!$lat || !$lng, 422, 'lat & lng required');

        $headers = [
            'User-Agent' => 'ProMatch/1.0 ('.config('services.nominatim.email','your@email').')',
            'Accept'     => 'application/json',
        ];

        $geo = Http::withHeaders($headers)
            ->timeout(15)
            ->get('https://nominatim.openstreetmap.org/reverse', [
                'lat' => $lat,
                'lon' => $lng,
                'format' => 'jsonv2',
                'zoom' => 10,
                'addressdetails' => 1,
                'extratags' => 0,
            ])->json();

        $addr = data_get($geo, 'address', []);
        $countryName = data_get($addr, 'country');
        $stateName   = data_get($addr, 'state') ?? data_get($addr, 'region') ?? data_get($addr, 'province');
        $cityName    = data_get($addr, 'city') ?? data_get($addr, 'town') ?? data_get($addr, 'village') ?? data_get($addr, 'county');

        $country = $this->closestCountry($countryName);
        $state   = $country ? $this->closestState($country, $stateName) : null;
        $city    = ($country && $state) ? $this->closestCity($country, $state, $cityName) : null;

        return response()->json([
            'raw'     => ['country'=>$countryName, 'state'=>$stateName, 'city'=>$cityName],
            'matched' => ['country'=>$country, 'state'=>$state, 'city'=>$city],
        ]);
    }

    // ---------- Private Helper Methods ----------

    private function searchCountries(string $query): array
    {
        $countries = Cache::get('loc:countries', []);
        return collect($countries)
            ->filter(fn($c) => Str::contains(Str::lower($c['name']), $query))
            ->take(5)
            ->values()
            ->all();
    }

    private function searchStates(string $query): array
    {
        $results = [];
        $popularCountries = ['Pakistan', 'United States', 'United Kingdom', 'Canada', 'India', 'Australia'];
        
        foreach ($popularCountries as $country) {
            $cacheKey = 'loc:states:'.Str::slug($country);
            $states = Cache::get($cacheKey);
            
            if (!$states) {
                try {
                    $resp = Http::timeout(10)->post(self::CN_BASE.'/states', ['country' => $country]);
                    if ($resp->ok()) {
                        $payload = $resp->json();
                        $states = data_get($payload, 'data.states', []);
                        Cache::put($cacheKey, $states, now()->addHours(12));
                    }
                } catch (\Exception $e) {
                    continue;
                }
            }

            if ($states) {
                foreach ($states as $state) {
                    if (Str::contains(Str::lower($state['name']), $query)) {
                        $results[] = [
                            'name' => $state['name'],
                            'country' => $country,
                        ];
                        if (count($results) >= 10) break 2;
                    }
                }
            }
        }

        return $results;
    }

    private function searchCities(string $query): array
    {
        $results = [];
        $popularLocations = [
            'Pakistan' => ['Punjab', 'Sindh', 'Khyber Pakhtunkhwa', 'Balochistan'],
            'United States' => ['California', 'Texas', 'New York', 'Florida'],
            'United Kingdom' => ['England', 'Scotland', 'Wales'],
            'India' => ['Maharashtra', 'Delhi', 'Karnataka', 'Tamil Nadu'],
        ];

        foreach ($popularLocations as $country => $states) {
            foreach ($states as $state) {
                $cacheKey = 'loc:cities:'.md5($country.'|'.$state);
                $cities = Cache::get($cacheKey);

                if (!$cities) {
                    try {
                        $resp = Http::timeout(10)->post(self::CN_BASE.'/state/cities', [
                            'country' => $country,
                            'state' => $state
                        ]);
                        if ($resp->ok()) {
                            $payload = $resp->json();
                            $cities = data_get($payload, 'data', []);
                            Cache::put($cacheKey, $cities, now()->addHours(12));
                        }
                    } catch (\Exception $e) {
                        continue;
                    }
                }

                if ($cities) {
                    foreach ($cities as $city) {
                        if (Str::contains(Str::lower($city), $query)) {
                            $results[] = [
                                'name' => $city,
                                'state' => $state,
                                'country' => $country,
                            ];
                            if (count($results) >= 15) break 3;
                        }
                    }
                }
            }
        }

        return $results;
    }

    private function resolveCountryName(Request $req): ?string
    {
        $iso2 = strtoupper((string)$req->query('iso2'));
        $name = trim((string)$req->query('country'));
        if ($name) return $name;

        if ($iso2) {
            $country = collect($this->countries($req)->getData(true))
                ->firstWhere('iso2', $iso2);
            return $country['name'] ?? null;
        }
        return null;
    }

    private function fallbackStates(string $country): array
    {
        $path = storage_path('app/location/states.json');
        if (!is_file($path)) return [];
        $json = json_decode(file_get_contents($path), true);
        $id = collect($json['countries'] ?? [])->firstWhere('name', $country)['id'] ?? null;
        if (!$id) return [];
        return collect($json['states'] ?? [])->where('country_id', $id)
            ->map(fn($s) => ['name'=>$s['name'], 'state_code'=>strtoupper($s['state_code'] ?? Str::slug($s['name'],'_'))])
            ->values()->all();
    }

    private function fallbackCities(string $country, string $state): array
    {
        $path = storage_path('app/location/cities.json');
        if (!is_file($path)) return [];
        $json = json_decode(file_get_contents($path), true);
        $stateId = collect($json['states'] ?? [])
            ->first(fn($s) => $s['country_name']===$country && $s['name']===$state)['id'] ?? null;
        if (!$stateId) return [];
        return collect($json['cities'] ?? [])->where('state_id', $stateId)
            ->pluck('name')->map(fn($n)=>['name'=>$n])->values()->all();
    }

    private function closestCountry(?string $name): ?string
    {
        if (!$name) return null;
        $needle = Str::lower($name);
        $list = Cache::get('loc:countries', []);
        $hit = collect($list)->first(fn($c) => Str::lower($c['name']) === $needle);
        if ($hit) return $hit['name'];
        $hit = collect($list)->first(fn($c) => Str::contains(Str::lower($c['name']), $needle));
        return $hit['name'] ?? $name;
    }

    private function closestState(string $country, ?string $name): ?string
    {
        if (!$name) return null;
        $needle = Str::lower($name);
        $states = $this->states(new Request(['country'=>$country]))->getData(true);
        $hit = collect($states)->first(fn($s) => Str::lower($s['name']) === $needle)
            ?? collect($states)->first(fn($s) => Str::contains(Str::lower($s['name']), $needle));
        return $hit['name'] ?? $name;
    }

    private function closestCity(string $country, string $state, ?string $name): ?string
    {
        if (!$name) return null;
        $needle = Str::lower($name);
        $cities = $this->cities(new Request(['country'=>$country, 'state'=>$state]))->getData(true);
        $hit = collect($cities)->first(fn($c) => Str::lower($c['name']) === $needle)
            ?? collect($cities)->first(fn($c) => Str::contains(Str::lower($c['name']), $needle));
        return $hit['name'] ?? $name;
    }
}