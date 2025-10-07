<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\OtpController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LocationApiController;
use App\Http\Controllers\Api\GeoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
Route::prefix('geo')->group(function () {
    Route::get('/countries', [GeoController::class, 'countries']);
    Route::get('/states',    [GeoController::class, 'states']);
    Route::get('/cities',    [GeoController::class, 'cities']);
});
// routes/api.php


Route::get('/location/reverse', function (Request $req) {
    $lat = $req->float('lat'); $lng = $req->float('lng');
    abort_if($lat === null || $lng === null, 400, 'lat/lng required');

    // Call OSM Nominatim with English, identify your app (User-Agent)
    $resp = Http::withHeaders([
        'User-Agent' => 'ProMatch/1.0 (+contact@example.com)',
        'Accept-Language' => 'en',
    ])->get('https://nominatim.openstreetmap.org/reverse', [
        'format' => 'jsonv2',
        'lat' => $lat,
        'lon' => $lng,
        'zoom' => 10,
        'addressdetails' => 1,
        'accept-language' => 'en',
    ])->json();

    $a = $resp['address'] ?? [];
    return response()->json([
        'matched' => [
            'country_code' => strtoupper($a['country_code'] ?? ''),
            'state'        => $a['state'] ?? $a['region'] ?? $a['state_district'] ?? '',
            'city'         => $a['city'] ?? $a['town'] ?? $a['village'] ?? $a['municipality'] ?? '',
        ],
        'raw' => $a,
    ]);
});



Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    Route::post('otp/send', [OtpController::class, 'send']);
    Route::post('otp/verify', [OtpController::class, 'verify']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
});




Route::prefix('location')->group(function () {
    Route::get('countries', [LocationApiController::class, 'countries']);       // ?q=pak → optional search
    Route::get('states',    [LocationApiController::class, 'states']);          // ?country=Pakistan
    Route::get('cities',    [LocationApiController::class, 'cities']);          // ?country=Pakistan&state=Sindh
    Route::get('reverse',   [LocationApiController::class, 'reverse']);         // ?lat=..&lng=..
});
