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
            max-width: 800px;
            margin: 0 auto;
        }

        .section {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            overflow: hidden;
        }

        .form-group {
            padding: 20px;
            box-sizing: border-box;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="number"],
        input[type="date"],
        select {
            width: calc(100% - 16px);
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        input[type="checkbox"] {
            margin-right: 8px;
        }

        input[type="submit"] {
            width: calc(100% - 40px);
            padding: 12px;
            background-color: #4caf50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 20px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        li {
            padding: 20px;
            box-sizing: border-box;
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .score-filter {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .score-filter label {
            margin-right: 8px;
        }

        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    <form action="{{ route('createExerciseSet') }}" method="POST" class="practice-form">
        @csrf

        <div class="section">
            <h2>Caratteristiche dell'esercitazione</h2>
            <div class="form-group">
                <label for="title">Titolo dell'esercitazione:</label>
                <input type="text" id="title" name="title" required>
            </div>

            <div class="form-group">
                <label for="description">Descrizione dell'esercitazione:</label>
                <input type="text" id="description" name="description" required>
            </div>

            <div class="form-group">
                <label for="max_score">Punteggio Massimo:</label>
                <input type="number" id="max_score" name="max_score" required>
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
        </div>

        <div class="section">
            <h2>Filtri</h2>
            <div class="form-group">
                <label for="subjectFilter">Filtra per Materia:</label>
                <select id="subjectFilter" name="subjectFilter" onchange="applyFilters()">
                    <option value="">Tutte le Materie</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject }}">{{ $subject }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="difficultyFilter">Filtra per Difficoltà:</label>
                <select id="difficultyFilter" name="difficultyFilter" onchange="applyFilters()">
                    <option value="">Tutte le Difficoltà</option>
                    <option value="Bassa">Bassa</option>
                    <option value="Media">Media</option>
                    <option value="Alta">Alta</option>
                </select>
            </div>

            <div class="form-group score-filter">
                <label for="minScore">Punteggio Minimo:</label>
                <input type="number" id="minScore" name="minScore" min="0" onchange="applyFilters()">
                <label for="maxScore">Punteggio Massimo:</label>
                <input type="number" id="maxScore" name="maxScore" min="0" onchange="applyFilters()">
            </div>

            <div class="form-group">
                <button id="applyFiltersButton" onclick="applyFilters(true)">Applica Filtri</button>
            </div>
        </div>

        <div class="section">
            <h2>Lista degli Esercizi</h2>
            <ul id="exerciseList">
                @foreach($exercises as $exercise)
                    <li class="exercise" data-subject="{{ $exercise->subject }}" data-difficulty="{{ $exercise->difficulty }}" data-score="{{ $exercise->score }}">
                        <div>
                            <h3>{{ $exercise->name }}</h3>
                            <strong>Domanda:</strong> {{ $exercise->question }}<br>
                            <strong>Materia:</strong> {{ $exercise->subject }}<br>
                            <strong>Difficoltà:</strong> {{ $exercise->difficulty }}<br>
                            <strong>Punteggio:</strong> {{ $exercise->score }}<br>
                            <input type="hidden" name="exercise_ids[]" value="{{ $exercise->id }}">
                        </div>
                        <div>
                            <input type="checkbox" name="selected_exercises[]" value="{{ $exercise->id }}">
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </form>

    <script>
        window.onload = function() {
            var exerciseListItems = document.querySelectorAll('.exercise');

            // Aggiungi event listener ai filtri
            document.getElementById('subjectFilter').addEventListener('change', applyFilters);
            document.getElementById('difficultyFilter').addEventListener('change', applyFilters);
            document.getElementById('minScore').addEventListener('input', applyFilters);
            document.getElementById('maxScore').addEventListener('input', applyFilters);

            // Chiama la funzione filterExercises() per nascondere gli esercizi che non soddisfano i filtri
            filterExercises();
        }

        function filterExercises() {
            var subjectFilter = document.getElementById('subjectFilter').value;
            var difficultyFilter = document.getElementById('difficultyFilter').value;
            var minScore = document.getElementById('minScore').value;
            var maxScore = document.getElementById('maxScore').value;

            exerciseListItems.forEach(function(exercise) {
                var subject = exercise.getAttribute('data-subject');
                var difficulty = exercise.getAttribute('data-difficulty');
                var score = parseFloat(exercise.getAttribute('data-score'));

                var isVisible = (!subjectFilter || subject === subjectFilter) &&
                                (!difficultyFilter || difficulty === difficultyFilter) &&
                                (isNaN(minScore) || score >= parseFloat(minScore)) &&
                                (isNaN(maxScore) || score <= parseFloat(maxScore));

                exercise.style.display = isVisible ? 'flex' : 'none';
            });

            // Mostra il pulsante solo se ci sono esercizi visibili
            var submitButton = document.getElementById('submitButton');
            submitButton.style.display = Array.from(exerciseListItems).some(exercise => exercise.style.display === 'flex') ? 'block' : 'none';
        }

        var applyFiltersFlag = false;

        function applyFilters(flag) {
            applyFiltersFlag = flag || false;
            filterExercises();

            // Mostra il pulsante "Applica Filtri" solo se ci sono filtri impostati
            var applyFiltersButton = document.getElementById('applyFiltersButton');
            applyFiltersButton.style.display = document.getElementById('subjectFilter').value !== '' ||
                                            document.getElementById('difficultyFilter').value !== '' ||
                                            document.getElementById('minScore').value !== '' ||
                                            document.getElementById('maxScore').value !== '';

            if (applyFiltersFlag) {
                // Impedisci l'invio del form
                event.preventDefault();
            }
        }
    </script>
</body>
</html>