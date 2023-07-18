<?php

namespace App\Http\Controllers\Api;

use App\Events\NotificationCreatedEvent;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function sendNotification(Request $request)
    {
        try {
            $notification = Notification::create([
                'title' => $request->title,
                'text' => $request->body,
                'user_id' => $request->receiver_id,
                'sender_id' => $request->sender_id,
                'notification_type' => 'message',
            ]);
            event(new NotificationCreatedEvent($notification, $request->receiver_id));
            return response()->json('Notification Sent',200);

        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 400);
        }
    }
}
