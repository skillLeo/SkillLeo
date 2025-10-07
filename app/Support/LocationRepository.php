<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LocationRepository
{
    private const CACHE_TTL = 604800; // 7 days
    private const BASE_DIR  = 'location'; // storage/app/location

    /** Countries array: [{name, iso2, iso3}] */
    public function countries(): array
    {
        return Cache::remember('loc:countries.v1', self::CACHE_TTL, function () {
            $path = storage_path('app/'.self::BASE_DIR.'/countries.json');
            $raw = is_file($path) ? json_decode(file_get_contents($path), true) : [];
            // dr5hn format: [{id,name,iso3,iso2,...}]
            return collect($raw)->map(fn($c)=>[
                'name' => $c['name'] ?? $c['country'] ?? null,
                'iso2' => strtoupper($c['iso2'] ?? ''),
                'iso3' => strtoupper($c['iso3'] ?? ''),
            ])->filter(fn($c)=>$c['name'])->values()->all();
        });
    }

    /** States map: countryName => [stateName, ...] */
    public function statesMap(): array
    {
        return Cache::remember('loc:states.map.v1', self::CACHE_TTL, function () {
            $countries = $this->countries();
            $statesPath = storage_path('app/'.self::BASE_DIR.'/states.json');
            $statesRaw = is_file($statesPath) ? json_decode(file_get_contents($statesPath), true) : [];

            $byCountryId = collect($statesRaw)->groupBy('country_id');
            $countryById = collect($countries)->keyBy(function($c) use ($statesRaw) {
                // map by name -> id via states.json (dr5hn uses ids consistently)
                // Build a helper: name -> any state.country_name -> id
                static $nameToId = null;
                if ($nameToId === null) {
                    $nameToId = collect($statesRaw)->groupBy('country_name')->map(fn($g)=>$g->first()['country_id'])->all();
                }
                return $nameToId[$c['name']] ?? $c['name']; // fallback
            });

            // If mapping by id failed, use country_name field directly
            $fallbackByName = collect($statesRaw)->groupBy('country_name');

            $result = [];
            // Prefer using country list as canonical keys (sorted)
            foreach ($countries as $c) {
                $name = $c['name'];
                $bucket = $fallbackByName[$name] ?? [];
                $result[$name] = collect($bucket)->pluck('name')->unique()->sort()->values()->all();
            }
            return $result;
        });
    }

    /** Cities bundle for a given country: stateName => [cityName,...] */
    public function citiesBundle(string $country): array
    {
        $country = trim($country);
        return Cache::remember('loc:cities.bundle.v1:'.Str::slug($country), self::CACHE_TTL, function () use ($country) {
            $statesPath = storage_path('app/'.self::BASE_DIR.'/states.json');
            $citiesPath = storage_path('app/'.self::BASE_DIR.'/cities.json');
            $statesRaw  = is_file($statesPath) ? json_decode(file_get_contents($statesPath), true) : [];
            $citiesRaw  = is_file($citiesPath) ? json_decode(file_get_contents($citiesPath), true) : [];

            // Filter states for this country
            $statesForCountry = collect($statesRaw)->where('country_name', $country)->values();
            $stateIdsByName   = $statesForCountry->keyBy('name')->map(fn($s)=>$s['id']);

            // Group cities by state_id then convert keys to state names
            $citiesByStateId = collect($citiesRaw)->groupBy('state_id');
            $bundle = [];
            foreach ($stateIdsByName as $stateName => $stateId) {
                $bundle[$stateName] = isset($citiesByStateId[$stateId])
                    ? collect($citiesByStateId[$stateId])->pluck('name')->unique()->sort()->values()->all()
                    : [];
            }
            return $bundle;
        });
    }
}
