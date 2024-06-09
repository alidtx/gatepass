<?php

namespace App\Listeners;

use App\Events\NotifyUser;
use App\Helpers\NotificationSend;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(NotifyUser $event)
    {
        $notificationInitiate = new NotificationSend();
        $notificationInitiate->sendNotification(
            $event->notifiableId, 
            $event->type, 
            $event->title, 
            $event->message, 
            $event->redirectUrl,
            $event->sendSms,
            $event->sendEmail,
            $event->sendPush
        );
    }
}
