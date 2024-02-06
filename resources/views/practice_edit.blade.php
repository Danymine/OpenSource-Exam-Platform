<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifica</title>
    <style>
    body {
        font-family: 'Arial', sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f4f4f4;
        color: #333;
    }

    .container {
        max-width: 95%;
        margin: 20px auto;
        background-color: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    h1, h2, h3 {
        color: #333;
    }

    form {
        overflow: hidden;
    }

    .column {
        float: left;
        box-sizing: border-box;
    }

    .column.left {
        width: 20%;
        margin-right: 20px;
    }

    .column.middle {
        width: 35%;
        margin-right: 20px;
        overflow-y: auto; 
        height: calc(100vh - 80px);
    }

    .column.right {
        width: 35%;
    }

    .column.right .exercise-list-wrapper {
        max-height: calc(100vh - 200px); 
        overflow-y: auto; 
        margin-top: 20px; 
    }

    label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }

    input, textarea, select {
        width: 100%;
        padding: 8px;
        margin-bottom: 10px;
        box-sizing: border-box;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 16px;
    }

    button {
        background-color: #4caf50;
        color: #fff;
        padding: 10px 15px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
    }

    button:hover {
        background-color: #45a049;
    }

    .alert {
        padding: 15px;
        background-color: #4caf50;
        color: #fff;
        margin-bottom: 15px;
        border-radius: 4px;
    }

    .section {
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    ul {
        list-style: none;
        padding: 0;
    }

    li.exercise {
        margin-bottom: 10px;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        overflow: hidden;
        background-color: #fff;
    }

    li.exercise div {
        overflow: hidden;
    }

    li.exercise div h3 {
        margin-top: 0;
        color: #333;
    }

    li.exercise div strong {
        display: block;
        margin-bottom: 5px;
    }

    li.exercise div input[type="checkbox"] {
        float: right;
    }
</style>
</head>
<body>
    <div class="container">
        <h1>Modifica {{ $type }}</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('practices.update', ['type' => $type, 'practice' => $practice->id]) }}">
            @csrf
            @method('PUT')

            <!-- Sezione per la modifica dei campi della pratica -->
            <div class="column left">
                <h2>Modifica Campi Pratica</h2>

                <label for="title">Titolo</label>
                <input type="text" name="title" value="{{ old('title', $practice->title) }}" required>

                <label for="description">Descrizione</label>
                <textarea name="description" required>{{ old('description', $practice->description) }}</textarea>

                <label for="difficulty">Difficoltà:</label>
                <select id="difficulty" name="difficulty">
                    <option value="{{ old('difficulty', $practice->difficulty) }}">{{ old('difficulty', $practice->difficulty) }}</option>
                    <option value="Bassa">Bassa</option>
                    <option value="Media">Media</option>
                    <option value="Alta">Alta</option>
                </select>

                <label for="subject">Materia:</label>
                <select id="subject" name="subject" >
                    <option value="{{ old('subject', $practice->subject) }}">{{ old('subject', $practice->subject) }}</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject }}">
                            {{ $subject }}
                        </option>
                    @endforeach
                </select>

                <label for="total_score">Punteggio Massimo:</label>
                <input type="number" id="total_score" name="total_score" value="{{ $practice->total_score }}" required>

                <label for="feedback">Feedback Automatico:</label>
                <input type="checkbox" id="feedback" name="feedback" {{ $practice->feedback ? 'checked' : '' }}>

                <label for="randomize">Randomizzazione delle Domande:</label>
                <input type="checkbox" id="randomize" name="randomize" {{ $practice->randomize ? 'checked' : '' }}>

                <label for="practice_date">Data dell'{{ $type }}:</label>
                <input type="date" id="practice_date" name="practice_date" value="{{ $practice->practice_date }}">
                
                <!-- pulsante di aggiornamento -->
                <button type="submit">Aggiorna</button>
            </div>

            <!-- Sezione per gli esercizi già presenti nella pratica -->
            <div class="column middle">
                <h2>Esercizi Presenti</h2>

                <ul id="selectedExerciseList">
                    @foreach ($practice->exercises as $exercise)
                        @if (in_array($exercise->id, $newPracticeExerciseIds))
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
                                    <!-- Aggiungi l'attributo "checked" se l'esercizio è presente -->
                                    <input type="checkbox" name="selected_exercises[]" value="{{ $exercise->id }}" checked>
                                </div>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>

            <!-- Sezione per la selezione degli esercizi disponibili -->
            <div class="column right">
                <div class="section">
                    <h2>Lista degli Esercizi Disponibili</h2>
                    
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
                            @foreach($exerciseType as $type)
                                <option value="{{ $type }}" {{ old('typeFilter') == $type ? 'selected' : '' }}>
                                    {{ $type }}
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

                <!-- Lista degli esercizi -->
                <div class="exercise-list-wrapper">
                    <ul id="exerciseList">
                        @foreach($allExercises as $exercise)
                            {{-- Aggiungi questa condizione per evitare che gli esercizi già presenti vengano visualizzati nella lista --}}
                            @if (!in_array($exercise->id, $newPracticeExerciseIds))
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
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </form>
    </div>

    <!-- script per i filtri e aggiornamento della lista di esercizi disponibili --> 
    <script>
        const subjectFilter = document.getElementById("subjectFilter");
        const difficultyFilter = document.getElementById("difficultyFilter");
        const typeFilter = document.getElementById("typeFilter");
        const minScoreInput = document.getElementById("minScore");
        const maxScoreInput = document.getElementById("maxScore");
        const exerciseList = document.getElementById("exerciseList");

        difficultyFilter.addEventListener("change", filterExercises);
        typeFilter.addEventListener("change", filterExercises);
        minScoreInput.addEventListener("input", filterExercises);
        maxScoreInput.addEventListener("input", filterExercises);

        const exercises = @json($allExercises);

        function filterExercises() {
            const subjectFilterValue = subjectFilter.value || "";
            const difficultyFilterValue = difficultyFilter.value || "";
            const typeFilterValue = typeFilter.value || "";
            const minScoreValue = minScoreInput.value || 0;
            const maxScoreValue = maxScoreInput.value || Infinity;

            const filteredExercises = exercises.filter(exercise => {
                return (
                    exercise.subject.toLowerCase().includes(subjectFilterValue.toLowerCase()) &&
                    (difficultyFilterValue === "" || exercise.difficulty.toLowerCase() === difficultyFilterValue.toLowerCase()) &&
                    (typeFilterValue === "" || exercise.type.toLowerCase() === typeFilterValue.toLowerCase()) &&
                    (exercise.score >= minScoreValue && exercise.score <= maxScoreValue)
                );
            });

            displayExercises(filteredExercises);
        }

        function displayExercises(exercises) {
            exerciseList.innerHTML = "";
            const middleColumnExerciseIds = Array.from(document.querySelectorAll("#selectedExerciseList [name='exercise_ids[]']"))
                .map(input => input.value);

            exercises.forEach(exercise => {
                if (!middleColumnExerciseIds.includes(exercise.id.toString())) {
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
                            <input type="checkbox" name="selected_exercises[]" value="${exercise.id}">
                        </div>
                    `;
                    exerciseList.appendChild(li);
                }
            });
        }

        // Applica i filtri iniziali
        filterExercises();

        // Aggiorna i filtri automaticamente
        subjectFilter.addEventListener("change", filterExercises);
        difficultyFilter.addEventListener("change", filterExercises);
        minScoreInput.addEventListener("input", filterExercises);
        maxScoreInput.addEventListener("input", filterExercises);
    </script>
</body>
</html>
