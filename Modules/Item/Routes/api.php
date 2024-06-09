<?php

use Illuminate\Http\Request;
use Modules\Item\Http\Controllers\ItemController;

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
        Route::get("item-list", [ItemController::class, 'index']);
        Route::resource("items", ItemController::class, ['parameters' => ['items' => 'id']]);
    });
});