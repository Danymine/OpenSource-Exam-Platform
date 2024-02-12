<!DOCTYPE html>
<html>
<head>
    <title>Nuovo Esercizio</title>
</head>
<body>
    <h1>Nuovo Esercizio</h1>

    @if ($errors->any())
        <div>
            <strong>Errore!</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('exercises.store') }}">
        @csrf

        <label for="name">Nome:</label>
        <input type="text" id="name" name="name"><br><br>

        <label for="question">Domanda:</label>
        <textarea id="question" name="question"></textarea><br><br>

        <label for="score">Punteggio:</label>
        <input type="text" id="score" name="score"><br><br>

        <label for="difficulty">Difficoltà:</label>
        <select id="difficulty" name="difficulty">
            <option value="Bassa">Bassa</option>
            <option value="Media">Media</option>
            <option value="Alta">Alta</option>
        </select><br><br>

        <label for="subject">Materia:</label>
        <input type="text" id="subject" name="subject"><br><br>

        <label for="type">Tipo:</label>
        <select id="type" name="type">
            <option value="Risposta Aperta">Risposta Aperta</option>
            <option value="Risposta Multipla">Risposta Multipla</option>
            <option value="Vero o Falso">Vero o Falso</option>
        </select><br><br>

        <div id="multiple_choice_container" style="display: none;">
        </div>
        
        <div id="true_false_container" style="display: none;">
        </div>

        <button type="submit">Crea Esercizio</button>
    </form>

    <script src="/js/createExercise.js"></script>
</body>
</html>