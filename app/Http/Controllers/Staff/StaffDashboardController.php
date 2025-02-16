<?php
namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class StaffDashboardController extends Controller
{
    public function summary()
    {
        $totalVisitorsToday = Visit::whereDate('check_in', today())->count();
        $upcomingVisits = Visit::whereDate('check_in', today())->where('status', 'Scheduled')->count();
        $pendingGuests = Visit::where('status', 'Pending')->count();

        return response()->json([
            'total_visitors_today' => $totalVisitorsToday,
            'upcoming_visits' => $upcomingVisits,
            'pending_guests' => $pendingGuests,
        ]);
    }

    public function recents()
    {
        $recents = Visit::with('visitor')
            ->orderBy('check_in', 'desc')
            ->take(10)
            ->get();

        return response()->json(['recents' => $recents]);
    }

    public function todaysGuests()
    {
        $todaysGuests = Visit::with('visitor')
            ->whereDate('check_in', today())
            ->get();

        return response()->json(['todays_guests' => $todaysGuests]);
    }

    public function checkInGuest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'guest_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $visit = Visit::where('visitor_id', $request->guest_id)->first();

        if (!$visit) {
            return response()->json(['error' => 'Visit not found'], 404);
        }

        $visit->status = 'Checked In';
        $visit->save();

        return response()->json(['message' => 'Guest checked in']);
    }

    public function cancelVisit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'guest_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $visit = Visit::where('visitor_id', $request->guest_id)->first();

        if (!$visit) {
            return response()->json(['error' => 'Visit not found'], 404);
        }

        $visit->status = 'Cancelled';
        $visit->save();

        return response()->json(['message' => 'Visit cancelled']);
    }

    public function pendingGuests()
    {
        $pendingGuests = Visit::with('visitor')
            ->where('status', 'Pending')
            ->get();

        return response()->json(['pending_guests' => $pendingGuests]);
    }
}
