<?php

use Illuminate\Http\Request;
use Modules\WastageDelivery\Http\Controllers\WastageDeliveryController;
use Illuminate\Auth\Events\Login;
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

// Route::middleware('auth:api')->get('/wastagedelivery', function (Request $request) {
//     return $request->user();
// });


// Route::resource("wastagedelivery", WastageDeliveryController::class, ['parameters' => ['users' => 'id']]);





Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/wastagedelivery',[WastageDeliveryController::class,'index']);
    Route::post('/wastagedelivery/store',[WastageDeliveryController::class,'store']);
    Route::get('/wastagedelivery/edit/{id}',[WastageDeliveryController::class,'edit']);
    Route::post('/wastagedelivery/update/{id}',[WastageDeliveryController::class,'update']);
    Route::delete('/wastagedelivery/delete/{id}',[WastageDeliveryController::class,'destroy']);
});


