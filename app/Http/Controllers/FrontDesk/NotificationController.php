<?php
namespace App\Http\Controllers\FrontDesk;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        // Fetch notifications from the database
        $notifications = [
            [
                'category' => 'Reminder',
                'title' => 'Reminder: You have 3 guests arriving today.',
                'message' => 'Please prepare for their arrival.',
                'timestamp' => 'Today | 1 min ago',
            ],
            [
                'category' => 'Check-in Failure',
                'title' => 'Check-in Failure: Guest could not check-in.',
                'message' => 'Please assist the guest at the front desk.',
                'timestamp' => 'Yesterday | 2:00 PM',
            ],
        ];

        return view('frontdesk.notifications.index', compact('notifications'));
    }
}
