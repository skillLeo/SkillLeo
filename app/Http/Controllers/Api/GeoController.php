<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Nnjeim\World\Models\Country;
use Nnjeim\World\Models\State;
use Nnjeim\World\Models\City;

class GeoController extends Controller
{
    public function countries()
    {
        $data = Cache::rememberForever('geo:countries:v1', function () {
            return Country::query()
                ->select('id', 'name', 'iso2')
                ->orderBy('name')
                ->get();
        });

        return response()->json($data);
    }

    public function states(Request $request)
    {
        $code = strtoupper($request->query('country', ''));
        abort_unless($code && strlen($code) === 2, 422, 'country must be ISO2 (e.g., PK)');

        $cacheKey = "geo:states:$code";
        $data = Cache::remember($cacheKey, now()->addDays(30), function () use ($code) {
            return State::query()
                ->where('country_code', $code)
                ->select('id', 'name', 'state_code')
                ->orderBy('name')
                ->get();
        });

        return response()->json($data);
    }

    public function cities(Request $request)
    {
        $stateId = (int) $request->query('state_id');
        abort_unless($stateId > 0, 422, 'state_id is required');

        $cacheKey = "geo:cities:state:$stateId";
        $data = Cache::remember($cacheKey, now()->addDays(30), function () use ($stateId) {
            return City::query()
                ->where('state_id', $stateId)
                ->select('id', 'name')
                ->orderBy('name')
                ->get();
        });

        return response()->json($data);
    }
}
