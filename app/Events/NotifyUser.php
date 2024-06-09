<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotifyUser
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $notifiableId;
    public $type;
    public $title;
    public $message;
    public $redirectUrl;
    public $sendSms;
    public $sendEmail;
    public $sendPush;

    /**
     * Create a new event instance.
     *
     * @param int $notifiableId
     * @param string $type
     * @param string $message
     * @param string|null $redirectUrl
     */
    public function __construct($notifiableId, $type, $title, $message, $redirectUrl = null, $sendSms=false, $sendEmail=false, $sendPush=false)
    {
        $this->notifiableId = $notifiableId;
        $this->type = $type;
        $this->title = $title;
        $this->message = $message;
        $this->redirectUrl = $redirectUrl;
        $this->sendSms = $sendSms;
        $this->sendEmail = $sendEmail;
        $this->sendPush = $sendPush;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
