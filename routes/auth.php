<?php

use App\Http\Controllers\Auth\UserAuthController;
use Illuminate\Support\Facades\Route;



Route::post('/register', [UserAuthController::class, 'register']);
Route::post('/login', [UserAuthController::class, 'login']);


// Request reset password link
Route::post('/password/forgot', [UserAuthController::class, 'sendResetLinkEmail']);

// Reset password
Route::post('/password/reset', [UserAuthController::class, 'resetPassword']);



Route::post('/social-login', [UserAuthController::class, 'socialLogin']);

// Route::get('/email/verify/{id}/{hash}', [UserAuthController::class, 'verifyEmail'])->name('verification.verify');




Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [UserAuthController::class, 'user']);
    Route::post('/logout', [UserAuthController::class, 'logout']);

    Route::post('/user/name', [UserAuthController::class, 'updateUserName']);


    /**Send email verification link from frontend */

    Route::post('/email/verification-notification', [UserAuthController::class, 'resendVerificationEmail'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
});
