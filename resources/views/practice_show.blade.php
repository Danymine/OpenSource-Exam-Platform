<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esercitazione: {{ $practice->title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
            color: #333;
        }

        .practice-details {
            background-color: #fff;
            border-radius: 4px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333;
            margin-bottom: 10px;
        }

        p {
            margin-bottom: 8px;
        }
    </style>
</head>
<body>
    <div class="practice-details">
        <h1>{{ $practice->title }}</h1>
        <p><strong>Descrizione:</strong> {{ $practice->description }}</p>
        <p><strong>Difficoltà:</strong> {{ $practice->difficulty }}</p>
        <p><strong>Materia:</strong> {{ $practice->subject }}</p>
        <p><strong>Punteggio Totale:</strong> {{ $practice->total_score }}</p>
        @if(isset($key))
            <p><strong>Chiave Generata:</strong> {{ $key }}</p>
        @endif
    </div>
</body>
</html>
