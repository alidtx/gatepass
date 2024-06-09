<?php

use Illuminate\Http\Request;
use Modules\Party\Http\Controllers\PartyController;

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
    Route::group(['prefix' => 'gatepass/'], function () {
        Route::get("party-list", [PartyController::class, 'index']);
        Route::post("party-store", [PartyController::class, 'store']);
        Route::resource("parties", PartyController::class, ['parameters' => ['parties' => 'id']]);
    });
});