<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Nuovo Esito Inserito') }}</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container p-4 rounded" style="background-color: #fff; box-shadow: 0.15rem 0.25rem 0 rgb(33 40 50 / 15%); border: 1px solid rgba(0,0,0,.125);">
        <div class="text-center">
            <h1>{{ __('Nuovo Esito Inserito nel Libretto') }}</h1>
            <p>{{ __('Ciao') }}</p>
            <p>{{ __('Abbiamo inserito un nuovo esito nel tuo libretto.') }}</p>
            <p>{{ __('Grazie,') }}<br>{{ __('Il tuo team scolastico') }}</p>
            <a href="{{ route('dashboard') }}" class="btn btn-primary">{{ __('Esplora Ora') }}</a>
        </div>
    </div>
</body>

</html>
