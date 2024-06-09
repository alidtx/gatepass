<?php

use Illuminate\Http\Request;
use Modules\VisitorRegister\Http\Controllers\VisitorRegisterController;
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

    Route::get('/visitor_register',[VisitorRegisterController::class,'index']);
    Route::post('/visitor_register/store',[VisitorRegisterController::class,'store']);
    Route::get('/visitor_register/edit/{id}',[VisitorRegisterController::class,'edit']);
    Route::post('/visitor_register/update/{id}',[VisitorRegisterController::class,'update']);
    Route::delete('/visitor_register/delete/{id}',[VisitorRegisterController::class,'destroy']);

});