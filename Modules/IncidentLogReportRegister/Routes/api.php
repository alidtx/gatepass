<?php

use Illuminate\Http\Request;
use Modules\IncidentLogReportRegister\Http\Controllers\IncidentLogReportRegisterController;
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

    Route::get('/incident_log_report_register',[IncidentLogReportRegisterController::class,'index']);
    Route::post('/incident_log_report_register/store',[IncidentLogReportRegisterController::class,'store']);
    Route::get('/incident_log_report_register/edit/{id}',[IncidentLogReportRegisterController::class,'edit']);
    Route::post('/incident_log_report_register/update/{id}',[IncidentLogReportRegisterController::class,'update']);
    Route::delete('/incident_log_report_register/delete/{id}',[IncidentLogReportRegisterController::class,'destroy']);

});