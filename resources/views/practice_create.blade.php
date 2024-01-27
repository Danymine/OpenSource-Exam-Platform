<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Practice Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }

        .practice-form {
            max-width: none;
            margin: 0 auto;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="number"],
        input[type="submit"] {
            width: calc(100% - 18px);
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #4caf50;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <form action="{{ route('practices.new') }}" method="GET" class="practice-form">
        @csrf
        <div class="form-group">
            <label for="title">Titolo dell'esercitazione:</label>
            <input type="text" id="title" name="title">
        </div>

        <div class="form-group">
            <label for="description">Descrizione dell'esercitazione:</label>
            <input type="text" id="description" name="description">
        </div>

        <div class="form-group">
            <label for="difficulty">Difficoltà:</label>
            <input type="text" id="difficulty" name="difficulty">
        </div>

        <div class="form-group">
            <label for="subject">Materia:</label>
            <input type="text" id="subject" name="subject">
        </div>

        <div class="form-group">
            <label for="max_questions">Numero Massimo di Domande:</label>
            <input type="number" id="max_questions" name="max_questions">
        </div>

        <div class="form-group">
            <label for="max_score">Punteggio Massimo:</label>
            <input type="number" id="max_score" name="max_score">
        </div>
        
        <div class="form-group">
            <label for="feedback">Feedback Automatico:</label>
            <input type="checkbox" id="feedback" name="feedback">
        </div>

        <div class="form-group">
            <label for="randomize">Randomizzazione delle Domande:</label>
            <input type="checkbox" id="randomize" name="randomize">
        </div>  
        
        <div class="form-group">
            <label for="practice_date">Data dell'Esame:</label>
            <input type="date" id="practice_date" name="practice_date">
        </div>
        <input type="submit" value="Genera Pratica">
    </form>

</body>
</html>
