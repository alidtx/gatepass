<?php

use Illuminate\Http\Request;
use Modules\Gatepass\Http\Controllers\GatepassTypeController;
use Modules\Gatepass\Http\Controllers\ItemController;
use Modules\Gatepass\Http\Controllers\UnitController;
use Modules\Gatepass\Http\Controllers\GatepassController;
use Modules\Gatepass\Http\Controllers\GatepassApproveController;
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
    Route::group(['prefix' => 'gatepass/'], function () {
        Route::get("type-list", [GatepassTypeController::class, 'index']);
        Route::get("get-list", [GatepassController::class, 'index']);
        Route::post("get-gatepass-no", [GatepassController::class, 'getGatePassNo']);
        Route::post("store", [GatepassController::class, 'store']);
        Route::get("gatepass-edit/{id}", [GatepassController::class, 'edit']);
        Route::post("gatepass-update/{id}", [GatepassController::class, 'update']);
        Route::post("store-document", [GatepassController::class, 'storeDocument']);
        Route::delete("delete/{id}", [GatepassController::class, 'destroy']);

        Route::post("approve/{id}", [GatepassController::class, 'approve']);
        Route::post("bulk-approve", [GatepassController::class, 'bulkApprove']);
        Route::post("final-approve/{id}", [GatepassController::class, 'finalApprove']);
        Route::post("bulk-final-approve", [GatepassController::class, 'bulkFinalApprove']);
        
        Route::resource("types", GatepassTypeController::class, ['parameters' => ['types' => 'id']]);
    });
});