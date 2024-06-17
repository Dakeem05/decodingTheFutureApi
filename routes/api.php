<?php

use App\Http\Controllers\Api\V1\EventRegistrationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['prefix' => 'v1'], function () {
    Route::prefix('event-registration')->controller(EventRegistrationController::class)->group(function () {
        Route::post('register', 'register');
        Route::post('verify', 'verify');
        Route::get('count', 'count');
        Route::get('resend/{email}', 'resend');
    });
});