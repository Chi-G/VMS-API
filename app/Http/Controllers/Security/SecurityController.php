<?php
namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use App\Models\Visit;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SecurityController extends Controller
{
    public function history(Request $request)
    {
        $month = $request->query('month');
        $visitorType = $request->query('visitor_type', 'all');
        $category = $request->query('category', 'all');

        $query = Visit::with('visitor', 'staff');

        if ($month) {
            $query->whereMonth('visit_date', $month);
        }

        if ($visitorType !== 'all') {
            $query->where('visitor_type', $visitorType);
        }

        if ($category !== 'all') {
            $query->where('category', $category);
        }

        $visits = $query->get()->groupBy(function ($visit) {
            return $visit->visit_date->format('Y-m-d');
        });

        return response()->json(['visits' => $visits]);
    }

    public function notifications()
    {
        $user = Auth::user();
        $notifications = Notification::where('user_id', $user->id)->get();

        if ($notifications->isEmpty()) {
            return response()->json(['message' => 'No Notifications Yet']);
        }

        return response()->json(['notifications' => $notifications]);
    }

    public function userProfile()
    {
        $user = Auth::user();

        return response()->json([
            'name' => $user->name,
            'email' => $user->email,
            'position' => $user->position,
            'company_name' => $user->company_name,
            'employee_id' => $user->employee_id,
        ]);
    }
}
