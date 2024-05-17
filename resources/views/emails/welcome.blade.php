<!DOCTYPE html>
<html>
<head>
    <title>Welcome Email</title>
</head>
<body>
    <h1>Hola, {{ $name }}!</h1>
    <p>Guarda el teu codi QR:</p>
    <img src="{{ asset('storage/' . $qrPath) }}">
</body>
</html>
