<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Kreait\Firebase\Factory;
use Exception;

class FirebaseNotification extends Notification
{
    use Queueable;
    private $messaging;
    private $notificationBody;

    /**
     * Create a new notification instance.
     */
    public function __construct($notificationBody)
    {
        $this->notificationBody = $notificationBody;
        $this->messaging = (new Factory)
            ->withServiceAccount(storage_path('app/public/goblins-50e94-firebase-adminsdk-jyq12-f3bb0ca541.json'))
            ->createMessaging();

    }

    public function via($notifiable)
    {
        try {
            $message = CloudMessage::withTarget('token', $notifiable->device_token)
                ->withNotification(['title' => 'Reservation Status', 'body' => $this->notificationBody]);

            $this->messaging->send($message);
        } catch (Exception $e) {
            Log::error('error in sending notification via firebase' . $e->getMessage());
        }

    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
