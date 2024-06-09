<?php

use Illuminate\Http\Request;
use Modules\IdMissingRegister\Http\Controllers\IdMissingRegisterController;
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

    Route::get('/id_missing_reg',[IdMissingRegisterController::class,'index']);
    Route::post('/id_missing_reg/store',[IdMissingRegisterController::class,'store']);
    Route::get('/id_missing_reg/edit/{id}',[IdMissingRegisterController::class,'edit']);
    Route::post('/id_missing_reg/update/{id}',[IdMissingRegisterController::class,'update']);
    Route::delete('/id_missing_reg/delete/{id}',[IdMissingRegisterController::class,'destroy']);

});