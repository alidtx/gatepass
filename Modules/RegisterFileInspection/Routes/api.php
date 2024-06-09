<?php

use Illuminate\Http\Request;
use Modules\RegisterFileInspection\Http\Controllers\RegisterFileInspectionController;
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

    Route::get('/register_file_inspection',[RegisterFileInspectionController::class,'index']);
    Route::post('/register_file_inspection/store',[RegisterFileInspectionController::class,'store']);
    Route::get('/register_file_inspection/edit/{id}',[RegisterFileInspectionController::class,'edit']);
    Route::post('/register_file_inspection/update/{id}',[RegisterFileInspectionController::class,'update']);
    Route::delete('/register_file_inspection/delete/{id}',[RegisterFileInspectionController::class,'destroy']);

});