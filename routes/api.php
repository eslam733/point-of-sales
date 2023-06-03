<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Auth\RegisterController;
use \App\Http\Controllers\Auth\GoogleController;
use \App\Http\Controllers\CategoryController;
use \App\Http\Controllers\ItemController;
use \App\Http\Controllers\FeatureItemController;
use \App\Http\Controllers\ReservationController;

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

    Route::post('auth/regsiter', [RegisterController::class, 'register']);

    Route::group(['name' => 'Category'], function () {
        Route::post('category/create', [CategoryController::class, 'store']);
    });

    Route::group(['name' => 'Item'], function () {
        Route::post('item/create', [ItemController::class, 'store']);
        Route::delete('item/{id}', [ItemController::class, 'destroy']);
    });

    Route::group(['name' => 'FeatureItem'], function () {
        Route::post('featureItem/create', [FeatureItemController::class, 'store']);
    });

    Route::group(['name' => 'Reservation'], function () {
        Route::post('reservation/create', [ReservationController::class, 'store']);
        Route::post('reservation/getDatesForItem', [ReservationController::class, 'getDatesForItem']);
        Route::get('reservation/getReservation', [ReservationController::class, 'getReservation']);
    });
    
});

Route::group(['name' => 'Category'], function () {
    Route::get('category/show', [CategoryController::class, 'show']);
});




