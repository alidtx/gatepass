<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Gatecheck\Http\Controllers\GatecheckController;
use Modules\User\Http\Controllers\LoginController;

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

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post("logout", [LoginController::class, 'logout']);

    Route::group(['prefix' => 'gatecheck/'], function () {
        Route::get("list", [GatecheckController::class, 'index']);
        Route::post("store", [GatecheckController::class, 'store']);
        Route::get("edit/{id}", [GatecheckController::class, 'edit']);
        Route::post("update/{id}", [GatecheckController::class, 'update']);
        Route::delete("delete/{id}", [GatecheckController::class, 'destroy']);
    });
});