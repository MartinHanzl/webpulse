<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Me\MeController;

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
    'prefix' => 'auth'
], function () {
    Route::post('register', [RegisterController::class, 'register']);

    Route::group([
        'middleware' => 'api'
    ], function () {
        Route::post('login', [LoginController::class, 'login']);
        Route::post('logout', [LoginController::class, 'logout']);
        Route::post('refresh', [LoginController::class, 'refresh']);

        Route::group([
            'prefix' => 'me'
        ], function () {
            Route::post('/', [LoginController::class, 'me']);
            Route::get('/profile', [MeController::class, 'profile']);
        });
    });
});
