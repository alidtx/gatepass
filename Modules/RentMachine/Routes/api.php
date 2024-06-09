<?php

use Illuminate\Http\Request;
use Modules\RentMachine\Http\Controllers\RentMachineController;
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

    Route::get('/rent_machine',[RentMachineController::class,'index']);
    Route::post('/rent_machine/store',[RentMachineController::class,'store']);
    Route::get('/rent_machine/edit/{id}',[RentMachineController::class,'edit']);
    Route::post('/rent_machine/update/{id}',[RentMachineController::class,'update']);
    Route::delete('/rent_machine/delete/{id}',[RentMachineController::class,'destroy']);

});