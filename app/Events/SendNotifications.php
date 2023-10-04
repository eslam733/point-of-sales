<?php

namespace App\Events;

use App\Http\Controllers\NotificationController;
use App\Models\Notification;
use App\Models\User;
use App\Notifications\FirebaseNotification;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendNotifications
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct($userId, $message, $type = null)
    {
        $user = User::where('id', $userId)->first();
        $notificationController = new NotificationController();
        $notificationController->store($userId, $message, $type ?? Notification::$all);
        $user->notify(new FirebaseNotification($message));
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
