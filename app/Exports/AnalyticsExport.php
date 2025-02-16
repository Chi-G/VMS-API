<?php

namespace App\Exports;

use App\Models\Visit;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AnalyticsExport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return collect([
            ['Date', 'Visit Count'],
            ...$this->data['visits']->map(fn ($visit) => [$visit->date, $visit->count]),

            ['Most Visited Staff', 'Visit Count'],
            ...$this->data['most_visited_staff']->map(fn ($staff) => [$staff->name, $staff->visits_count]),

            ['Peak Visitor Hours', 'Count'],
            ...$this->data['peak_visitor_hours']->map(fn ($hour) => [$hour->hour, $hour->count]),

            ['Visitor Types', 'Count'],
            ...$this->data['visitor_types']->map(fn ($type) => [$type->visitor_type, $type->count]),

            ['Purpose of Visit', 'Count'],
            ...$this->data['purpose_of_visit']->map(fn ($purpose) => [$purpose->purpose, $purpose->count]),

            ['Most Frequent Visitors', 'Visit Count'],
            ...$this->data['most_frequent_visitors']->map(fn ($visitor) => [$visitor->visitor_name, $visitor->count]),
        ]);
    }

    public function headings(): array
    {
        return ['Category', 'Data'];
    }
}
