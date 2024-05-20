<!DOCTYPE html>
<html>
<head>
    <title>Welcome Email</title>
</head>
<body>
    <h1>Hola, {{ $name }}!</h1>
    <p>Guarda el teu codi QR:</p>
    <img src="{{ $message->embed(storage_path('app/' . $qrPath)) }}" alt="QR Code" class="qr-code">
</body>
</html>
