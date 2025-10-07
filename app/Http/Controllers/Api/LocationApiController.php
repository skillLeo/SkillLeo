<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;

class LocationApiController extends Controller
{


    public function countryPack(Request $req)
    {
        $country = trim((string)$req->query('country'));
        abort_if($country === '', 422, 'country required');
    
        $cacheKey = 'loc:pack:' . \Illuminate\Support\Str::slug($country);
    
        $pack = \Illuminate\Support\Facades\Cache::remember($cacheKey, now()->addDays(3), function () use ($country) {
            $local = $this->buildPackFromLocal($country);
            if (!empty($local)) return $local;
            return $this->buildPackFromCountriesNow($country);
        });
    
        return response()->json($pack);
    }
    
    private function buildPackFromLocal(string $country): array
    {
        $base = storage_path('app/location');
        $fStates = $base . '/states.json';
        $fCities = $base . '/cities.json';
        if (!is_file($fStates) || !is_file($fCities)) return [];
    
        $statesJson = json_decode(file_get_contents($fStates), true);
        $citiesJson = json_decode(file_get_contents($fCities), true);
        if (!$statesJson || !$citiesJson) return [];
    
        $states = collect($statesJson['states'] ?? [])
            ->whereStrict('country_name', $country)
            ->values(['id','name'])
            ->all();
    
        $cityByState = collect($citiesJson['cities'] ?? [])->groupBy('state_id');
    
        $resultStates = [];
        foreach ($states as $s) {
            $cities = ($cityByState->get($s['id']) ?? collect())
                ->pluck('name')->filter()->unique()->sort()->values()->all();
            $resultStates[] = ['name' => $s['name'], 'cities' => $cities];
        }
    
        // flat cities across the country (for no-state countries or quick city list)
        $flatCities = collect($resultStates)->flatMap(fn($s) => $s['cities'])
            ->filter()->unique()->sort()->values()->all();
    
        // If no states exist but we have cities linked indirectly (rare), expose them
        if (empty($resultStates) && !empty($flatCities)) {
            // Keep states empty; frontend will show N/A and use flat_cities
        }
    
        return [
            'country'     => $country,
            'states'      => collect($resultStates)->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE)->values()->all(),
            'flat_cities' => $flatCities,
        ];
    }
    
    private function buildPackFromCountriesNow(string $country): array
    {
        $statesResp = \Illuminate\Support\Facades\Http::timeout(25)
            ->post(self::CN_BASE . '/states', ['country' => $country]);
    
        $statesArr = $statesResp->ok() ? (data_get($statesResp->json(), 'data.states', []) ?? []) : [];
        $list = [];
        $flat = [];
    
        foreach ($statesArr as $s) {
            $sname = $s['name'] ?? null;
            if (!$sname) continue;
    
            $citiesResp = \Illuminate\Support\Facades\Http::timeout(25)
                ->post(self::CN_BASE . '/state/cities', ['country' => $country, 'state' => $sname]);
            $cities = $citiesResp->ok() ? (data_get($citiesResp->json(), 'data', []) ?? []) : [];
            sort($cities, SORT_NATURAL | SORT_FLAG_CASE);
    
            $list[] = ['name' => $sname, 'cities' => array_values($cities)];
            $flat = array_values(array_unique(array_merge($flat, $cities)));
        }
    
        usort($list, fn($a,$b) => strnatcasecmp($a['name'], $b['name']));
        sort($flat, SORT_NATURAL | SORT_FLAG_CASE);
    
        return ['country' => $country, 'states' => $list, 'flat_cities' => $flat];
    }
    













    
    private const CN_BASE = 'https://countriesnow.space/api/v0.1/countries'; // CountriesNow

    /** Get countries (ISO2 + name). */
    public function countries(Request $req)
    {
        $q = Str::lower(trim($req->query('q', '')));

        $data = Cache::remember('loc:countries', now()->addHours(12), function () {
            // Prefer ISO codes endpoint; fallback to base list.
            $resp = Http::timeout(15)->get(self::CN_BASE.'/iso');
            if ($resp->ok() && isset($resp['data'])) {
                return collect($resp['data'])->map(fn ($c) => [
                    'name' => $c['name']        ?? $c['country'] ?? null,
                    'iso2' => strtoupper($c['Iso2'] ?? $c['iso2'] ?? $c['iso'] ?? ''),
                    'iso3' => strtoupper($c['Iso3'] ?? $c['iso3'] ?? ''),
                ])->filter(fn($c) => $c['name'] && $c['iso2'])->values()->all();
            }

            // Fallback: countries with cities (no codes)
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

    /** Get states of a country. Accepts ?country=Pakistan or ?iso2=PK */
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

        // Fallback from cache file (if you choose to warm it; see command below)
        if (empty($states)) $states = $this->fallbackStates($country);

        return response()->json($states);
    }

    /** Get cities inside a state of a country. Requires ?country=Pakistan&state=Sindh */
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

    /** Reverse geocode lat/lng to {country,state,city} using Nominatim, then normalize to our lists. */
    public function reverse(Request $req)
    {
        $lat = (float) $req->query('lat');
        $lng = (float) $req->query('lng');
        abort_if(!$lat || !$lng, 422, 'lat & lng required');

        $headers = [
            // Identify your app + contact per Nominatim policy
            'User-Agent' => 'ProMatch/1.0 ('.config('services.nominatim.email','your@email').')',
            'Accept'     => 'application/json',
        ];

        $geo = Http::withHeaders($headers)
            ->timeout(15)
            ->get('https://nominatim.openstreetmap.org/reverse', [
                'lat' => $lat,
                'lon' => $lng,
                'format' => 'jsonv2',
                'zoom' => 10,            // favor city-level
                'addressdetails' => 1,
                'extratags' => 0,
            ])->json();

        $addr = data_get($geo, 'address', []);
        // Candidate strings from OSM
        $countryName = data_get($addr, 'country');
        $stateName   = data_get($addr, 'state') ?? data_get($addr, 'region') ?? data_get($addr, 'province');
        $cityName    = data_get($addr, 'city') ?? data_get($addr, 'town') ?? data_get($addr, 'village') ?? data_get($addr, 'county');

        // Normalize (case/diacritics) & snap to CountriesNow lists
        $country = $this->closestCountry($countryName);
        $state   = $country ? $this->closestState($country, $stateName) : null;
        $city    = ($country && $state) ? $this->closestCity($country, $state, $cityName) : null;

        return response()->json([
            'raw'     => ['country'=>$countryName, 'state'=>$stateName, 'city'=>$cityName],
            'matched' => ['country'=>$country, 'state'=>$state, 'city'=>$city],
        ]);
    }

    // ---------- Helpers ----------

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
        $path = storage_path('app/location/states.json'); // optional warmed cache
        if (!is_file($path)) return [];
        $json = json_decode(file_get_contents($path), true);
        $id = collect($json['countries'] ?? [])->firstWhere('name', $country)['id'] ?? null;
        if (!$id) return [];
        $states = collect($json['states'] ?? [])->where('country_id', $id)
            ->map(fn($s) => ['name'=>$s['name'], 'state_code'=>strtoupper($s['state_code'] ?? Str::slug($s['name'],'_'))])
            ->values()->all();
        return $states;
    }

    private function fallbackCities(string $country, string $state): array
    {
        $path = storage_path('app/location/cities.json'); // optional warmed cache
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
        // exact
        $hit = collect($list)->first(fn($c) => Str::lower($c['name']) === $needle);
        if ($hit) return $hit['name'];
        // loose contains
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
