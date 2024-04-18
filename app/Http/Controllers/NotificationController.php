<?php

namespace App\Http\Controllers;

use App\Models\Notification;

class NotificationController extends Controller
{
    public function store($userId, $message, $type, $reservationId, $action) : Notification {
        return Notification::create([
            'user_id' => $userId,
            'message' => $message,
            'type' => $type,
            'reservation_id' => $reservationId,
            'actions' => $action,
        ]);
    }

    public function show() {
        $user = auth()->user();
        if ($user->isAdmin() || $user->isSubAdmin()) {
            return $this->successResponse('notifications',
                Notification::with(['user'])
                    ->whereIn('type', [Notification::$all, Notification::$admin])
                    ->orderBy('id', 'desc')
                    ->get(),
                200);

        } else {
            return $this->successResponse('notifications',
                Notification::with('user')->whereIn('type', [Notification::$all, Notification::$user])
                    ->where('user_id', auth()->id())
                    ->orderBy('id', 'desc')
                    ->get(),
                200);
        }

    }
}
