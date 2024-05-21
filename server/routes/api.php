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
        'middleware' => 'auth:api'
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
    'prefix' => 'blog'
], function () {
    Route::group([
        'prefix' => 'posts'
    ], function () {
        Route::get('list', [\App\Http\Controllers\Client\Blog\PostController::class, 'list']);
        Route::get('detail/{id}', [\App\Http\Controllers\Client\Blog\PostController::class, 'detail'])->where('id', '[0-9]+');
        Route::post('create', [\App\Http\Controllers\Client\Blog\PostController::class, 'create']);
    });
});

Route::group([
    'prefix' => 'links'
], function () {
    Route::get('list/{lang}', [\App\Http\Controllers\Client\Settings\LinkController::class, 'list']);
});

Route::group([
    'prefix' => 'languages'
], function () {
    Route::get('list/{lang}', [\App\Http\Controllers\Client\LanguageController::class, 'list']);
});

/*
 * Admin routes
 */
Route::group([
    'prefix' => 'admin'
], function () {
    Route::group([
        'prefix' => 'auth'
    ], function () {
        Route::post('register', [AdminRegisterController::class, 'register']);
        Route::post('verify', [AdminRegisterController::class, 'verify']);

        Route::post('login', [AdminLoginController::class, 'login']);

        Route::group([
            'middleware' => 'auth:admin'
        ], function () {
            Route::post('logout', [AdminLoginController::class, 'logout']);
            Route::post('refresh', [AdminLoginController::class, 'refresh']);

            Route::group([
                'prefix' => 'me'
            ], function () {
                Route::get('', [AdminMeController::class, 'me']);
                Route::get('/profile', [AdminMeController::class, 'profile']);
            });
        });
    });
    Route::group([
        'middleware' => 'auth:admin'
    ], function () {
        Route::group([
            'prefix' => 'language'
        ], function () {
            Route::get('', [\App\Http\Controllers\Admin\Language\LanguageController::class, 'index']);
            Route::get('{id}', [\App\Http\Controllers\Admin\Language\LanguageController::class, 'show'])->where('id', '[0-9]+');
            Route::post('', [\App\Http\Controllers\Admin\Language\LanguageController::class, 'store']);
            Route::post('{id}', [\App\Http\Controllers\Admin\Language\LanguageController::class, 'update'])->where('id', '[0-9]+');
            Route::delete('{id}', [\App\Http\Controllers\Admin\Language\LanguageController::class, 'destroy'])->where('id', '[0-9]+');
        });
    });
});
