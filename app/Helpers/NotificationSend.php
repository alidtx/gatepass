<?php

namespace App\Helpers;

use App\Jobs\SendPushNotification;
use Modules\Notification\Entities\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendEMailNotification;

class NotificationSend
{

    public function sendNotification($users, $type, $title, $dataBody, $redirectUrl = null, $sendSms=false, $sendEmail=false, $sendPush=false)
    {
        try {
            // $users = User::with("firebase")->whereIn('id', $to)->get();
                
                $dbdata = ['title' => $title,'body' => $dataBody, 'notification_type' => $type];
                $toEmail = null;
                foreach($users as $user) {
                    
                    //Store Notification Data To Database
                    Notification::create([
                        'type' => $title, 
                        'notifiable_id' => $user->id, 
                        'message' => $dataBody, 
                        'redirect_url' => $redirectUrl
                    ]);

                    //Send Email To Admin
                    if($user->email && $sendEmail) {
                        Mail::to($user->email)->send(new SendEMailNotification($title, $dataBody));
                    }

                    
                    //Send Push Notification to User
                    $message = $dbdata;
                    if ($sendPush) {
                        if(isset($user->firebase)) {
                            foreach ($user->firebase as $firebase) {
                                dispatch(new SendPushNotification($message, $firebase->fcm_token));
                            }
                        }
                    }
                }

        } catch (\Exception $exception) {
            Log::error([$exception->getFile(), $exception->getLine(), $exception->getMessage()]);
        }
    }
}
