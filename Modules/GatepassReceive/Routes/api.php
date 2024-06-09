<?php

use Illuminate\Http\Request;
use Modules\GatepassReceive\Http\Controllers\InternalReceiveController;
use Modules\GatepassReceive\Http\Controllers\ExternalReceiveController;

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
    Route::group(['prefix' => 'gatepass-receive/'], function () {
        Route::get("get-receive-no", [ExternalReceiveController::class, 'getReceiveNo']);
        Route::get("gatepass-list", [InternalReceiveController::class, 'gatepassNoList']);
        Route::post("store-document", [ExternalReceiveController::class, 'storeDocument']);
        Route::resource('internal', InternalReceiveController::class, ['parameters' => ['internal' => 'id']]);
        Route::resource('external', ExternalReceiveController::class, ['parameters' => ['external' => 'id']]);
    });
});