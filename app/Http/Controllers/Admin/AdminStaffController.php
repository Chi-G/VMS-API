<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminStaffController extends Controller
{
    public function index(Request $request)
    {
        $staff = Staff::all();
        return response()->json($staff);
    }

    public function show($id)
    {
        $staff = Staff::findOrFail($id);
        return response()->json($staff);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:staff',
            'role' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $staff = Staff::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'department' => $request->department,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'Staff created successfully', 'staff' => $staff]);
    }

    public function update(Request $request, $id)
    {
        $staff = Staff::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:staff,email,' . $staff->id,
            'role' => 'sometimes|required|string|max:255',
            'department' => 'sometimes|required|string|max:255',
            'password' => 'sometimes|required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $staff->update($request->only(['name', 'email', 'role', 'department', 'password']));

        if ($request->has('password')) {
            $staff->password = Hash::make($request->password);
            $staff->save();
        }

        return response()->json(['message' => 'Staff updated successfully', 'staff' => $staff]);
    }

    public function destroy($id)
    {
        $staff = Staff::findOrFail($id);
        $staff->delete();

        return response()->json(['message' => 'Staff deleted successfully']);
    }

    public function deactivate($id)
    {
        $staff = Staff::findOrFail($id);
        $staff->status = false;
        $staff->save();

        return response()->json(['message' => 'Staff account deactivated']);
    }
}
