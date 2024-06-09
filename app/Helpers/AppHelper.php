<?php

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Modules\DownloadImportManager\Entities\DownloadImportManager;
use Modules\Notification\Entities\Notification;
use Modules\Notification\Transformers\NotificationResource;
use Modules\Setting\Entities\Setting;

if (!function_exists('tableDataInfo')) {
    function tableDataInfo($table)
    {
        $table->softDeletes();
        $table->foreignId('created_by')->nullable()->comment('0 for system');
        $table->foreignId('updated_by')->nullable()->comment('0 for system');
    }
}

if (!function_exists('ajaxResponse')) {

    function ajaxResponse($code, $message = "The given data was invalid.", $errors = null, $data = null)
    {

        if (!is_null($errors)) {
            if (!is_object($errors)) {
                $errors = (object) $errors;
            }
        }
        return response()->json(
            [
                'message' => $message,
                'errors'  => $errors,
                'data'    => $data,
                'code'    => $code,
            ],
            200);
    }
}

if (!function_exists('apiResponse')) {

    function apiResponse($code, $message = "", $errors = null, $data = null)
    {
        return response()->json(
            [
                'code'    => $code,
                'message' => $message,
                'data'    => $data,
                'errors'  => $errors,
            ],
            200);
    }
}

if (!function_exists('exceptionResponse')) {

    function exceptionResponse($code, $message = "", $errors = null, $data = null)
    {
        return response()->json(
            [
                'code'    => $code,
                'message' => $message,
                'data'    => $data,
                'errors'  => $errors,
            ],
            500);
    }
}

if (!function_exists('classResponse')) {

    function classResponse($status, $message = "The given data was invalid.", $errors = null, $data = null)
    {

        return
            [
            'message' => $message,
            'errors'  => $errors,
            'data'    => $data,
            'status'  => $status,
        ];

    }
}

/**
 * create import download manager task
 * @param       object      authUser        auth()->user()
 * @param       string      title           Title of the task
 * @param       string      type            Download or Import
 * @param       string      url             if any url needs to be downloaded. (Optional)
 * @return      int         row id
 */
if (!function_exists('importDownloadManagerCreate')) {
    function importDownloadManagerCreate($authUser, $title, $type, $url = null)
    {
        $downloadImportManager          = new DownloadImportManager();
        $downloadImportManager->user_id = $authUser->id;
        $downloadImportManager->title   = $title;
        $downloadImportManager->type    = $type;
        $downloadImportManager->url     = $url;
        $downloadImportManager->save();

        return $downloadImportManager->id;
    }
}

/**
 * update import download manager after finished the task
 * @param       int         downloadImportId    Update row ID
 * @param       string      status              Complete, Pending, Failed
 * @param       string      url                 if any url needs to be downloaded. (Optional)
 * @param       string      remarks             Remarks
 */
if (!function_exists('importDownloadManagerUpdate')) {
    function importDownloadManagerUpdate($downloadImportId, $status, $remarks = null, $url = null)
    {
        // Log::info('before url: ' . $url);
        $downloadImportManager = DownloadImportManager::find($downloadImportId);
        if ($downloadImportManager) {
            $downloadImportManager->remarks = $remarks;
            $downloadImportManager->status  = $status;
            // if (!isNull($url)) {
            //     Log::info('url: ' . $url);
            $downloadImportManager->url = $url;
            // }
            $downloadImportManager->save();
            __sendNotification([$downloadImportManager->user_id], $downloadImportManager->type . ' is ' . $downloadImportManager->status, $downloadImportManager->title);
        } else {
            throw new Exception('Download Import Manager ID not found');
        }
    }

}

if (!function_exists('getStudentIdUnderParentId')) {

    function getStudentIdUnderParentId($parent_id)
    {
        return User::where('parent_id', $parent_id)->pluck('id');
    }
}

if (!function_exists('getInsituteActiveStudentId')) {

    function getInsituteActiveStudentId($institute_id)
    {
        return User::where('parent_id', $institute_id)
            ->where('status', 'Active')
            ->pluck('id');
    }
}

if (!function_exists('getDiffrentBetweenTwoDate')) {

    function getDiffrentBetweenTwoDate($startTime, $endTime)
    {
        // get diffrent in minutes between two dates

        $start = Carbon::parse($startTime);
        $end   = Carbon::parse($endTime);
        $diff  = $start->diffInMinutes($end);
        return $diff . ' minutes';

    }
}

if (!function_exists('getPaymentMethods')) {

    function getPaymentMethods()
    {
        //    get all payment methods will be used in application
        return ['Cash', 'Cheque', 'Card', 'Paypal','Venmo','Balance'];
    }
}
if (!function_exists('getPaymentMethodForBalance')) {

    function getPaymentMethodForBalance()
    {
        //    get all payment methods will be used in application
        return ['Cash', 'Cheque', 'Card', 'Paypal','Venmo'];
    }
}

if (!function_exists('getPaymentType')) {

    function getPaymentType()
    {
        //    get all payment type
        return ['With Balance', 'Without Balance'];
    }
}

if (!function_exists('getSettings')) {

    function getSettings()
    {
        //    get all payment methods will be used in application
        return Setting::pluck('value', 'key');
    }
}

/**
-------------------------------------
notification helper functions started
-------------------------------------
 */

/**
 * make new notification for a user
 * @param       array       $userIds        the user ids of notification receiver
 * @param       string      $title          notification title (header)
 * @param       string      $body           notification description
 * @param       string      $url            any clickable link if needs to redirect. Always pas the full path with base url **optional**
 */
if (!function_exists('__sendNotification')) {
    function __sendNotification($userIds, $title, $body, $url = null)
    {
        try {
            $insertArray = [];
            foreach ($userIds as $userId) {
                $insertArray[] = [
                    'receiver_id' => $userId,
                    'title'       => $title,
                    'body'        => $body,
                    'url'         => $url,
                    'created_at'  => Carbon::now(),
                    'updated_at'  => Carbon::now(),
                ];
            }
            Notification::insert($insertArray);
        } catch (\Exception $exception) {
            \Illuminate\Support\Facades\Log::error("Error occurred on helper::__sendNotification. " . $exception->getMessage());
        }
    }
}

/**
 * get unread notifications for a user
 * @param       int         $userId         the user id of notification receiver
 * @return      collection
 */
if (!function_exists('__getUnreadNotification')) {
    function __getUnreadNotification($userId)
    {
        try {
            $unreadNotifications = Notification::where('receiver_id', $userId)->whereNull('read_at')->orderBy('created_at', 'desc')->limit(50)->get();

            return NotificationResource::collection($unreadNotifications);
        } catch (\Exception $exception) {
            \Illuminate\Support\Facades\Log::error("Error occurred on helper::__getUnreadNotification. " . $exception->getMessage());
        }
    }
}

/**
 * notification time view
 * @param       Carbon         $carbon         created_at time
 */
if (!function_exists('__getNotificationTimeAgo')) {
    function __getNotificationTimeAgo($carbon)
    {
        return str_ireplace(
            [' seconds', ' second', ' minutes', ' minute', ' hours', ' hour', ' days', ' day', ' weeks', ' week'],
            ['s', 's', 'm', 'm', 'h', 'h', 'd', 'd', 'w', 'w'],
            $carbon->diffForHumans()
        );
    }
}

/**
-------------------------------------
notification helper functions ended
-------------------------------------
 */

if (!function_exists('getSettingsValue')) {
    function getSettingsValue($key)
    {
        $value = Setting::where('key', $key)->first();
        return $value->value ?? "";
    }
}


if (!function_exists('defaultCountryId')) {
    function defaultCountryId()
    {
        return 1;
    }
}
if (!function_exists('getSessionStatus')) {
    function getSessionStatus()
    {
       return  ['Pending', 'Ongoing', 'Completed', 'Billable Cancellation', 'Non-Billable Cancellation'];
    }
}

if (!function_exists('validationErrorResponse')) {
    function validationErrorResponse($message)
    {
        return response()->json([
            'status' => false,
            'code' => 422, // $this->getStatusCode(), //OR 200
            'message' => gettype($message) === 'array' ? $message : array($message),
            'errors' => $message,
        ], 200);
    }
}



