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
         return $this->successResponse('notifications', Notification::with('user')->where('user_id', auth()->id())->get(), 200);
    }
}
