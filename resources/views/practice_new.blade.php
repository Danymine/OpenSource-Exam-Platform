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

        .practice-info {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }

        h1, h2 {
            margin-bottom: 10px;
        }

        h2 {
            margin-top: 20px;
        }

        .exercise {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 15px;
        }

        .exercise h3 {
            margin-bottom: 5px;
        }

        .generated-key {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            margin-top: 20px;
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
    </style>
</head>
<body>
    @if(isset($newPractice))
        <div class="practice-info">
            <h1>Esercitazione Generata</h1>
            <p><strong>Titolo:</strong> {{ $newPractice->title }}</p>
            <p><strong>Descrizione:</strong> {{ $newPractice->description }}</p>
            <p><strong>Difficoltà:</strong> {{ $newPractice->difficulty }}</p>
            <p><strong>Materia:</strong> {{ $newPractice->subject }}</p>
            <p><strong>Punteggio Totale:</strong> {{ $newPractice->total_score }}</p>
        </div>

        <h2>Esercizi:</h2>
        @foreach($newPractice->exercises as $exercise)
            <div class="exercise">
                <h3>{{ $exercise->name }}</h3>
                <p><strong>Domanda:</strong> {{ $exercise->question }}</p>
                <p><strong>Punteggio:</strong> {{ $exercise->score }}</p>
                <!-- Altri dettagli dell'esercizio -->
            </div>
        @endforeach

        @if(isset($key))
            <div class="generated-key">
                <strong>Chiave Generata:</strong>
                <span id="generatedKey">{{ $key }}</span>
                <button onclick="copyKey()">Copia</button>
            </div>
        @endif
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
</body>
</html>
