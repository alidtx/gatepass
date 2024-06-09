<?php

use Illuminate\Http\Request;
use Modules\Exportgoods7Point\Http\Controllers\Exportgoods7PointController;
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

    Route::get('/export7Points',[Exportgoods7PointController::class,'index']);
    Route::post('/export7Points/store',[Exportgoods7PointController::class,'store']);
    Route::get('/export7Points/edit/{id}',[Exportgoods7PointController::class,'edit']);
    Route::post('/export7Points/update/{id}',[Exportgoods7PointController::class,'update']);
    Route::delete('/export7Points/delete/{id}',[Exportgoods7PointController::class,'destroy']);

});