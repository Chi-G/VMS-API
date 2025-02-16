<!DOCTYPE html>
<html>
<head>
    <title>Analytics Report</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; }
    </style>
</head>
<body>
    <h2>Analytics Report</h2>

    <h3>Visits</h3>
    <table>
        <tr><th>Date</th><th>Visit Count</th></tr>
        @foreach ($data['visits'] as $visit)
            <tr><td>{{ $visit->date }}</td><td>{{ $visit->count }}</td></tr>
        @endforeach
    </table>

    <h3>Most Visited Staff</h3>
    <table>
        <tr><th>Staff Name</th><th>Visit Count</th></tr>
        @foreach ($data['most_visited_staff'] as $staff)
            <tr><td>{{ $staff->name }}</td><td>{{ $staff->visits_count }}</td></tr>
        @endforeach
    </table>

    <h3>Peak Visitor Hours</h3>
    <table>
        <tr><th>Hour</th><th>Count</th></tr>
        @foreach ($data['peak_visitor_hours'] as $hour)
            <tr><td>{{ $hour->hour }}</td><td>{{ $hour->count }}</td></tr>
        @endforeach
    </table>

</body>
</html>
