<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\LoginController;
use Modules\User\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post("login", [LoginController::class, 'login']);
Route::post('fcm-token-update', [UserController::class, 'fcmTokenUpdate']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post("logout", [LoginController::class, 'logout']);

    Route::group(['prefix' => 'user/'], function () {
        Route::get("list", [UserController::class, 'index']);
        Route::resource("sources", UserSourceController::class, ['parameters' => ['sources' => 'id']]);
    });

    Route::resource("users", UserController::class, ['parameters' => ['users' => 'id']]);
});