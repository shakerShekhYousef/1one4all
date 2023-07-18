<?php

namespace App\Listeners;

use App\Consumers\Notifications;
use App\Events\NotificationCreatedEvent;
use App\Models\Device;

class NotificationListener
{
    public function onCreate($event)
    {

        $tokens = Device::where('user_id', $event->receiver_id)->pluck('firebase_token')->toArray();

        if (count($tokens) != 0 ){
            $noti = new Notifications();
            $noti->send($event->notification, $tokens);

            return array_merge( ['notification' => $noti]);
        }
        return 0;

    }

    public function subscribe($events)
    {
        $events->listen(
            NotificationCreatedEvent::class,
            'App\Listeners\NotificationListener@onCreate'
        );

    }
}
