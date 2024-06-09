<?php

use Illuminate\Http\Request;
use Modules\ShortLeave\Http\Controllers\ShortLeaveController;
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
    Route::get('/short_leave',[ShortLeaveController::class,'index']);
    Route::post('/short_leave/store',[ShortLeaveController::class,'store']);
    Route::get('/short_leave/edit/{id}',[ShortLeaveController::class,'edit']);
    Route::post('/short_leave/update/{id}',[ShortLeaveController::class,'update']);
    Route::delete('/short_leave/delete/{id}',[ShortLeaveController::class,'destroy']);
});
