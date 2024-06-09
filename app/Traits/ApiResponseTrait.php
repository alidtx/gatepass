<?php

namespace App\Traits;

trait ApiResponseTrait
{
    // public function sendSMS($body)
    // {
    //     $user = env("SMS_USER");
    //     $pass = env("SMS_PASS");
    //     $sid = env("SMS_SID");
    //     $url = env("SMS_URL");

    //     $phone = $body["phone"];
    //     $message = $body["message"];

    //     $param = "user=$user&pass=$pass&sms[0][0]= " . $phone . "&sms[0][1]=" . urlencode($message) . "&sid=$sid";
    //     $crl = curl_init();
    //     curl_setopt($crl, CURLOPT_SSL_VERIFYPEER, FALSE);
    //     curl_setopt($crl, CURLOPT_SSL_VERIFYHOST, 2);
    //     curl_setopt($crl, CURLOPT_URL, $url);
    //     curl_setopt($crl, CURLOPT_HEADER, 0);
    //     curl_setopt($crl, CURLOPT_RETURNTRANSFER, 1);
    //     curl_setopt($crl, CURLOPT_POST, 1);
    //     curl_setopt($crl, CURLOPT_POSTFIELDS, $param);
    //     $response = curl_exec($crl);
    //     curl_close($crl);
    // }

    public function exceptionResponse(string $message)
    {
        return response()->json([
            'code' => 500,
            'status' => 'fail',
            'message' => $message,
            'data' => null
        ], 200);
    }

    /**
     * Invalid Request Response / Custom Validation Response
     *
     * @param string $message
     * @param array|null $errors
     * @return \Illuminate\Http\JsonResponse
     */
    public function invalidResponse($code, $message, array $errors = null)
    {
        return response()->json([
            'code' => $code,
            'status' => 'fail',
            'message' => gettype($message) === 'array' ? $message : array($message),
            'data' => $errors,

        ], 200);
    }

    public function successResponse($code, $message, $data = null)
    {
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => gettype($message) === 'array' ? $message : array($message),
            'data' => $data,

        ], 200);
    }

    public function successResponseNew($message, $data)
    {
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => gettype($message) === 'array' ? $message : array($message),
            'data' => $data,

        ], 200);
    }

    /**
     * Response with Access Token
     *
     * @param $accessToken
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondWithAccessToken($accessToken)
    {
        return response()->json([
            'code' => 200,
            'message' => [],
            'data' => $accessToken
        ], 200);
    }


    public function unauthorizedResponse($messages)
    {
        return response()->json([
            'code' => 401,
            'message' => $messages
            // 'data' => ""
        ], 200);
    }

    public function _encrypt_string($data = "")
    {
        $iv = str_random(16);
        $encrypted = openssl_encrypt($data, "aes-256-cbc", 'G7RAi4BTpa32H1ykg56LkrjqTBoEYqCc', 0, $iv);

        return base64_encode($iv . '||' . $encrypted);
    }

    /**
     * Invalid Request Response / Custom Validation Response
     *
     * @param array $messages
     * @param string $middleware
     * @return \Illuminate\Http\JsonResponse
     */
    public function middlewareResponse(array $messages, $middleware)
    {
        return response()->json([
            'code' => 900,
            'message' => $messages,
            'middleware' => $middleware
        ], 200);
    }

    public function response($messages, $code, $data = [])
    {
        return response()->json([
            'code' => $code,
            'message' => $messages,
            'data' => $data
        ], 200);
    }
}
