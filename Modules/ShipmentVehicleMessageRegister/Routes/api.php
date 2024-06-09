<?php

use Illuminate\Http\Request;
use Modules\ShipmentVehicleMessageRegister\Http\Controllers\ShipmentVehicleMessageRegisterController;
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

    Route::get('/shipment_vehicle_msg_reg',[ShipmentVehicleMessageRegisterController::class,'index']);
    Route::post('/shipment_vehicle_msg_reg/store',[ShipmentVehicleMessageRegisterController::class,'store']);
    Route::get('/shipment_vehicle_msg_reg/edit/{id}',[ShipmentVehicleMessageRegisterController::class,'edit']);
    Route::post('/shipment_vehicle_msg_reg/update/{id}',[ShipmentVehicleMessageRegisterController::class,'update']);
    Route::delete('/shipment_vehicle_msg_reg/delete/{id}',[ShipmentVehicleMessageRegisterController::class,'destroy']);

});