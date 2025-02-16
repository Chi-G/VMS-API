<?php
namespace App\Http\Controllers\FrontDesk;

use App\Http\Controllers\Controller;
use App\Models\Visitor;
use App\Models\Visit;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;

class GuestConfirmationController extends Controller
{
    public function index(Request $request)
    {
        $visitors = Visitor::with('visits.staff', 'visits.admin')->get();
        return response()->json($visitors);
    }

    public function show($id)
    {
        $visitor = Visitor::with('visits.staff', 'visits.admin')->findOrFail($id);
        return response()->json($visitor);
    }

    public function filter(Request $request)
    {
        $status = $request->query('status');
        $visitors = Visitor::whereHas('visits', function ($query) use ($status) {
            $query->where('status', $status);
        })->get();

        return response()->json($visitors);
    }

    public function search(Request $request)
    {
        $query = $request->query('query');
        $visitors = Visitor::where('name', 'LIKE', "%{$query}%")
            ->orWhere('email', 'LIKE', "%{$query}%")
            ->orWhere('contact_number', 'LIKE', "%{$query}%")
            ->get();

        return response()->json($visitors);
    }

    public function checkIn(Request $request, $id)
    {
        $visitor = Visitor::findOrFail($id);
        $visit = $visitor->visits()->latest()->first();
        $visit->check_in = now();
        $visit->save();

        return response()->json(['message' => 'Check-in successful', 'visitor' => $visitor]);
    }

    public function checkOut(Request $request, $id)
    {
        $visitor = Visitor::findOrFail($id);
        $visit = $visitor->visits()->latest()->first();
        $visit->check_out = now();
        $visit->save();

        return response()->json(['message' => 'Check-out successful', 'visitor' => $visitor]);
    }

    public function printTag($id)
    {
        $visitor = Visitor::with('visits.host')->findOrFail($id);
        $pdf = PDF::loadView('guests.visitorTag', compact('visitor'));
        return $pdf->download('visitor_tag.pdf');
    }
}
