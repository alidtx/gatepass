<?php

namespace App\Traits;

use App\Modules\Config\Models\Config as ModelsConfig;
use Illuminate\Support\Facades\Log;

trait Curl
{

    public function call_curl($url, $token, $message = null)
    {
        /*api_key available in:
        Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key*/
        $api_key = env('PUSH_SERVER_KEY', 'AAAA0oGmUjU:APA91bHwVkq-Mg6JXk_r8xg5ZiBxdm71U5fZKALWqBIYCYN90yz1J__whH4l4z2P-Z2A7h0wpbrhJwMA3V5_H92nN1gl72zYUl21tlAJK7E8bpFlAUUs9rH4VAZW76G3k3JJGM_W5hIv');

        $data = [
            "priority" => "HIGH",
            "notification" => [
                "title" => $message['title'],
                "body" => $message['body'],
            ],
            "to" => $token ?? "",
        ];

        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'key=' . $api_key,
        ];

        //  return json_encode($headers);

        $url = "https://fcm.googleapis.com/fcm/send";

        $client = new \GuzzleHttp\Client();
        $request = $client->post($url, [
            'headers' => $headers,
            "body" => json_encode($data),
        ]);

        if ($request->getStatusCode() == 200) {
            $content = $request->getBody()->getContents();
            Log::info("push notification response: ". json_encode($content));
        }
    }
}
