<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code - {{ $resource->name }}</title>
    <title>QR Code - {{ $resource->name }}</title>
    @vite(['resources/css/resources/print.css', 'resources/js/resources/print.js'])
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

    <div class="footer">DataHub Asset Tracking</div>
</body>

</html>