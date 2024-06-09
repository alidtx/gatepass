<?php

use Illuminate\Http\Request;
use Modules\MedicalRoom\Http\Controllers\MedicalRoomController;
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
    Route::get('/medical_room',[MedicalRoomController::class,'index']);
    Route::post('/medical_room/store',[MedicalRoomController::class,'store']);
    Route::get('/medical_room/edit/{id}',[MedicalRoomController::class,'edit']);
    Route::post('/medical_room/update/{id}',[MedicalRoomController::class,'update']);
    Route::delete('/medical_room/delete/{id}',[MedicalRoomController::class,'destroy']);

});