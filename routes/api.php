<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::middleware('set.locale')->group(function () {
    require __DIR__ . '/auth.php';
    require __DIR__ . '/social_auth.php';
});
