<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class LocationApiController extends Controller
{
    /**
     * Search all location types (no filters)
     */
    private function searchAll(string $query): array
    {
        $results = [];

        // Search Cities (Priority)
        $cities = City::with(['state.country'])
            ->where('name', 'LIKE', "{$query}%")
            ->orWhere('name', 'LIKE', "%{$query}%")
            ->limit(10)
            ->get();

        foreach ($cities as $city) {
            $results[] = [
                'type' => 'city',
                'id' => $city->id,
                'city' => $city->name,
                'state' => $city->state->name,
                'country' => $city->state->country->name,
                'display' => "{$city->name}, {$city->state->name}",
                'full_display' => "{$city->name}, {$city->state->name}, {$city->state->country->name}",
                'match_score' => $this->calculateScore($query, $city->name)
            ];
        }

        // Search States
        $states = State::with('country')
            ->where('name', 'LIKE', "{$query}%")
            ->orWhere('name', 'LIKE', "%{$query}%")
            ->limit(8)
            ->get();

        foreach ($states as $state) {
            $results[] = [
                'type' => 'state',
                'id' => $state->id,
                'city' => null,
                'state' => $state->name,
                'country' => $state->country->name,
                'display' => "{$state->name}, {$state->country->name}",
                'full_display' => "{$state->name}, {$state->country->name}",
                'match_score' => $this->calculateScore($query, $state->name)
            ];
        }

        // Search Countries
        $countries = Country::where('name', 'LIKE', "{$query}%")
            ->orWhere('name', 'LIKE', "%{$query}%")
            ->limit(5)
            ->get();

        foreach ($countries as $country) {
            $results[] = [
                'type' => 'country',
                'id' => $country->id,
                'city' => null,
                'state' => null,
                'country' => $country->name,
                'display' => $country->name,
                'full_display' => $country->name,
                'match_score' => $this->calculateScore($query, $country->name)
            ];
        }

        return $results;
    }

    /**
     * Calculate match score for sorting
     */
    private function calculateScore(string $query, string $name): int
    {
        $query = Str::lower($query);
        $name = Str::lower($name);
        
        // Exact match
        if ($query === $name) return 100;
        
        // Starts with
        if (Str::startsWith($name, $query)) return 80;
        
        // Contains word boundary
        if (preg_match('/\b' . preg_quote($query, '/') . '/i', $name)) return 60;
        
        // Contains anywhere
        if (Str::contains($name, $query)) return 40;
        
        return 0;
    }

    /**
     * Get specific location details
     */
    public function getLocation(Request $request)
    {
        $type = $request->query('type');
        $id = $request->query('id');

        $location = match($type) {
            'city' => City::with(['state.country'])->find($id),
            'state' => State::with('country')->find($id),
            'country' => Country::find($id),
            default => null
        };

        if (!$location) {
            return response()->json(['error' => 'Location not found'], 404);
        }

        return response()->json($this->formatLocation($location, $type));
    }

    private function formatLocation($location, $type)
    {
        return match($type) {
            'city' => [
                'city' => $location->name,
                'state' => $location->state->name,
                'country' => $location->state->country->name,
            ],
            'state' => [
                'city' => null,
                'state' => $location->name,
                'country' => $location->country->name,
            ],
            'country' => [
                'city' => null,
                'state' => null,
                'country' => $location->name,
            ],
        };
    }


















        public function search(Request $request)
    {
        $query = Str::lower(trim($request->query('q', '')));
        $filterCountry = $request->query('country');
        $filterState = $request->query('state');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        // Build cache key including filters
        $cacheKey = 'location_search:' . md5($query . '|' . $filterCountry . '|' . $filterState);
        
        $results = Cache::remember($cacheKey, now()->addMinutes(30), function () use ($query, $filterCountry, $filterState) {
            $results = [];

            // If filtering by country and state, only search cities
            if ($filterCountry && $filterState) {
                $results = $this->searchCitiesOnly($query, $filterCountry, $filterState);
            }
            // If filtering by country only, search states and cities
            elseif ($filterCountry) {
                $results = $this->searchStatesAndCities($query, $filterCountry);
            }
            // No filters, search everything
            else {
                $results = $this->searchAll($query);
            }

            // Sort by relevance
            usort($results, function ($a, $b) {
                if ($a['match_score'] !== $b['match_score']) {
                    return $b['match_score'] <=> $a['match_score'];
                }
                
                // Prioritize: cities > states > countries
                $priority = ['city' => 3, 'state' => 2, 'country' => 1];
                return ($priority[$b['type']] ?? 0) <=> ($priority[$a['type']] ?? 0);
            });

            return array_slice($results, 0, 15);
        });

        return response()->json($results);
    }

  
    private function searchCitiesOnly(string $query, string $country, string $state): array
    {
        $results = [];

        $cities = City::whereHas('state', function ($q) use ($country, $state) {
                $q->where('name', $state)
                  ->whereHas('country', function ($q2) use ($country) {
                      $q2->where('name', $country);
                  });
            })
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "{$query}%")
                  ->orWhere('name', 'LIKE', "%{$query}%");
            })
            ->with(['state.country'])
            ->limit(15)
            ->get();

        foreach ($cities as $city) {
            $results[] = [
                'type' => 'city',
                'id' => $city->id,
                'city' => $city->name,
                'state' => $city->state->name,
                'country' => $city->state->country->name,
                'display' => $city->name,
                'full_display' => "{$city->name}, {$city->state->name}, {$city->state->country->name}",
                'match_score' => $this->calculateScore($query, $city->name)
            ];
        }

        return $results;
    }
 
    private function searchStatesAndCities(string $query, string $country): array
    {
        $results = [];

        // Search states
        $states = State::whereHas('country', function ($q) use ($country) {
                $q->where('name', $country);
            })
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "{$query}%")
                  ->orWhere('name', 'LIKE', "%{$query}%");
            })
            ->with('country')
            ->limit(10)
            ->get();

        foreach ($states as $state) {
            $results[] = [
                'type' => 'state',
                'id' => $state->id,
                'city' => null,
                'state' => $state->name,
                'country' => $state->country->name,
                'display' => $state->name,
                'full_display' => "{$state->name}, {$state->country->name}",
                'match_score' => $this->calculateScore($query, $state->name)
            ];
        }

        // Search cities
        $cities = City::whereHas('state.country', function ($q) use ($country) {
                $q->where('name', $country);
            })
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "{$query}%")
                  ->orWhere('name', 'LIKE', "%{$query}%");
            })
            ->with(['state.country'])
            ->limit(10)
            ->get();

        foreach ($cities as $city) {
            $results[] = [
                'type' => 'city',
                'id' => $city->id,
                'city' => $city->name,
                'state' => $city->state->name,
                'country' => $city->state->country->name,
                'display' => $city->name,
                'full_display' => "{$city->name}, {$city->state->name}, {$city->state->country->name}",
                'match_score' => $this->calculateScore($query, $city->name)
            ];
        }

        return $results;
    }
}
    //  * Unified location search - LinkedIn style with filtering
    //  * GET /api/location/search?q=sargodha&country=Pakistan&state=Punjab
    //  */


