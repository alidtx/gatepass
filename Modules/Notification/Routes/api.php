<?php

use Illuminate\Http\Request;
use Modules\Notification\Http\Controllers\NotificationController;

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
    Route::group(['prefix' => 'notifications/'], function () {
        Route::get("unread-notification-lists", [NotificationController::class, 'unreadNotificationLists']);
        Route::get("total-unread-notifications", [NotificationController::class, 'totalUnreadNotifications']);
    });
    Route::resource("notifications", NotificationController::class, ['parameters' => ['notifications' => 'id']]);
});