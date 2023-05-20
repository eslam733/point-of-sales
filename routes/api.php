<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use PHPUnit\TextUI\Configuration\Group;
use \App\Http\Controllers\Auth\RegisterController;
use \App\Http\Controllers\Auth\GoogleController;

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

Route::get('unauthorized', function() {
    return response()->json([
        'status'=> false,
        'message' => 'Unauthorized',
    ]);
});

Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::post('auth/regsiter', [RegisterController::class, 'register']);
    
});

Route::post('auth/google', [GoogleController::class, 'auth']);

Route::post('auth/admin/login', [LoginController::class, 'login']);


