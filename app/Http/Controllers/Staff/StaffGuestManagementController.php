<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Visitor;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StaffGuestManagementController extends Controller
{
    public function getIndividualGuests(Request $request)
    {
        $status = $request->query('status');
        $search = $request->query('search');
        $date = $request->query('date');

        $query = Visit::with('visitor')
            ->where('visitor_type', 'individual');

        if ($status) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->whereHas('visitor', function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('contact_number', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            });
        }

        if ($date) {
            $query->whereDate('check_in', $date);
        }

        $visits = $query->get();

        return response()->json(['visits' => $visits]);
    }

    public function filterGuests(Request $request)
    {
        $name = $request->query('name');
        $visitDate = $request->query('visit_date');
        $status = $request->query('status');

        $query = Visit::with('visitor')
            ->where('visitor_type', 'individual');

        if ($name) {
            $query->whereHas('visitor', function ($q) use ($name) {
                $q->where('name', 'like', "%$name%");
            });
        }

        if ($visitDate) {
            $query->whereDate('check_in', $visitDate);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $visits = $query->get();

        return response()->json(['visits' => $visits]);
    }

    public function cancelVisit($guest_id)
    {
        $visit = Visit::where('visitor_id', $guest_id)->first();

        if (!$visit) {
            return response()->json(['error' => 'Visit not found'], 404);
        }

        $visit->status = 'Cancelled';
        $visit->save();

        return response()->json(['message' => 'Visit cancelled']);
    }

    public function rescheduleVisit(Request $request, $guest_id)
    {
        $validator = Validator::make($request->all(), [
            'new_date' => 'required|date',
            'new_time' => 'required|date_format:H:i',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $visit = Visit::where('visitor_id', $guest_id)->first();

        if (!$visit) {
            return response()->json(['error' => 'Visit not found'], 404);
        }

        $visit->check_in = $request->new_date . ' ' . $request->new_time;
        $visit->status = 'Rescheduled';
        $visit->save();

        return response()->json(['message' => 'Visit rescheduled']);
    }

    public function getGroupVisits(Request $request)
    {
        $status = $request->query('status');

        $query = Visit::with('visitor')
            ->where('visitor_type', 'group');

        if ($status) {
            $query->where('status', $status);
        }

        $visits = $query->get();

        return response()->json(['visits' => $visits]);
    }

    public function scheduleGroupVisit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'group_leader_name' => 'required|string',
            'group_leader_contact' => 'required|string',
            'group_leader_email' => 'required|email',
            'organization_name' => 'required|string',
            'group_size' => 'required|integer',
            'visit_time' => 'required|date',
            'host_name' => 'required|string',
            'building' => 'required|string|in:building 1,building 2,building 3,building 4',
            'floor' => 'required|string|in:floor 1,floor 2,floor 3,floor 4',
            'purpose' => 'required|string|in:corporate event,conference,team meeting,workshop,other',
            'special_requirements' => 'nullable|string|in:parking arrangements[for multiple vehicles],meeting room,catering needs,other',
            'emergency_contact' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $groupLeader = Visitor::create([
            'name' => $request->group_leader_name,
            'contact_number' => $request->group_leader_contact,
            'email' => $request->group_leader_email,
            'address' => $request->organization_name,
            'special_requirements' => $request->special_requirements,
            'emergency_contact' => $request->emergency_contact,
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('visitor_photos', 'public');
            $groupLeader->photo = $path;
            $groupLeader->save();
        }

        $visit = Visit::create([
            'visitor_id' => $groupLeader->id,
            'visitor_type' => 'group',
            'purpose' => $request->purpose,
            'check_in' => $request->visit_time,
            'host_name' => $request->host_name,
            'building' => $request->building,
            'floor' => $request->floor,
            'group_size' => $request->group_size,
        ]);

        return response()->json(['message' => 'Group visit scheduled successfully', 'group_leader' => $groupLeader, 'visit' => $visit]);
    }

    public function rescheduleGroupVisit(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'new_date' => 'required|date',
            'new_time' => 'required|date_format:H:i',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $visit = Visit::find($id);

        if (!$visit) {
            return response()->json(['error' => 'Visit not found'], 404);
        }

        $visit->check_in = $request->new_date . ' ' . $request->new_time;
        $visit->status = 'Rescheduled';
        $visit->save();

        return response()->json(['message' => 'Group visit rescheduled']);
    }

    public function cancelGroupVisit($id)
    {
        $visit = Visit::find($id);

        if (!$visit) {
            return response()->json(['error' => 'Visit not found'], 404);
        }

        $visit->status = 'Cancelled';
        $visit->save();

        return response()->json(['message' => 'Group visit cancelled']);
    }
}
