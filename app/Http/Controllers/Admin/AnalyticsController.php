<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Visit;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AnalyticsExport;
use Barryvdh\DomPDF\Facade\Pdf;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->query('filter', 'daily');

        $visits = Visit::select(DB::raw('DATE(check_in) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->when($filter === 'weekly', function ($query) {
                return $query->whereBetween('check_in', [now()->startOfWeek(), now()->endOfWeek()]);
            })
            ->when($filter === 'monthly', function ($query) {
                return $query->whereMonth('check_in', now()->month);
            })
            ->get();

        $mostVisitedStaff = Staff::withCount('visits')
            ->orderBy('visits_count', 'desc')
            ->take(5)
            ->get();

        $peakVisitorHours = Visit::select(DB::raw('HOUR(check_in) as hour'), DB::raw('count(*) as count'))
            ->groupBy('hour')
            ->get();

        $visitorTypes = Visit::select('visitor_type', DB::raw('count(*) as count'))
            ->groupBy('visitor_type')
            ->get();

        $purposeOfVisit = Visit::select('purpose', DB::raw('count(*) as count'))
            ->groupBy('purpose')
            ->get();

        $mostFrequentVisitors = Visit::select('visitor_name', DB::raw('count(*) as count'))
            ->groupBy('visitor_name')
            ->having('count', '>=', 25)
            ->get();

        return response()->json([
            'visits' => $visits,
            'most_visited_staff' => $mostVisitedStaff,
            'peak_visitor_hours' => $peakVisitorHours,
            'visitor_types' => $visitorTypes,
            'purpose_of_visit' => $purposeOfVisit,
            'most_frequent_visitors' => $mostFrequentVisitors,
        ]);
    }

    public function export(Request $request)
    {
        $format = $request->query('format', 'pdf');

        $analyticsData = [
            'visits' => Visit::select(DB::raw('DATE(check_in) as date'), DB::raw('count(*) as count'))
                ->groupBy('date')
                ->get(),

            'most_visited_staff' => Staff::withCount('visits')
                ->orderBy('visits_count', 'desc')
                ->take(5)
                ->get(),

            'peak_visitor_hours' => Visit::select(DB::raw('HOUR(check_in) as hour'), DB::raw('count(*) as count'))
                ->groupBy('hour')
                ->get(),

            'visitor_types' => Visit::select('visitor_type', DB::raw('count(*) as count'))
                ->groupBy('visitor_type')
                ->get(),

            'purpose_of_visit' => Visit::select('purpose', DB::raw('count(*) as count'))
                ->groupBy('purpose')
                ->get(),

            'most_frequent_visitors' => Visit::select('visitor_name', DB::raw('count(*) as count'))
                ->groupBy('visitor_name')
                ->having('count', '>=', 25)
                ->get(),
        ];

        if ($format === 'csv' || $format === 'excel') {
            return Excel::download(new AnalyticsExport($analyticsData), "analytics.$format");
        }

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('exports.analytics', ['data' => $analyticsData]);
            return $pdf->download('analytics.pdf');
        }

        return response()->json(['error' => 'Invalid format'], 400);
    }
}
