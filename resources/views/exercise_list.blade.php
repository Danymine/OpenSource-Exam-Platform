<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pratica</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        h2 {
            color: #333;
        }

        .container {
            max-width: 1600px;
            margin: 20px auto;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        .practice-form {
            display: flex;
        }

        .side-column {
            width: 400px;
            padding: 20px;
            box-sizing: border-box;
            background-color: #f7f7f7;
        }

        .list-section {
            width: 1200px; 
            padding: 20px;
            box-sizing: border-box;
            height: calc(100vh - 40px);
            overflow-y: auto; 
        }

        .section {
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }

        .form-group input[type="checkbox"] {
            margin-top: 5px;
        }

        #exerciseList {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .exercise {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 5px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            height: 100%; /* Fissa l'altezza dei riquadri */
            width:100%;
        }

        .exercise div {
            flex: 1;
        }

        .exercise h3 {
            color: #333;
            margin-top: 0;
        }

        input[type="checkbox"] {
            margin-top: 8px;
        }

        .list-section .exercise div:last-child {
            text-align: right;
        }

        .list-section .exercise div:last-child input[type="checkbox"] {
            margin-left: 10px;
        }

        .side-column button[type="submit"] {
            margin-top: 20px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            padding: 12px;
            font-size: 16px;
        }

        .side-column button[type="submit"]:hover {
            background-color: #45a049;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
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

    <div class="container">
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

    <form action="{{ route('createExerciseSet', ['type' => $type]) }}" method="POST" class="practice-form">
        @csrf

        <div class="practice-form">
            <div class="side-column">
                <div class="section">
                    <h2>Caratteristiche dell'{{ $type }}</h2>
                    <div class="form-group">
                        <label for="title">Titolo dell'{{ $type }}:</label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="description">Descrizione dell'{{ $type }}:</label>
                        <textarea id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
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
                        <label for="practice_date">Data dell'{{ $type }}:</label>
                        <input type="date" id="practice_date" name="practice_date" value="{{ old('practice_date') }}">
                    </div>
                </div>

                <div class="section">
                    <h2>Filtri</h2>
                    <div class="form-group">
                        <label for="subjectFilter">Filtra per Materia:</label>
                        <select id="subjectFilter" name="subjectFilter">
                            <option value="">Tutte le Materie</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject }}" {{ old('subjectFilter') == $subject ? 'selected' : '' }}>
                                    {{ $subject }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="difficultyFilter">Filtra per Difficoltà:</label>
                        <select id="difficultyFilter" name="difficultyFilter">
                            <option value="">Tutte le Difficoltà</option>
                            <option value="Bassa" {{ old('difficultyFilter') == 'Bassa' ? 'selected' : '' }}>Bassa</option>
                            <option value="Media" {{ old('difficultyFilter') == 'Media' ? 'selected' : '' }}>Media</option>
                            <option value="Alta" {{ old('difficultyFilter') == 'Alta' ? 'selected' : '' }}>Alta</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="typeFilter">Filtra per Tipo di Esercizio:</label>
                        <select id="typeFilter" name="typeFilter">
                            <option value="">Tutti i Tipi</option>
                            @foreach($exerciseTypes as $exerciseType)
                                <option value="{{ $exerciseType }}" {{ old('typeFilter') == $exerciseType ? 'selected' : '' }}>
                                    {{ $exerciseType }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group score-filter">
                        <label for="minScore">Punteggio Minimo:</label>
                        <input type="number" id="minScore" name="minScore" min="0" value="{{ old('minScore') }}">
                        <label for="maxScore">Punteggio Massimo:</label>
                        <input type="number" id="maxScore" name="maxScore" min="0" value="{{ old('maxScore') }}">
                    </div>
                </div>

                <!-- Bottone di invio del form -->
                <button type="submit" style="width: 100%;">Crea {{ $type }}</button>
            </div>

            <div class="list-section">
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
            </div>
        </div>
    </form>

    <script>
        
        let exercises = @json($exercises);
        let selectedExercises = [];

        document.addEventListener("DOMContentLoaded", function() {

            // Seleziona tutte le checkbox degli esercizi
            const checkboxes = document.querySelectorAll('input[name="selected_exercises[]"]');

            checkboxes.forEach(function(checkbox) {

                checkbox.addEventListener("click", function() {

                    const exerciseId = this.value; // Ottieni l'id dell'esercizio

                    if (this.checked) {

                        // Se la checkbox è stata selezionata, aggiungi l'id all'array
                        selectedExercises.push(exerciseId);
                    } 
                    else {

                        // Se la checkbox è stata deselezionata, rimuovi l'id dall'array
                        const index = selectedExercises.indexOf(exerciseId);
                        if (index !== -1) {

                            selectedExercises.splice(index, 1);
                        }
                    }

                });
            });
        });


        let subjectFilter = document.getElementById("subjectFilter");
        let difficultyFilter = document.getElementById("difficultyFilter");
        let typeFilter = document.getElementById("typeFilter");
        let minScoreInput = document.getElementById("minScore");
        let maxScoreInput = document.getElementById("maxScore");
        let exerciseList = document.getElementById("exerciseList");

        difficultyFilter.addEventListener("change", filterExercises);
        minScoreInput.addEventListener("input", filterExercises);
        maxScoreInput.addEventListener("input", filterExercises);
        typeFilter.addEventListener("change", filterExercises);

        function filterExercises() {

            let subjectFilterValue = subjectFilter.value || "";
            let difficultyFilterValue = difficultyFilter.value || "";
            let typeFilterValue = typeFilter.value || "";
            let minScoreValue = minScoreInput.value || 0;
            let maxScoreValue = maxScoreInput.value || Infinity;

            let filteredExercises = exercises.filter(exercise => {

               if (
                    // Controlla se il soggetto dell'esercizio contiene il valore del filtro del soggetto, ignorando maiuscole e minuscole
                    exercise.subject.toLowerCase().includes(subjectFilterValue.toLowerCase()) &&
                    
                    // Controlla se la difficoltà dell'esercizio corrisponde al valore del filtro di difficoltà, ignorando maiuscole e minuscole
                    (difficultyFilterValue === "" || exercise.difficulty.toLowerCase() === difficultyFilterValue.toLowerCase()) &&
                    
                    // Controlla se il tipo dell'esercizio corrisponde al valore del filtro del tipo, ignorando maiuscole e minuscole
                    (typeFilterValue === "" || exercise.type.toLowerCase() === typeFilterValue.toLowerCase()) &&
                    
                    // Controlla se il punteggio dell'esercizio rientra nell'intervallo specificato dal punteggio minimo e massimo
                    (exercise.score >= minScoreValue && exercise.score <= maxScoreValue)
                ) {

                    // Se tutti i criteri sono soddisfatti, l'esercizio viene incluso nei risultati filtrati
                    return true;
                } else {

                    // Se uno qualsiasi dei criteri non è soddisfatto, l'esercizio viene escluso dai risultati filtrati
                    return false;
                }
            });

            displayExercises(filteredExercises);
        }

        function displayExercises(exercises) {
            
            exerciseList.innerHTML = "";
            exercises.forEach(exercise => {
                const li = document.createElement("li");
                li.classList.add("exercise");
                li.dataset.subject = exercise.subject;
                li.dataset.difficulty = exercise.difficulty;
                li.dataset.score = exercise.score;
                li.innerHTML = `
                    <div>
                        <h3>${exercise.name}</h3>
                        <strong>Domanda:</strong> ${exercise.question}<br>
                        <strong>Materia:</strong> ${exercise.subject}<br>
                        <strong>Difficoltà:</strong> ${exercise.difficulty}<br>
                        <strong>Punteggio:</strong> ${exercise.score}<br>
                        <input type="hidden" name="exercise_ids[]" value="${exercise.id}">
                    </div>
                    <div>
                        <input type="checkbox" name="selected_exercises[]" value="${exercise.id}" ${selectedExerciseIds.includes(String(exercise.id)) ? 'checked' : ''}>
                    </div>
                `;
                exerciseList.appendChild(li);
            });
        }

        // Aggiorna i filtri automaticamente
        subjectFilter.addEventListener("change", filterExercises);
        difficultyFilter.addEventListener("change", filterExercises);
        minScoreInput.addEventListener("input", filterExercises);
        maxScoreInput.addEventListener("input", filterExercises);
    </script>

</body>
</html>