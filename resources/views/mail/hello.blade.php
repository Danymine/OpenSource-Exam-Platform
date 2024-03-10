<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Email di Benvenuto') }}</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <div class="card shadow">
            <div class="card-body">
                <h1 class="card-title">{{ __('Benvenuto/a, :name', ['name' => $name]) }}</h1>
                <p class="card-text">{{ __('Grazie per esserti unito a noi. Siamo entusiasti di averti con noi.') }}</p>
                <p class="card-text">{{ __('Inizia subito a esplorare tutto ciò che abbiamo da offrire.') }}</p>
                <a href="{{ route('dashboard') }}" class="btn btn-primary">{{ __('Esplora Ora') }}</a>
            </div>
        </div>
    </div>
</body>
</html>
