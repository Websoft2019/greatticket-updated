<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()->notifications;
        return response()->json($notifications);
    }

    public function markAsRead($notificationId)
{
    $notification = auth()->user()->notifications()->find($notificationId);
    if ($notification) {
        $notification->markAsRead();
        return response()->json(['message' => 'Notification marked as read']);
    }

    return response()->json(['message' => 'Notification not found'], 404);
}
}
