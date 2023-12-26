<!DOCTYPE html>
<html>
<head>
    <title>Crea Esercitazione</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
        }

        .section {
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .section h2 {
            margin-bottom: 15px;
            color: #555;
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input[type="number"],
        input[type="checkbox"],
        button {
            margin-bottom: 15px;
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        button {
            background-color: #4caf50;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #45a049;
        }

        .checkbox-container {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .checkbox-container input[type="checkbox"] {
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Crea Esercitazione</h1>

        <div class="section">
            <h2>Generazione Automatica</h2>
            <!-- Form per la generazione automatica -->
            <form action="{{ route('genera_esercitazione') }}" method="POST">
                @csrf
                <!-- Input per la generazione automatica -->
                <label for="numero_domande_auto">Numero di domande:</label>
                <input type="number" name="numero_domande_auto" id="numero_domande_auto" required>
                <label for="punteggio_massimo_auto">Punteggio massimo:</label>
                <input type="number" name="punteggio_massimo_auto" id="punteggio_massimo_auto" required>
                <!-- Nuovo container per allineare il checkbox -->
                <div class="checkbox-container">
                    <input type="checkbox" name="randomizzazione_auto" id="randomizzazione_auto">
                    <label for="randomizzazione_auto">Randomizzazione</label>
                </div>
                <!-- Pulsante di submit -->
                <button type="submit">Genera Automaticamente</button>
            </form>
        </div>

        <div class="section">
            <h2>Generazione Manuale</h2>
            <!-- Form per la generazione manuale -->
            <form action="{{ route('genera_esercitazione') }}" method="POST">
                @csrf
                <!-- Input per la generazione manuale -->
                <label for="lista_esercizi"></label>
                <div class="button-container">
                    <!-- Bottone "Seleziona" -->
                    <button type="button">Seleziona esercizi</button>
                </div>
                <!-- Pulsante di submit -->
                <button type="submit">Genera Manualmente</button>
            </form>
        </div>
    </div>
</body>
</html>
