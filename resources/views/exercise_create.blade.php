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

        <div id="multiple_choice" style="display: none;">
            <label for="option1">Opzione 1:</label>
            <input type="text" id="option1" name="options[]"><br><br>

            <label for="option2">Opzione 2:</label>
            <input type="text" id="option2" name="options[]"><br><br>

            <label for="option3">Opzione 3:</label>
            <input type="text" id="option3" name="options[]"><br><br>

            <label for="option4">Opzione 4:</label>
            <input type="text" id="option4" name="options[]"><br><br>

            <label for="correct_option">Opzione corretta:</label>
            <input type="text" id="correct_option" name="correct_option"><br><br>

            <label for="explanation">Spiegazione:</label>
            <input id="explanation" name="explanation"></input><br><br>
        </div>
        
        <div id="true_false" style="display: none;">
            <label for="correct_answer">Risposta corretta:</label>
            <select id="correct_answer" name="correct_option">
                <option value="Vero">Vero</option>
                <option value="Falso">Falso</option>
            </select><br><br>

            <label for="explanation">Spiegazione:</label>
            <textarea id="explanation" name="explanation"></textarea><br><br>
        </div>

        <button type="submit">Crea Esercizio</button>
    </form>
  
    <script>
        document.getElementById('type').addEventListener('change', function() {
            if (this.value === 'Risposta Multipla') {
                document.getElementById('multiple_choice').style.display = 'block';
            } else {
                document.getElementById('multiple_choice').style.display = 'none';
            }

            if (this.value === 'Vero o Falso') {
                document.getElementById('true_false').style.display = 'block';
            } else {
                document.getElementById('true_false').style.display = 'none';
            }
        });
    </script>
</body>
</html>
