<?php

use App\Http\Controllers\Api\CompanyLogoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/companies/{company}/logo', [CompanyLogoController::class, 'store']);
    Route::delete('/companies/{company}/logo', [CompanyLogoController::class, 'destroy']);
});


Route::middleware('set.locale')->group(function () {
    require __DIR__ . '/auth.php';
    require __DIR__ . '/social_auth.php';
});
