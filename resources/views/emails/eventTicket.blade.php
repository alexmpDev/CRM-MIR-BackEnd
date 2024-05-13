<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrada per a l'event {{ $eventName }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            padding: 20px;
        }
        .header {
            background: #f4f4f4;
            padding: 10px 20px;
            text-align: center;
        }
        .content {
            margin-top: 20px;
        }
        .footer {
            margin-top: 20px;
            padding: 10px 20px;
            text-align: center;
            background: #eee;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Benvingut a l'event!</h1>
        </div>
        <div class="content">
            <p>Hola {{ $studentName }},</p>
            <p>Estem encantats de tenir-te amb nosaltres en el nostre pròxim esdeveniment:</p>
            <p><strong>Esdeveniment:</strong> {{ $eventName }}</p>
            <p><strong>Data:</strong> {{ $eventDate }}</p>
            <p>Adjuntem el teu codi QR que necessitaràs presentar a l'entrada.</p>
            <p>Assegura't de portar aquest codi amb tu, ja que és necessari per a la teva entrada a l'esdeveniment.</p>
            <!-- Incluye la imagen del código QR directamente en el correo -->
            <img src="{{ $message->embed(storage_path('app/' . $qrPath)) }}" alt="QR Code" class="qr-code">
        </div>
        <div class="footer">
            <p>Gràcies per la teva participació!</p>
        </div>
    </div>
</body>
</html>
