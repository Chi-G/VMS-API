<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VisitorController extends Controller
{
    public function index(Request $request)
    {
        $visitors = Visitor::all();
        return response()->json($visitors);
    }

    public function show($id)
    {
        $visitor = Visitor::findOrFail($id);
        return response()->json($visitor);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'contact_number' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:visitors',
            'address' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $visitor = Visitor::create($request->all());

        return response()->json(['message' => 'Visitor created successfully', 'visitor' => $visitor]);
    }

    public function update(Request $request, $id)
    {
        $visitor = Visitor::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'contact_number' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:visitors,email,' . $visitor->id,
            'address' => 'sometimes|required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $visitor->update($request->only(['name', 'contact_number', 'email', 'address']));

        return response()->json(['message' => 'Visitor updated successfully', 'visitor' => $visitor]);
    }

    public function destroy($id)
    {
        $visitor = Visitor::findOrFail($id);
        $visitor->delete();

        return response()->json(['message' => 'Visitor deleted successfully']);
    }

    public function markAsVip($id)
    {
        $visitor = Visitor::findOrFail($id);
        $visitor->is_vip = true;
        $visitor->save();

        return response()->json(['message' => 'Visitor marked as VIP']);
    }

    public function blacklist($id)
    {
        $visitor = Visitor::findOrFail($id);
        $visitor->is_blacklisted = true;
        $visitor->save();

        return response()->json(['message' => 'Visitor blacklisted']);
    }
}
