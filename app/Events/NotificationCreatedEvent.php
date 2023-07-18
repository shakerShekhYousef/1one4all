<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class NotificationCreatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $notification;
    public $receiver_id;

    public function __construct($notification, $receiver_id)
    {

        $this->notification = $notification;
        $this->receiver_id = $receiver_id;

    }


    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
