<?php

use Illuminate\Http\Request;
use Modules\KeylockCheck\Http\Controllers\KeylockCheckController;
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

    Route::get('/keylock_check',[KeylockCheckController::class,'index']);
    Route::post('/keylock_check/store',[KeylockCheckController::class,'store']);
    Route::get('/keylock_check/edit/{id}',[KeylockCheckController::class,'edit']);
    Route::post('/keylock_check/update/{id}',[KeylockCheckController::class,'update']);
    Route::delete('/keylock_check/delete/{id}',[KeylockCheckController::class,'destroy']);

});