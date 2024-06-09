<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Traits;

use App\Mail\ForgetOtpEmail;
use App\Mail\OtpMail;
use Modules\User\Entities\Otp;
use Modules\User\Entities\OtpCount;
use Carbon\Carbon;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Mail;

/**
 *
 * @author arnob
 */
/*
 * type = login_otp,forget_password,etc
 */

trait OtpAndTokenTrait
{

    use SendNewSmsApiTrait;

    public function Otp($mobile, $type, $send_in, $user = null, $ipAddress = null, $otp_hash = null)
    {
        $otpCount = OtpCount::where('mobile', $mobile)
                    ->where('type', $type)
                    ->orderBy('created_at', 'desc')
                    ->first();

        if ($otpCount) {

            $diff = $this->__diffBetweenTwoDateTime($otpCount->updated_at, date('Y-m-d H:i:s'));
            $returnData = $this->__canSendOtp($otpCount->count, $diff, $otpCount->type);

            if ($returnData['status']) {
                $otpCount->count = $otpCount->count + 1;
                $otpCount->ip = $ipAddress;
                $otpCount->updated_at = date('Y-m-d H:i:s');
                $otpCount->save();
            } else {
                    return ['status' => false, 'message' => $returnData['message'], 'code' => $returnData['code'] ?? 422];
                }
        } else {
            $otpCount = new OtpCount();
            $otpCount->user_id = (is_null($user)) ? null : $user->id;
            $otpCount->mobile = $mobile;
            $otpCount->count = 1;
            $otpCount->type = $type;
            $otpCount->ip = $ipAddress;
            $otpCount->created_at = date('Y-m-d H:i:s');
            $otpCount->updated_at = date('Y-m-d H:i:s');
            $otpCount->save();
        }

        $token = $this->__sendAndStoreOtp($mobile, $type, $user, $ipAddress);
        
        $smsBody = "";
        if($type == "forget_password") {
            $smsBody = "Dear Customer,
            Your Ecopia password reset OTP is: __otp__
            This OTP is valid for next 2 min.
            Ecopia";
        }

        $smsBody = str_replace('__otp__', $token . ' ', $smsBody);
        if($otp_hash) {
            $smsBody = $smsBody. ' '.$otp_hash;
        }

        if ($send_in == 'mobile') {
            //New Isms
            $this->sendSms($smsBody, $mobile);
        } 
        return ['status' => true, 'message' => ''];
    }

    public function getUserIp(HttpRequest $request)
    {
        // Gettingip address of remote user
        return $user_ip_address = $request->ip();
    }

    private function __diffBetweenTwoDateTime($fromDateTime, $toDateTime)
    {
        $dt1 = new \DateTime($fromDateTime);
        $dt2 = new \DateTime($toDateTime);
        return $dteDiff = $dt1->diff($dt2);
    }

    private function __canSendOtp($count, $diff, $type)
    {
        if ($diff->h >= 1) {
            return ['status' => true, 'message' => ''];
        }

        if ($diff->i >= 1) {
            return ['status' => true, 'message' => ''];
        }

        if ($diff->i < 2) {
            // code param send for app as a flag to identify that error
            return ['status' => false, 'message' => 'You can only request OTP once every 2 minutes.', 'code'=> 406];
        }

        return ['status' => false, 'message' => __('app.try_agian_after_a_while')];
    }

    public function verifyOtp($mobile, $otp, $type, $delete = true)
    {

        $formatted_date = Carbon::now()->subMinutes(2)->toDateTimeString();
        $data = Otp::where('mobile', $mobile)
                ->where('token', $otp)
                ->where('type', $type)
                ->where('updated_at', '>', $formatted_date)
                ->orderBy('id', 'desc')
                ->first();
        
        if ($data) {
            /* remove this otp */
            if ($delete) {
                Otp::where('id', $data->id)->delete();
            }

            return ['status' => true, 'data' => $data];
        } else {
            return ['status' => false, 'data' => null];
        }
    }

    private function __sendAndStoreOtp($mobile, $type, $user = null, $ipAddress = null)
    {
        while (1) {
            if (config('misc.notification.sms.enable_sms_sending') == 'no') {
                $token = 12345; //for local or demo
            } else {
                $token = rand(10000, 99999);
            }
            break;
            if (!Otp::where('token', $token)->exists()) {
                break;
            }
        }

        $data = Otp::where('mobile', $mobile)->where('type', $type)->orderBy('created_at', 'desc');

        if (!is_null($user)) {
            $data->where('user_id', $user->id);
        }

        $data = $data->first();

        if ($data) {
            //update
            $data->token = $token;
            $data->updated_at = date('Y-m-d H:i:s');
            $data->ip = $ipAddress;
            $data->save();
        } else {
            //create
            $data = new Otp();
            $data->user_id = (is_null($user)) ? null : $user->id;
            $data->mobile = $mobile;
            $data->token = $token;
            $data->type = $type;
            $data->created_at = date('Y-m-d H:i:s');
            $data->updated_at = date('Y-m-d H:i:s');
            $data->ip = $ipAddress;
            $data->save();
        }

        return $token;
    }
}
