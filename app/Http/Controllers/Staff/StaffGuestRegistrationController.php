<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Visitor;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StaffGuestRegistrationController extends Controller
{
    public function registerIndividual(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'contact_number' => 'required|string',
            'email' => 'required|email',
            'address' => 'required|string',
            'visit_time' => 'required|date',
            'host_name' => 'required|string',
            'building' => 'required|string|in:building 1,building 2,building 3,building 4',
            'floor' => 'required|string|in:floor 1,floor 2,floor 3,floor 4',
            'purpose' => 'required|string|in:recruitment,business-meeting,repairs/maintenance,personal/unofficial,other',
            'special_requirements' => 'nullable|string|in:parking space,wheelchair/accessibility,language interpretation,other',
            'emergency_contact' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $visitor = Visitor::create($request->only([
            'name', 'contact_number', 'email', 'address', 'special_requirements', 'emergency_contact'
        ]));

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('visitor_photos', 'public');
            $visitor->photo = $path;
            $visitor->save();
        }

        $visit = Visit::create([
            'visitor_id' => $visitor->id,
            'visitor_type' => 'individual',
            'purpose' => $request->purpose,
            'check_in' => $request->visit_time,
            'host_name' => $request->host_name,
            'building' => $request->building,
            'floor' => $request->floor,
        ]);

        return response()->json(['message' => 'Individual guest registered successfully', 'visitor' => $visitor, 'visit' => $visit]);
    }

    public function registerGroup(Request $request)
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

        return response()->json(['message' => 'Group registered successfully', 'group_leader' => $groupLeader, 'visit' => $visit]);
    }

    public function searchVisitors(Request $request)
    {
        $query = $request->query('query');
        $visitors = Visitor::where('name', 'like', "%$query%")
            ->orWhere('contact_number', 'like', "%$query%")
            ->orWhere('email', 'like', "%$query%")
            ->get();

        return response()->json(['visitors' => $visitors]);
    }

    public function notify(Request $request)
    {
        // Logic to send notification to the host and visitor
        return response()->json(['message' => 'Notification sent']);
    }

    public function generateQrCode($guest_id)
    {
        $visit = Visit::with('visitor')->where('visitor_id', $guest_id)->first();

        if (!$visit) {
            return response()->json(['error' => 'Visit not found'], 404);
        }

        // Generate QR code (assuming you have a QR code generation logic)
        $qrCode = 'generated_qr_code'; // Replace with actual QR code generation logic

        return response()->json(['visit' => $visit, 'qr_code' => $qrCode]);
    }
}
