<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\QuickAccessController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\UserGroupController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\Contact\ContactPhaseController;
use App\Http\Controllers\Contact\ContactSourceController;
use App\Http\Controllers\Contact\ContactTaskController;
use App\Http\Controllers\Contact\ContactController;

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

        // Profile routes
        Route::group([
            'prefix' => 'profile'
        ], function () {
            Route::get('', [ProfileController::class, 'index']);
            Route::post('', [ProfileController::class, 'store']);
            Route::post('password', [ProfileController::class, 'password']);
        });

        // User routes
        Route::group([
            'prefix' => 'user'
        ], function () {
            // User group routes
            Route::group([
                'prefix' => 'group'
            ], function () {
                Route::get('', [UserGroupController::class, 'index']);
                Route::get('{id}', [UserGroupController::class, 'show'])->where('id', '[0-9]+');
                Route::post('{id?}', [UserGroupController::class, 'store']);
                Route::delete('{id}', [UserGroupController::class, 'destroy'])->where('id', '[0-9]+');
            });

            Route::get('', [UserController::class, 'index']);
            Route::get('{id}', [UserController::class, 'show'])->where('id', '[0-9]+');
            Route::post('{id?}', [UserController::class, 'store']);
            Route::delete('{id}', [UserController::class, 'destroy'])->where('id', '[0-9]+');
        });

        // Contact routes
        Route::group([
            'prefix' => 'contact'
        ], function () {
            // Contact phase routes
            Route::group([
                'prefix' => 'phase'
            ], function () {
                Route::get('', [ContactPhaseController::class, 'index']);
                Route::get('{id}', [ContactPhaseController::class, 'show'])->where('id', '[0-9]+');
                Route::post('{id?}', [ContactPhaseController::class, 'store']);
                Route::delete('{id}', [ContactPhaseController::class, 'destroy'])->where('id', '[0-9]+');
            });

            // Contact source routes
            Route::group([
                'prefix' => 'source'
            ], function () {
                Route::get('', [ContactSourceController::class, 'index']);
                Route::get('{id}', [ContactSourceController::class, 'show'])->where('id', '[0-9]+');
                Route::post('{id?}', [ContactSourceController::class, 'store']);
                Route::delete('{id}', [ContactSourceController::class, 'destroy'])->where('id', '[0-9]+');
            });

            // Contact task routes
            Route::group([
                'prefix' => 'source'
            ], function () {
                Route::get('', [ContactTaskController::class, 'index']);
                Route::get('{id}', [ContactTaskController::class, 'show'])->where('id', '[0-9]+');
                Route::post('{id?}', [ContactTaskController::class, 'store']);
                Route::delete('{id}', [ContactTaskController::class, 'destroy'])->where('id', '[0-9]+');
            });

            Route::get('', [ContactController::class, 'index']);
            Route::get('{id}', [ContactController::class, 'show'])->where('id', '[0-9]+');
            Route::post('{id?}', [ContactController::class, 'store']);
            Route::delete('{id}', [ContactController::class, 'destroy'])->where('id', '[0-9]+');
        });
    });
});
