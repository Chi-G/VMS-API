<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VisitController extends Controller
{
    public function index(Request $request)
    {
        $visits = Visit::all();
        return response()->json($visits);
    }

    public function show($id)
    {
        $visit = Visit::findOrFail($id);
        return response()->json($visit);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'visitor_id' => 'required|exists:visitors,id',
            'staff_id' => 'required|exists:staff,id',
            'visitor_type' => 'required|string|max:255',
            'purpose' => 'required|string|max:255',
            'check_in' => 'required|date',
            'check_out' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $visit = Visit::create($request->all());

        return response()->json(['message' => 'Visit created successfully', 'visit' => $visit]);
    }

    public function update(Request $request, $id)
    {
        $visit = Visit::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'visitor_id' => 'sometimes|required|exists:visitors,id',
            'staff_id' => 'sometimes|required|exists:staff,id',
            'visitor_type' => 'sometimes|required|string|max:255',
            'purpose' => 'sometimes|required|string|max:255',
            'check_in' => 'sometimes|required|date',
            'check_out' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $visit->update($request->only(['visitor_id', 'staff_id', 'visitor_type', 'purpose', 'check_in', 'check_out']));

        return response()->json(['message' => 'Visit updated successfully', 'visit' => $visit]);
    }

    public function destroy($id)
    {
        $visit = Visit::findOrFail($id);
        $visit->delete();

        return response()->json(['message' => 'Visit deleted successfully']);
    }
}
