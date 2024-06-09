<?php

namespace App\Jobs;

use App\Traits\Curl;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendPushNotification implements ShouldQueue
{
    use Dispatchable, Queueable, InteractsWithQueue, SerializesModels, Curl;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $token;
    public $data;
    public $single;

    private $curlDetails = [
        "url" => "https://fcm.googleapis.com/fcm/send",
        "method" => "POST",
    ];

    /**
     * @var bool|mixed
     */

    public function __construct($data, $token, $single = true)
    {
        $this->token = $token;
        $this->data = $data;
        $this->single = $single;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data['to'] = $this->token;
        $data['title'] = $this->data['title'];
        $data['description'] = $this->data['body'];
        $this->call_curl($this->curlDetails['url'], $this->token, $this->data);
    }
}
