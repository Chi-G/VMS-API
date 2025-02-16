<!DOCTYPE html>
<html>
<head>
    <title>Visit Confirmation</title>
</head>
<body>
    <h1>Visit Confirmation</h1>
    <p>Dear {{ $name }},</p>
    <p>Your visit is confirmed with the following details:</p>
    <ul>
        <li>Date: {{ $visit_date }}</li>
        <li>Host: {{ $host_name }}</li>
        <li>Purpose: {{ $purpose }}</li>
    </ul>
    <p>Please use the following QR code for check-in:</p>
    <img src="{{ $qr_code }}" alt="QR Code">
    <p>Thank you,</p>
    <p>Sydani Groups>
</body>
</html>
