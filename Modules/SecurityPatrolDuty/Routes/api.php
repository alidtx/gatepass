<?php

use Illuminate\Http\Request;
use Modules\SecurityPatrolDuty\Http\Controllers\SecurityPatrolDutyController;
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
    Route::get('/securtiy_patrol_duty',[SecurityPatrolDutyController::class,'index']);
    Route::post('/securtiy_patrol_duty/store',[SecurityPatrolDutyController::class,'store']);
    Route::get('/securtiy_patrol_duty/edit/{id}',[SecurityPatrolDutyController::class,'edit']);
    Route::post('/securtiy_patrol_duty/update/{id}',[SecurityPatrolDutyController::class,'update']);
    Route::delete('/securtiy_patrol_duty/delete/{id}',[SecurityPatrolDutyController::class,'destroy']);
});