<?php
namespace App\Http\Controllers\FrontDesk;

use App\Http\Controllers\Controller;
use App\Models\Visitor;
use App\Models\Visit;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->query('month');
        $visitorType = $request->query('visitor_type', 'all');

        $query = Visit::with('visitor', 'host');

        if ($month) {
            $query->whereMonth('visit_date', $month);
        }

        if ($visitorType !== 'all') {
            $query->where('visitor_type', $visitorType);
        }

        $visits = $query->get()->groupBy(function ($visit) {
            return $visit->visit_date->format('Y-m-d');
        });

        return view('frontdesk.history.index', compact('visits'));
    }
}
