<?php
namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Staff;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class StaffController extends Controller
{
    public function notifications()
    {
        $staff = Auth::guard('staff-api')->user();
        $notifications = Notification::where('user_id', $staff->id)->get();

        if ($notifications->isEmpty()) {
            return response()->json(['message' => 'No Notifications Yet']);
        }

        return response()->json(['notifications' => $notifications]);
    }

    public function profile()
    {
        $staff = Auth::guard('staff-api')->user();

        return response()->json([
            'name' => $staff->name,
            'email' => $staff->email,
            'position' => $staff->position,
            'company_name' => $staff->company_name,
            'employee_id' => $staff->employee_id,
            'profile_picture' => $staff->profile_picture,
        ]);
    }

    public function sendConfirmationEmail($visit_id)
    {
        $visit = Visit::with('visitor')->find($visit_id);

        if (!$visit) {
            return response()->json(['error' => 'Visit not found'], 404);
        }

        $visitor = $visit->visitor;
        $details = [
            'name' => $visitor->name,
            'email' => $visitor->email,
            'visit_date' => $visit->check_in,
            'host_name' => $visit->host_name,
            'purpose' => $visit->purpose,
            'qr_code' => 'generated_qr_code', // Replace with actual QR code generation logic
        ];

        Mail::send('emails.visit_confirmation', $details, function ($message) use ($visitor) {
            $message->to($visitor->email)
                ->subject('Visit Confirmation')
                ->from('no-reply@Sydani.com', 'Saydani Groups');
        });

        return response()->json(['message' => 'Confirmation email sent']);
    }
}
