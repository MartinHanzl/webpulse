<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;

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
        Route::get('api/user', [LoginController::class, 'me'])->middleware('auth:sanctum');
    });

    // User routes
    Route::group([
        'middleware' => 'auth:sanctum'
    ], function () {
        // Contact routes
        Route::group([
            'prefix' => 'contact'
        ], function () {

        });
    });
});
