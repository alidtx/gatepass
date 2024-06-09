<?php

use Illuminate\Http\Request;
use Modules\SecurityLightOnOffRegister\Http\Controllers\SecurityLightOnOffRegisterController;
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

    Route::get('/security_light_register_on',[SecurityLightOnOffRegisterController::class,'index']);
    Route::post('/security_light_register_on/store',[SecurityLightOnOffRegisterController::class,'store']);
    Route::get('/security_light_register_on/edit/{id}',[SecurityLightOnOffRegisterController::class,'edit']);
    Route::post('/security_light_register_on/update/{id}',[SecurityLightOnOffRegisterController::class,'update']);
    Route::delete('/security_light_register_on/delete/{id}',[SecurityLightOnOffRegisterController::class,'destroy']);

});