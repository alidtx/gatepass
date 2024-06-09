<?php

use Illuminate\Http\Request;
use Modules\Report\Http\Controllers\ReportController;

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
    Route::group(['prefix' => 'report/'], function () {
        Route::get("internal-gatepasses", [ReportController::class, 'getInternalReceivedGatepasses']);
        Route::get("gatepass-excel/{id}", [ReportController::class, 'getGatepassReport']);
        Route::get("gatepass/{id}", [ReportController::class, 'downloadGatepassReport']);
        Route::get("download-internal-gatepasses", [ReportController::class, 'downloadInternalGatepassExcel']);
    });
});