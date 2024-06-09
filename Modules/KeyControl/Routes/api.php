<?php

use Illuminate\Http\Request;
use Modules\KeyControl\Http\Controllers\KeyControlController;
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

    Route::get('/key_control',[KeyControlController::class,'index']);
    Route::post('/key_control/store',[KeyControlController::class,'store']);
    Route::get('/key_control/edit/{id}',[KeyControlController::class,'edit']);
    Route::post('/key_control/update/{id}',[KeyControlController::class,'update']);
    Route::delete('/key_control/delete/{id}',[KeyControlController::class,'destroy']);

});