<?php

use Illuminate\Http\Request;
use Modules\KeyReturnRegister\Http\Controllers\KeyReturnRegisterController;
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

    Route::get('/key_return_reg',[KeyReturnRegisterController::class,'index']);
    Route::post('/key_return_reg/store',[KeyReturnRegisterController::class,'store']);
    Route::get('/key_return_reg/edit/{id}',[KeyReturnRegisterController::class,'edit']);
    Route::post('/key_return_reg/update/{id}',[KeyReturnRegisterController::class,'update']);
    Route::delete('/key_return_reg/delete/{id}',[KeyReturnRegisterController::class,'destroy']);

});