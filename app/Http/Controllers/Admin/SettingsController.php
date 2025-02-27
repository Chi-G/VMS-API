<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Admin;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    public function index(Request $request)
    {
        $settings = Setting::where('admin_id', $request->user()->id)->get();
        return response()->json($settings);
    }

    public function update(Request $request)
    {
        $request->validate([
            'key' => 'required|string',
            'value' => 'required|string',
        ]);

        $setting = Setting::updateOrCreate(
            ['admin_id' => $request->user()->id, 'key' => $request->key],
            ['value' => $request->value]
        );

        return response()->json(['message' => 'Setting updated', 'setting' => $setting]);
    }

    public function delete(Request $request)
    {
        $request->validate([
            'key' => 'required|string',
        ]);

        $setting = Setting::where('admin_id', $request->user()->id)->where('key', $request->key)->first();

        if ($setting) {
            $setting->delete();
            return response()->json(['message' => 'Setting deleted']);
        }

        return response()->json(['message' => 'Setting not found'], 404);
    }

    public function fetchRoles()
    {
        $roles = [
            'Admin', 'Staff', 'Front Desk', 'Security'
        ];
        return response()->json($roles);
    }

    public function updateRoles(Request $request)
    {
        $request->validate([
            'role' => 'required|string',
            'permissions' => 'required|array',
        ]);

        $role = Role::where('name', $request->role)->firstOrFail();

        // Detach all existing permissions
        $role->permissions()->detach();

        // Attach the new permissions
        foreach ($request->permissions as $permissionName) {
            $permission = Permission::firstOrCreate(['name' => $permissionName]);
            $role->permissions()->attach($permission);
        }

        return response()->json(['message' => 'Role updated']);
    }

    public function fetchNotifications(Request $request)
    {
        $notifications = Setting::where('admin_id', $request->user()->id)->where('key', 'like', 'notification_%')->get();
        return response()->json($notifications);
    }

    public function updateNotifications(Request $request)
    {
        $request->validate([
            'key' => 'required|string',
            'value' => 'required|string',
        ]);

        $notification = Setting::updateOrCreate(
            ['admin_id' => $request->user()->id, 'key' => $request->key],
            ['value' => $request->value]
        );

        return response()->json(['message' => 'Notification updated', 'notification' => $notification]);
    }

    public function fetchDataRetention(Request $request)
    {
        $dataRetention = Setting::where('admin_id', $request->user()->id)->where('key', 'like', 'data_retention_%')->get();
        return response()->json($dataRetention);
    }

    public function updateDataRetention(Request $request)
    {
        $request->validate([
            'key' => 'required|string',
            'value' => 'required|string',
        ]);

        $dataRetention = Setting::updateOrCreate(
            ['admin_id' => $request->user()->id, 'key' => $request->key],
            ['value' => $request->value]
        );

        return response()->json(['message' => 'Data retention policy updated', 'dataRetention' => $dataRetention]);
    }

    public function fetchSecuritySettings(Request $request)
    {
        $securitySettings = Setting::where('admin_id', $request->user()->id)->where('key', 'like', 'security_%')->get();
        return response()->json($securitySettings);
    }

    public function updateSecuritySettings(Request $request)
    {
        $request->validate([
            'key' => 'required|string',
            'value' => 'required|string',
        ]);

        $securitySetting = Setting::updateOrCreate(
            ['admin_id' => $request->user()->id, 'key' => $request->key],
            ['value' => $request->value]
        );

        return response()->json(['message' => 'Security setting updated', 'securitySetting' => $securitySetting]);
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email',
            'phone' => 'nullable|string',
            'profile_picture' => 'nullable|string',
        ]);

        $admin = $request->user();
        $admin->update($request->only('name', 'email', 'phone', 'profile_picture'));

        return response()->json(['message' => 'Profile updated', 'admin' => $admin]);
    }

    public function addAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:admins',
            'password' => 'required|string|minimum:8',
            'role' => 'required|string',
            'department' => 'nullable|string',
            'profile_picture' => 'nullable|string',
        ]);

        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'department' => $request->department,
            'profile_picture' => $request->profile_picture,
        ]);

        return response()->json(['message' => 'Admin added', 'admin' => $admin]);
    }

    public function fetchCompanyDetails(Request $request)
    {
        $companyDetails = Setting::where('admin_id', $request->user()->id)->where('key', 'like', 'company_%')->get();
        return response()->json($companyDetails);
    }

    public function updateCompanyDetails(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string',
            'company_logo' => 'nullable|string',
            'company_email' => 'required|string|email',
        ]);

        $companyDetails = [
            'company_name' => $request->company_name,
            'company_logo' => $request->company_logo,
            'company_email' => $request->company_email,
        ];

        foreach ($companyDetails as $key => $value) {
            Setting::updateOrCreate(
                ['admin_id' => $request->user()->id, 'key' => $key],
                ['value' => $value]
            );
        }

        return response()->json(['message' => 'Company details updated']);
    }
}
