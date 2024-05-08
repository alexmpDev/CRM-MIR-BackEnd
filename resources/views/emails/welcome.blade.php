<!DOCTYPE html>
<html>
<head>
    <title>Welcome Email</title>
</head>
<body>
    <h1>Welcome, {{ $name }}!</h1>
    <p>Here is your QR Code:</p>
    <img src="{{ asset('storage/' . $qrPath) }}">
</body>
</html>
