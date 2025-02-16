<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller 
{
    public function index(Request $request)
    {
        $notifications = $request->user()->notifications;
        return response()->json($notifications);
    }

    public function markAsRead(Request $request)
    {
        $notification = Notification::find($request->id);
        $notification->read = true;
        $notification->save();

        return response()->json(['message' => 'Notification marked as read']);
    }

    public function clear(Request $request)
    {
        $request->user()->notifications()->delete();
        return response()->json(['message' => 'All notifications cleared']);
    }
}
