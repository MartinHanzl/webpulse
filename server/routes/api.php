<?php

use App\Http\Controllers\Admin\Activity\ActivityController;
use App\Http\Controllers\Admin\Activity\UserActivityController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\RegisterController;
use App\Http\Controllers\Admin\Cashflow\CashflowBudgetController;
use App\Http\Controllers\Admin\Cashflow\CashflowCategoryController;
use App\Http\Controllers\Admin\Cashflow\CashflowController;
use App\Http\Controllers\Admin\Contact\ContactController;
use App\Http\Controllers\Admin\Contact\ContactPhaseController;
use App\Http\Controllers\Admin\Contact\ContactSourceController;
use App\Http\Controllers\Admin\Contact\ContactTaskController;
use App\Http\Controllers\Admin\Message\MessageBlueprintController;
use App\Http\Controllers\Admin\User\ProfileController;
use App\Http\Controllers\Admin\User\QuickAccessController;
use App\Http\Controllers\Admin\User\UserController;
use App\Http\Controllers\Admin\User\UserGroupController;
use App\Http\Controllers\Controller as BaseController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\TaxRate\TaxRateController;
use App\Http\Controllers\Admin\Project\ProjectController;
use App\Http\Controllers\Admin\Project\ProjectStatusController;

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

            Route::group([
                'prefix' => 'activity'
            ], function () {
                Route::get('', [UserActivityController::class, 'index']);
                Route::get('{id}', [UserActivityController::class, 'show'])->where('id', '[0-9]+');
                Route::post('{id?}', [UserActivityController::class, 'store']);
                Route::delete('{id}', [UserActivityController::class, 'destroy'])->where('id', '[0-9]+');
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
                'prefix' => 'task'
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
            Route::post('history/{id}/{historyId?}', [ContactController::class, 'history'])->where('id', '[0-9]+')->where('historyId', '[0-9]+');
            Route::delete('history/destroy/{id}', [ContactController::class, 'historyDestroy'])->where('id', '[0-9]+');
        });

        Route::group([
            'prefix' => 'message'
        ], function () {
            Route::group([
                'prefix' => 'blueprint'
            ], function () {
                Route::get('', [MessageBlueprintController::class, 'index']);
                Route::get('{id}', [MessageBlueprintController::class, 'show'])->where('id', '[0-9]+');
                Route::post('{id?}', [MessageBlueprintController::class, 'store']);
                Route::delete('{id}', [MessageBlueprintController::class, 'destroy'])->where('id', '[0-9]+');
            });
        });

        // Activity routes
        Route::group([
            'prefix' => 'activity'
        ], function () {
            Route::get('', [ActivityController::class, 'index']);
            Route::get('{id}', [ActivityController::class, 'show'])->where('id', '[0-9]+');
            Route::post('{id?}', [ActivityController::class, 'store']);
            Route::delete('{id}', [ActivityController::class, 'destroy'])->where('id', '[0-9]+');
        });

        // Cashflow routes
        Route::group([
            'prefix' => 'cashflow'
        ], function () {
            // Cashflow category routes
            Route::group([
                'prefix' => 'category'
            ], function () {
                Route::get('', [CashflowCategoryController::class, 'index']);
                Route::get('{id}', [CashflowCategoryController::class, 'show'])->where('id', '[0-9]+');
                Route::post('{id?}', [CashflowCategoryController::class, 'store']);
                Route::delete('{id}', [CashflowCategoryController::class, 'destroy'])->where('id', '[0-9]+');
            });

            // Cashflow budget routes
            Route::group([
                'prefix' => 'budget'
            ], function () {
                Route::post('{id?}', [CashflowBudgetController::class, 'store']);
            });

            Route::post('{id?}', [CashflowController::class, 'store']);
        });

        // Tax-rate routes
        Route::group([
            'prefix' => 'tax-rate'
        ], function () {
            Route::get('', [TaxRateController::class, 'index']);
            Route::get('{id}', [TaxRateController::class, 'show'])->where('id', '[0-9]+');
            Route::post('{id?}', [TaxRateController::class, 'store']);
            Route::delete('{id}', [TaxRateController::class, 'destroy'])->where('id', '[0-9]+');
        });

        // Projects routes
        Route::group([
            'prefix' => 'project'
        ], function () {
            // Project status routes
            Route::group([
                'prefix' => 'status'
            ], function () {
                Route::get('', [ProjectStatusController::class, 'index']);
                Route::get('{id}', [ProjectStatusController::class, 'show'])->where('id', '[0-9]+');
                Route::post('{id?}', [ProjectStatusController::class, 'store']);
                Route::delete('{id}', [ProjectStatusController::class, 'destroy'])->where('id', '[0-9]+');
            });

            Route::get('', [ProjectController::class, 'index']);
            Route::get('{id}', [ProjectController::class, 'show'])->where('id', '[0-9]+');
            Route::post('{id?}', [ProjectController::class, 'store']);
            Route::delete('{id}', [ProjectController::class, 'destroy'])->where('id', '[0-9]+');
        });

        // Dashboard and statistics routes
        Route::get('dashboard', [BaseController::class, 'dashboard']);
        Route::get('statistics', [BaseController::class, 'statistics']);
    });
});
