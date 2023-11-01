<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\NotificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Auth\RegisterController;
use \App\Http\Controllers\Auth\GoogleController;
use \App\Http\Controllers\CategoryController;
use \App\Http\Controllers\ItemController;
use \App\Http\Controllers\FeatureItemController;
use \App\Http\Controllers\ReservationController;
use \App\Http\Controllers\UserController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('unauthorized', function () {
    return response()->json([
        'status'=> false,
        'message' => 'Unauthorized',
    ]);
});

Route::post('auth/google', [GoogleController::class, 'auth']);

Route::post('auth/admin/login', [LoginController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::group(['middleware' => ['OnlyAdmin']], function () {

        Route::group(['name' => 'Category'], function () {
            Route::post('category/create', [CategoryController::class, 'store']);
        });

        Route::group(['name' => 'Item', 'prefix' => 'item'], function () {
            Route::post('/create', [ItemController::class, 'store']);
            Route::delete('/{id}', [ItemController::class, 'destroy']);
        });

        Route::group(['name' => 'FeatureItem'], function () {
            Route::post('featureItem/create', [FeatureItemController::class, 'store']);
        });

        Route::group(['name' => 'Reservation', 'prefix' => 'reservation'], function () {
            Route::post('/getReservation', [ReservationController::class, 'getReservation']);
            Route::post('/{id}/changeStatus', [ReservationController::class, 'changeStatus']);
        });

        Route::group(['name' => 'Users', 'prefix' => 'users'], function () {
            Route::get('/', [UserController::class, 'index']);
            Route::delete('/delete/{id}', [UserController::class, 'destory']);
        });
    });

    Route::group(['name' => 'Item', 'prefix' => 'item'], function () {
        Route::get('/', [ItemController::class, 'index']);
    });

    Route::post('auth/regsiter', [RegisterController::class, 'register']);

    Route::group(['name' => 'Reservation', 'prefix' => 'reservation'], function () {
        Route::post('/create', [ReservationController::class, 'store']);
        Route::post('/getDatesForItem', [ReservationController::class, 'getDatesForItem']);
        Route::get('/myReservation', [ReservationController::class, 'myReservation']);
        Route::get('/{id}/canceledReservation', [ReservationController::class, 'canceledReservation']);
    });

    Route::group(['name' => 'Users', 'prefix' => 'users'], function () {
        Route::post('/updatePhone/{id}', [UserController::class, 'updatePhone']);
        Route::post('/updateDeviceToken/{id}', [UserController::class, 'updateDeviceToken']);
        Route::get('/checkNumber/{id}', [UserController::class, 'checkNumber']);
    });

    Route::group(['name' => 'Notifications', 'prefix' => 'notifications'], function () {
        Route::get('/', [NotificationController::class, 'show']);
    });

});

Route::group(['name' => 'Category'], function () {
    Route::get('category/show', [CategoryController::class, 'show']);
});




