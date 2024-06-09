<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Gatecheck\Entities\Gatecheck;
use Modules\Gatecheck\Entities\GatecheckNotificationLog;
use App\Events\NotifyUser;
use App\Models\User;
use Illuminate\Support\Facades\Event;

class NotifyGatechecks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:gatechecks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notifications to responsible persons for gatechecks with status 0 after 1 hour of release_date_time';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Get gatechecks with status 0 and release_date_time older than 1 hour
        $gatechecks = Gatecheck::whereHas('gatepassInfo', function ($query) {
                            $query->where('type_id', 1);
                        })
                        ->where('status', 0)
                        ->where('release_date_time', '<', now()->subHour())
                        ->get();
                        
        foreach ($gatechecks as $gatecheck) {
            // Check if notification has already been sent for this gatecheck
            if (!$this->notificationSent($gatecheck)) {
                // Send notification to responsible person
                $this->sendNotification($gatecheck);

                // Log the notification to prevent duplicate notifications
                $this->logNotification($gatecheck);
            }
        }
    }

    private function notificationSent($gatecheck)
    {
        // Check if a notification has been logged for this gatecheck
        return GatecheckNotificationLog::where('gatecheck_id', $gatecheck->id)->exists();
    }

    private function sendNotification($gatecheck)
    {
        $type = 'Internal Gatepass Not Received';
        $title = 'Internal Gatepass Not Received Yet';
        $gatepass = $gatecheck?->gatepassInfo?->gate_pass_no;
        $message = 'Internal Gatepass: ' . $gatepass . ' has not been received yet. Please check the details.';

        $userDetails = User::whereHas('userAssignedRoles', function ($query) {
            $query->whereHas('roleInfo', function ($query) {
                $query->where('name', 'GM');
            });
        })->get();

        // Send notification to the determined user IDs
        if ($userDetails->count() > 0) {
            Event::dispatch(new NotifyUser(
                $userDetails,
                $type,
                $title,
                $message,
                null, // redirect url
                false, // send sms
                false, // send email
                true // send push
            ));
        }
    }

    private function logNotification($gatecheck)
    {
        // Log the notification to prevent duplicate notifications
        GatecheckNotificationLog::create([
            'gatecheck_id' => $gatecheck->id,
            'sent_at' => now()
        ]);
    }
}
