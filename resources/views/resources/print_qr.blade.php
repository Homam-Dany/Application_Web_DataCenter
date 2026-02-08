<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code - {{ $resource->name }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            background-color: white;
        }

        .qr-card {
            border: 2px solid #000;
            padding: 40px;
            text-align: center;
            border-radius: 10px;
        }

        h1 {
            margin-bottom: 10px;
            font-size: 24px;
        }

        p {
            margin-bottom: 30px;
            color: #555;
        }

        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #999;
        }

        @media print {
            .no-print {
                display: none;
            }

            body {
                background: white;
            }

            .qr-card {
                border: none;
            }
        }
    </style>
</head>

<body>
    <div class="qr-card">
        <h1>{{ $resource->name }}</h1>
        <p>{{ strtoupper($resource->type) }} • ID: {{ $resource->id }}</p>

        <div style="margin: 20px 0;">
            {!! QrCode::size(250)->generate(route('resources.show', $resource->id)) !!}
        </div>

        <p style="font-size: 14px;">Scannez pour accéder à la fiche technique</p>
    </div>

    <div class="footer">DC-Manager Asset Tracking</div>

    <script>
        window.onload = function () {
            window.print();
        }
    </script>
</body>

</html>