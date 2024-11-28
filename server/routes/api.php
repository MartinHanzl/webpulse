<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\QuickAccessController;
use App\Http\Controllers\User\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::group([
    'prefix' => 'admin'
], function () {
    Route::post('register', [RegisterController::class, 'index']);

    // Login, logout, and refresh token routes
    Route::group([
        'prefix' => 'auth'
    ], function () {
        Route::post('login', [LoginController::class, 'login']);
        Route::post('logout', [LoginController::class, 'logout'])->middleware('auth:sanctum');
        Route::get('refresh', [LoginController::class, 'refresh'])->middleware('auth:sanctum');
        Route::get('me', [LoginController::class, 'me'])->middleware('auth:sanctum');
        Route::get('profile', [UserController::class, 'profile'])->middleware('auth:sanctum');
    });

    // User routes
    Route::group([
        'middleware' => 'auth:sanctum',
    ], function () {
        // Quick access routes
        Route::group([
            'prefix' => 'quick-access'
        ], function () {
            Route::get('', [QuickAccessController::class, 'index']);
            Route::get('{id}', [QuickAccessController::class, 'show'])->where('id', '[0-9]+');
            Route::post('{id?}', [QuickAccessController::class, 'store']);
            Route::delete('{id}', [QuickAccessController::class, 'destroy'])->where('id', '[0-9]+');
        });

        Route::group([
            'prefix' => 'user'
        ], function () {
            Route::get('', [UserController::class, 'index']);
            Route::get('{id}', [UserController::class, 'show'])->where('id', '[0-9]+');
            Route::post('{id?}', [UserController::class, 'store']);
            Route::delete('{id}', [UserController::class, 'destroy'])->where('id', '[0-9]+');
        });
    });
});
