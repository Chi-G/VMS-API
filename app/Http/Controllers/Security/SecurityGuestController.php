<?php
namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use App\Models\Visitor;
use App\Models\Visit;
use App\Models\BelongingsCheckIn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SecurityGuestController extends Controller
{
    public function confirm(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'guest_code' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $visitor = Visitor::where('guest_code', $request->guest_code)->first();

        if (!$visitor) {
            return response()->json(['error' => 'Guest not found'], 404);
        }

        // Mark the guest as checked in
        $visit = Visit::where('visitor_id', $visitor->id)->where('status', 'Scheduled')->first();
        if ($visit) {
            $visit->status = 'Confirmed';
            $visit->save();
        }

        return response()->json(['message' => 'Guest confirmed', 'visitor' => $visitor]);
    }

    public function getGuestDetails($guest_id)
    {
        $visitor = Visitor::with('visits')->find($guest_id);

        if (!$visitor) {
            return response()->json(['error' => 'Guest not found'], 404);
        }

        return response()->json(['visitor' => $visitor]);
    }

    public function getBelongings($guest_id)
    {
        $belongings = BelongingsCheckIn::where('visitor_id', $guest_id)->get();

        if ($belongings->isEmpty()) {
            return response()->json(['error' => 'No belongings found'], 404);
        }

        return response()->json(['belongings' => $belongings]);
    }

    public function confirmStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'guest_id' => 'required|integer',
            'status' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $visit = Visit::where('visitor_id', $request->guest_id)->first();

        if (!$visit) {
            return response()->json(['error' => 'Visit not found'], 404);
        }

        $visit->status = $request->status;
        $visit->save();

        return response()->json(['message' => 'Status updated']);
    }

    public function checkInBelongings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'guest_id' => 'required|integer',
            'items' => 'required|array',
            'items.*.name' => 'required|string',
            'items.*.type' => 'required|string',
            'items.*.quantity' => 'required|integer',
            'items.*.description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        foreach ($request->items as $item) {
            BelongingsCheckIn::create([
                'visitor_id' => $request->guest_id,
                'name' => $item['name'],
                'type' => $item['type'],
                'quantity' => $item['quantity'],
                'description' => $item['description'],
            ]);
        }

        return response()->json(['message' => 'Belongings checked in']);
    }

    public function checkout(Request $request)
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

        $visit->status = 'Checked Out';
        $visit->save();

        return response()->json(['message' => 'Guest checked out']);
    }

    public function notifyFrontDesk(Request $request)
    {
        // Logic to notify the front desk
        return response()->json(['message' => 'Front desk notified']);
    }
}
