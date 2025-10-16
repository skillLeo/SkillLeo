<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\OtpController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LocationApiController;
use App\Http\Controllers\Api\GeoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;



use App\Http\Controllers\Api\InstitutionController;
use App\Http\Controllers\Api\CompanyController;

Route::get('/companies/search', [CompanyController::class, 'search'])->name('api.companies.search');
Route::get('/companies',        [CompanyController::class, 'index'])->name('api.companies.index');

Route::get('/institutions/search', [InstitutionController::class, 'search'])->name('api.institutions.search');
Route::get('/institutions',        [InstitutionController::class, 'index'])->name('api.institutions.index');





 


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
    Route::get('search', [LocationApiController::class, 'search']);
    Route::get('details', [LocationApiController::class, 'getLocation']);
});