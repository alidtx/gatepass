<?php

use Illuminate\Http\Request;
use Modules\WastageCarryingLabor\Http\Controllers\WastageCarryingLaborController;
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

    Route::get('/wastage_carrying_labor',[WastageCarryingLaborController::class,'index']);
    Route::post('/wastage_carrying_labor/store',[WastageCarryingLaborController::class,'store']);
    Route::get('/wastage_carrying_labor/edit/{id}',[WastageCarryingLaborController::class,'edit']);
    Route::post('/wastage_carrying_labor/update/{id}',[WastageCarryingLaborController::class,'update']);
    Route::delete('/wastage_carrying_labor/delete/{id}',[WastageCarryingLaborController::class,'destroy']);

});