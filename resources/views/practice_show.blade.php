<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ ucfirst($type) }}: {{ $practice->title }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f9f9f9;
            color: #333;
            display: flex;
            justify-content: center;
        }

        .container {
            max-width: none;
            width: 100%;
        }
        
        .practice-details,
        .exercise,
        .key-section,
        .back-button {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }

        h1,
        h2,
        h3 {
            margin-bottom: 10px;
        }

        h2 {
            margin-top: 20px;
        }

        .key-section {
            display: flex;
            align-items: center;
        }

        .key-section button {
            margin-left: 10px;
            padding: 8px 12px;
            border-radius: 4px;
            background-color: #3498db;
            color: #fff;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .key-section button:hover {
            background-color: #2980b9;
        }

        .back-button {
            text-align: center;
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
        <div class="practice-details">
            <h1>{{ $practice->title }}</h1>
            <p><strong>Descrizione:</strong> {{ $practice->description }}</p>
            <p><strong>Difficoltà:</strong> {{ $practice->difficulty }}</p>
            <p><strong>Materia:</strong> {{ $practice->subject }}</p>
            <p><strong>Punteggio Totale:</strong> {{ $practice->total_score }}</p>
        </div>

        <h2>Esercizi:</h2>
        @foreach($practice->exercises as $exercise)
        <div class="exercise">
            <h3>{{ $exercise->name }}</h3>
            <p><strong>Domanda:</strong> {{ $exercise->question }}</p>
            <p><strong>Punteggio:</strong> {{ $exercise->pivot->custom_score ?? '' }}</p>
            <!-- Altri dettagli dell'esercizio -->
        </div>
        @endforeach

        <div class="key-section">
            <strong>Chiave:</strong>
            <span id="generatedKey">{{ $practice->key }}</span>
            <button onclick="copyKey()">Copia</button>
        </div>

        <!-- Bottone per tornare alla lista delle esercitazioni -->
        <div class="back-button">
            <a href="{{ route('practices.index', ['type' => $type]) }}">
                <button>Torna alla lista delle {{ $type }}</button>
            </a>
        </div>
    </div>

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
