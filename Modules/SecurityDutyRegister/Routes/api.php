<?php

use Illuminate\Http\Request;
use Modules\SecurityDutyRegister\Http\Controllers\SecurityDutyRegisterController;
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
    
    Route::get('/security_register_duty',[SecurityDutyRegisterController::class,'index']);
    Route::post('/security_register_duty/store',[SecurityDutyRegisterController::class,'store']);
    Route::get('/security_register_duty/edit/{id}',[SecurityDutyRegisterController::class,'edit']);
    Route::post('/security_register_duty/update/{id}',[SecurityDutyRegisterController::class,'update']);
    Route::delete('/security_register_duty/delete/{id}',[SecurityDutyRegisterController::class,'destroy']);

});