<!DOCTYPE html>
<html>
<head>
    <title>Modifica Esercizio</title>
</head>
<body>
    <h1>Modifica Esercizio</h1>

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

    <form method="POST" action="{{ route('exercises.update', ['exercise' => $exercise->id]) }}">
        @csrf
        @method('PUT')

        <label for="name">Nome:</label>
        <input type="text" id="name" name="name" value="{{ $exercise->name }}"><br><br>

        <label for="question">Domanda:</label>
        <textarea id="question" name="question">{{ $exercise->question }}</textarea><br><br>

        <label for="score">Punteggio:</label>
        <input type="text" id="score" name="score" value="{{ $exercise->score }}"><br><br>

        <label for="difficulty">Difficoltà:</label>
        <input type="text" id="difficulty" name="difficulty" value="{{ $exercise->difficulty }}"><br><br>

        <label for="subject">Materia:</label>
        <input type="text" id="subject" name="subject" value="{{ $exercise->subject }}"><br><br>

        <label for="type">Tipo:</label>
        <select id="type" name="type">
            <option value="Risposta Aperta" @if($exercise->type === 'Risposta Aperta') selected @endif>Risposta Aperta</option>
            <option value="Risposta Multipla" @if($exercise->type === 'Risposta Multipla') selected @endif>Risposta Multipla</option>
        </select><br><br>

        <div id="multiple_choice" @if($exercise->type === 'Risposta Multipla') style="display: block;" @else style="display: none;" @endif>
            <label for="option1">Opzione 1:</label>
            <input type="text" id="option1" name="options[]" value="{{ $exercise->options[0] }}"><br><br>

            <label for="option2">Opzione 2:</label>
            <input type="text" id="option2" name="options[]" value="{{ $exercise->options[1] }}"><br><br>

            <label for="option3">Opzione 3:</label>
            <input type="text" id="option3" name="options[]" value="{{ $exercise->options[2] }}"><br><br>

            <label for="option4">Opzione 4:</label>
            <input type="text" id="option4" name="options[]" value="{{ $exercise->options[3] }}"><br><br>

            <label for="correct_option">Opzione corretta:</label>
            <input type="text" id="correct_option" name="correct_option" value="{{ $exercise->correct_option }}"><br><br>

            <label for="explanation">Spiegazione:</label>
            <textarea id="explanation" name="explanation">{{ $exercise->explanation }}</textarea><br><br>
        </div>

        <button type="submit">Salva Modifiche</button>
    </form>

    <script>
        document.getElementById('type').addEventListener('change', function() {
            if (this.value === 'Risposta Multipla') {
                document.getElementById('multiple_choice').style.display = 'block';
            } else {
                document.getElementById('multiple_choice').style.display = 'none';
            }
        });
    </script>
</body>
</html>
