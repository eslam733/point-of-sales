<?php

namespace App\Http\Controllers;

use App\Models\Notification;

class NotificationController extends Controller
{
    public function store($userId, $message) : Notification {
        return Notification::create([
            'user_id' => $userId,
            'message' => $message,
        ]);
    }

    public function show() {
        $this->authorize('viewNotifications', Notification::class);

        return Notification::get();
    }
}
