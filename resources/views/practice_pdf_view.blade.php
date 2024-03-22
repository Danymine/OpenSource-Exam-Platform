<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dettagli Pratica</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .exercise {
            margin-bottom: 20px;
        }

        .exercise h4 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }

        .exercise p {
            margin: 0;
        }

        hr {
            margin-top: 30px;
            margin-bottom: 30px;
            border: 0;
            border-top: 1px solid #ccc;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1> {{ $practice->title }} </h1>
        <div class="practice-details">
            <p><strong>Descrizione:</strong> {{ $practice->description }}</p>
            <p><strong>Difficoltà:</strong> {{ $practice->difficulty }}</p>
            <p><strong>Materia:</strong> {{ $practice->subject }}</p>
            <p><strong>Punteggio Totale:</strong> {{ $practice->total_score }}</p>
            <p><strong>Feedback Automatico:</strong> {{ $practice->feedback_enabled ? 'Sì' : 'No' }}</p>
            <p><strong>Randomizzazione Domande:</strong> {{ $practice->randomize_questions ? 'Sì' : 'No' }}</p>
            <p><strong>Data di Creazione:</strong> {{ $practice->created_at }}</p>
            <p><strong>Data programmata:</strong> {{ \Carbon\Carbon::parse($practice->practice_date)->format('d-m-Y') }}</p>
        </div>

        <hr>

        <h2>Esercizi:</h2>

        @foreach($practice->exercises as $exercise)
            <div class="exercise">
                <h4>{{ $exercise->name }}</h4>
                <p><strong>Domanda:</strong> {{ $exercise->question }}</p>
                <p><strong>Punteggio:</strong> {{ $exercise->score }}</p>
            </div>
        @endforeach
    </div>
</body>
</html>