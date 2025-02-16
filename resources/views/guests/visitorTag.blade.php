<!DOCTYPE html>
<html>
<head>
    <title>Visitor Tag</title>
</head>
<body>
    <h1>Visitor Tag</h1>
    <p>Name: {{ $visitor->name }}</p>
    <p>Email: {{ $visitor->email }}</p>
    <p>Contact Number: {{ $visitor->contact_number }}</p>
    <p>Address: {{ $visitor->address }}</p>
    <p>Check-in Time: {{ $visitor->check_in }}</p>
    <p>Visit Date: {{ $visitor->visits->first()->visit_date }}</p>
    <p>Host Name: {{ $visitor->visits->first()->host->name }}</p>
    <p>Contact Info: {{ $visitor->visits->first()->host->contact_info }}</p>
    <p>Special Requirements: {{ $visitor->visits->first()->special_requirements }}</p>
</body>
</html>
