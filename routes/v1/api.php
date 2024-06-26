<?php

use App\Http\Controllers\Api\V1\AuthenticationController;
use App\Http\Controllers\Api\V1\ClaimController;
use App\Http\Controllers\Api\V1\EventRegistrationController;
use App\Http\Controllers\Api\V1\LeaderboardController;
use App\Http\Controllers\Api\V1\QuestController;
use App\Http\Controllers\Api\V1\ReferralController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('event-registration')->controller(EventRegistrationController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('verify', 'verify');
    Route::get('count', 'count');
    Route::get('resend/{email}', 'resend');
});

Route::prefix('auth')->controller(AuthenticationController::class)->group(function () {
    Route::post('register', 'register');
    Route::get('resend/{email}', 'resend');
    Route::post('verify', 'verify');
    Route::post('login', 'login');
    Route::post('forgot-password', 'forgotPassword');
    Route::post('verify-forgot-password', 'verifyForgotPassword');
    Route::post('resend-forgot-password', 'resendForgotPassword');
    Route::post('change-password', 'changePassword');
});

Route::middleware('auth:api')->group(function () {
    Route::prefix('auth')->controller(AuthenticationController::class)->group(function () {
        Route::get('user', 'getUser');
        Route::get('logout', 'logout');
    });
    Route::prefix('referral')->controller(ReferralController::class)->group(function () {
        Route::get('index', 'index');
    });

    Route::prefix('claim')->controller(ClaimController::class)->group(function () {
        Route::get('index', 'claim');
    });

    Route::prefix('leaderboard')->controller(LeaderboardController::class)->group(function () {
        Route::get('index', 'index');
    });

    Route::resource('quest', QuestController::class);
    Route::post('submit-quest', [QuestController::class,'submit']);
    Route::get('quest-submission', [QuestController::class, 'submissions']);
    Route::get('quest-submission/{proof}', [QuestController::class, 'submissionsSearch']);
});