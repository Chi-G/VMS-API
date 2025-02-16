<?php
namespace App\Http\Controllers\FrontDesk;

use App\Http\Controllers\Controller;
use App\Models\Visitor;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GuestRegistrationController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'contact_number' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:visitors',
            'address' => 'required|string|max:255',
            'visit_time' => 'required|date',
            'host_name' => 'required|string|max:255',
            'building' => 'required|string|max:255',
            'floor' => 'required|string|max:255',
            'purpose' => 'required|string|max:255',
            'special_requirements' => 'nullable|string|max:255',
            'emergency_contact' => 'nullable|string|max:255',
            'photo' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $visitor = Visitor::create([
            'name' => $request->name,
            'contact_number' => $request->contact_number,
            'email' => $request->email,
            'address' => $request->address,
            'special_requirements' => $request->special_requirements,
            'emergency_contact' => $request->emergency_contact,
            'photo' => $request->photo ? $request->photo->store('photos', 'public') : null,
        ]);

        $visit = Visit::create([
            'visitor_id' => $visitor->id,
            'host_id' => $request->host_id,
            'visit_date' => $request->visit_time,
            'status' => 'Scheduled',
            'special_requirements' => $request->special_requirements,
        ]);

        return response()->json(['message' => 'Guest registered successfully', 'visitor' => $visitor, 'visit' => $visit]);
    }

    public function searchVisitors(Request $request)
    {
        $query = $request->query('query');
        $visitors = Visitor::where('name', 'LIKE', "%{$query}%")
            ->orWhere('email', 'LIKE', "%{$query}%")
            ->orWhere('contact_number', 'LIKE', "%{$query}%")
            ->get();

        return response()->json($visitors);
    }

    public function registerGroup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'leader_name' => 'required|string|max:255',
            'organization_name' => 'required|string|max:255',
            'group_size' => 'required|integer',
            'pre_visit_instructions' => 'nullable|string|max:255',
            'visit_time' => 'required|date',
            'host_name' => 'required|string|max:255',
            'building' => 'required|string|max:255',
            'floor' => 'required|string|max:255',
            'purpose' => 'required|string|max:255',
            'special_requirements' => 'nullable|string|max:255',
            'emergency_contact' => 'nullable|string|max:255',
            'photo' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $leader = Visitor::create([
            'name' => $request->leader_name,
            'contact_number' => $request->contact_number,
            'email' => $request->email,
            'address' => $request->address,
            'special_requirements' => $request->special_requirements,
            'emergency_contact' => $request->emergency_contact,
            'photo' => $request->photo ? $request->photo->store('photos', 'public') : null,
        ]);

        $visit = Visit::create([
            'visitor_id' => $leader->id,
            'host_id' => $request->host_id,
            'visit_date' => $request->visit_time,
            'status' => 'Scheduled',
            'special_requirements' => $request->special_requirements,
        ]);

        return response()->json(['message' => 'Group registered successfully', 'leader' => $leader, 'visit' => $visit]);
    }
}
