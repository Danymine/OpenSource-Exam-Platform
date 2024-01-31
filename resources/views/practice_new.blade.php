<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generated Practice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f9f9f9;
            color: #333;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        .practice-info, .exercise, .generated-key {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }

        h1, h2, h3 {
            margin-bottom: 10px;
        }

        h2 {
            margin-top: 20px;
        }

        .generated-key {
            display: flex;
            align-items: center;
        }

        .generated-key button {
            margin-left: 10px;
            padding: 8px 12px;
            border-radius: 4px;
            background-color: #3498db;
            color: #fff;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .generated-key button:hover {
            background-color: #2980b9;
        }

        .back-button {
            margin-top: 20px;
        }

        .back-button a button {
            padding: 10px 20px;
            background-color: #e74c3c;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .back-button a button:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
    <div class="container">
        @if(isset($newPractice))
            <div class="practice-info">
                <h1>Esercitazione Generata</h1>
                <p><strong>Titolo:</strong> {{ $newPractice->title }}</p>
                <p><strong>Descrizione:</strong> {{ $newPractice->description }}</p>
                <p><strong>Difficoltà:</strong> {{ $newPractice->difficulty }}</p>
                <p><strong>Materia:</strong> {{ $newPractice->subject }}</p>
                <p><strong>Punteggio Totale:</strong> {{ $newPractice->total_score }}</p>
                
                <!-- Nuove informazioni sul feedback e randomizzazione -->
                <p class="checkbox-info">
                    <input type="checkbox" id="feedbackEnabled" disabled {{ $newPractice->feedback_enabled ? 'checked' : '' }}>
                    <label for="feedbackEnabled">Feedback Automatico</label>
                </p>
                <p class="checkbox-info">
                    <input type="checkbox" id="randomizeQuestions" disabled {{ $newPractice->randomize_questions ? 'checked' : '' }}>
                    <label for="randomizeQuestions">Randomizzazione Domande</label>
                </p>

                <!-- Mostra la data di creazione -->
                <p><strong>Data di Creazione:</strong> {{ $newPractice->created_at }}</p>
                
                <!-- Mostra la data in cui si terrà l'esercitazione -->
                <p><strong>Data programmata:</strong> {{ \Carbon\Carbon::parse($newPractice->practice_date)->format('d-m-Y') }}</p>
            </div>

            <h2>Esercizi:</h2>
            @foreach($newPractice->exercises as $exercise)
                <div class="exercise">
                    <h3>{{ $exercise->name }}</h3>
                    <p><strong>Domanda:</strong> {{ $exercise->question }}</p>
                    <!-- Accedi al custom_score attraverso la relazione pivot -->
                    <p><strong>Punteggio:</strong> {{ $exercise->pivot->custom_score ?? '' }}</p>
                    <!-- Altri dettagli dell'esercizio -->
                </div>
            @endforeach

            <div class="generated-key">
                <strong>Chiave Generata:</strong>
                <span id="generatedKey">{{ $newPractice->key }}</span>
                <button onclick="copyKey()">Copia</button>
            </div>

            <!-- Bottone per tornare alla lista delle esercitazioni -->
            <div class="back-button">
                <a href="{{ route('practices.index', ['type' => $type]) }}">
                    <button>Torna alla lista delle esercitazioni</button>
                </a>
            </div>
        @endif

        <script>
            function copyKey() {
                var keyElement = document.getElementById('generatedKey');
                var tempTextArea = document.createElement('textarea');
                tempTextArea.value = keyElement.innerText;

                document.body.appendChild(tempTextArea);
                tempTextArea.select();
                document.execCommand('copy');
                document.body.removeChild(tempTextArea);

                alert('Chiave copiata negli appunti!');
            }
        </script>
    </div>
</body>
</html>
