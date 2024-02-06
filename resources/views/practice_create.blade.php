<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Practice Form</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .header {
            background-color: #3498db;
            color: white;
            text-align: center;
            padding: 20px;
            border-bottom: 2px solid #2980b9;
        }

        .footer {
            background-color: #3498db;
            color: white;
            text-align: center;
            padding: 10px;
        }

        .practice-form {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        .practice-form h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }

        input[type="text"],
        input[type="number"],
        input[type="date"],
        select,
        .checkbox {
            width: calc(100% - 24px); /* 24px è il totale dei padding e dei bordi */
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="number"]:focus,
        input[type="date"]:focus,
        select:focus,
        .checkbox:focus {
            border-color: #3498db;
        }

        input[type="submit"] {
            background-color: #3498db;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
            padding: 12px 20px;
            border-radius: 5px;
            width: 100%;
        }

        input[type="submit"]:hover {
            background-color: #2980b9;
        }

        .checkbox {
            margin-left: 5px;
            vertical-align: middle;
        }

        /* Alert styles */
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }

        .alert-danger ul {
            margin-bottom: 0;
            padding-left: 20px;
        }

        .alert-danger li {
            list-style-type: disc;
            margin-bottom: 5px;
        }

        .alert-danger .alert-symbol {
            margin-right: 10px;
            font-size: 18px;
            color: #721c24;
        }
    </style>
</head>
<body>

    @if($errors->any())
        <div class="alert alert-danger">
            <span class="alert-symbol">&#9888;</span>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('practices.new', ['type' => $type]) }}" method="POST" class="practice-form">
        @csrf

        <h2>Genera Pratica</h2>

        <div class="form-group">
            <label for="title">Titolo:</label>
            <input type="text" id="title" name="title" value="{{ old('title') }}" required>
        </div>

        <div class="form-group">
            <label for="description">Descrizione:</label>
            <textarea id="description" name="description" rows="4" style="width: calc(100% - 24px); padding: 12px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; transition: border-color 0.3s ease;" required>{{ old('description') }}</textarea>
        </div>

        <div class="form-group">
            <label for="difficulty">Difficoltà:</label>
            <select id="difficulty" name="difficulty">
                <option value="">Tutte le Difficoltà</option>
                <option value="Bassa">Bassa</option>
                <option value="Media">Media</option>
                <option value="Alta">Alta</option>
            </select>
        </div>

        <div class="form-group">
            <label for="subject">Materia:</label>
            <select id="subject" name="subject">
                <option value="">Tutte le Materie</option>
                @foreach($subjects as $subject)
                    <option value="{{ $subject }}">
                        {{ $subject }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="max_questions">Numero Massimo di Domande:</label>
            <input type="number" id="max_questions" name="max_questions" value="{{ old('max_questions') }}" required>
        </div>

        <div class="form-group">
            <label for="max_score">Punteggio Massimo:</label>
            <select id="max_score" name="max_score" required>
                <option value="10">10</option>
                <option value="30">30</option>
                <option value="100">100</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="feedback">Feedback Automatico:</label>
            <input type="checkbox" id="feedback" name="feedback" {{ old('feedback') ? 'checked' : '' }}>
        </div>

        <div class="form-group">
            <label for="randomize">Randomizzazione delle Domande:</label>
            <input type="checkbox" id="randomize" name="randomize" {{ old('randomize') ? 'checked' : '' }}>
        </div>  
        
        <div class="form-group">
            <label for="practice_date">Data dell'Esame:</label>
            <input type="date" id="practice_date" name="practice_date" value="{{ old('practice_date') }}" required>
        </div>
        <input type="submit" value="Genera Pratica">
    </form>
</body>
</html>
