<?php

use App\Http\Controllers\Client\Auth\LoginController;
use App\Http\Controllers\Client\Auth\RegisterController;
use App\Http\Controllers\Client\Me\MeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileManagerController;

// admin controllers
use App\Http\Controllers\Admin\Auth\RegisterController as AdminRegisterController;
use App\Http\Controllers\Admin\Auth\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\Me\MeController as AdminMeController;


Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('register', [RegisterController::class, 'register']);
    Route::post('verify', [RegisterController::class, 'verify']);

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

Route::group([
    'prefix' => 'files',
    'middleware' => 'api'
], function () {
    Route::post('image', [FileManagerController::class, 'uploadImage']);
});

Route::group([
    'prefix' => 'admin'
], function () {
    Route::group([
        'prefix' => 'auth'
    ], function () {
        Route::post('register', [AdminRegisterController::class, 'register']);

        Route::group([
            'middleware' => 'api'
        ], function () {
            Route::post('login', [AdminLoginController::class, 'login']);
            Route::post('logout', [AdminLoginController::class, 'logout']);
            Route::post('refresh', [AdminLoginController::class, 'refresh']);

            Route::group([
                'prefix' => 'me'
            ], function () {
                Route::post('/', [AdminLoginController::class, 'me']);
                Route::get('/profile', [AdminMeController::class, 'profile']);
            });
        });
    });
});
