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

    <form action="{{ route('exercises.update', $exercise->id) }}" method="POST">
    @csrf
    @method('PUT')


        <label for="name">Nome:</label>
        <input type="text" id="name" name="name" value="{{ $exercise->name }}"><br><br>

        <label for="question">Domanda:</label>
        <textarea id="question" name="question">{{ $exercise->question }}</textarea><br><br>

        <label for="score">Punteggio:</label>
        <input type="text" id="score" name="score" value="{{ $exercise->score }}"><br><br>

            
        <label for="difficulty">Difficoltà:</label>
        <select id="difficulty" name="difficulty">
            <option value="Bassa">Bassa</option>
            <option value="Media">Media</option>
            <option value="Alta">Alta</option>
        </select><br><br>

        <label for="subject">Materia:</label>
        <input type="text" id="subject" name="subject" value="{{ $exercise->subject }}"><br><br>

        <label for="type">Tipo:</label>
        <select id="type" name="type">
            <option value="Risposta Aperta" {{ $exercise->type == 'Risposta Aperta' ? 'selected' : '' }}>Risposta Aperta</option>
            <option value="Risposta Multipla" {{ $exercise->type == 'Risposta Multipla' ? 'selected' : '' }}>Risposta Multipla</option>
            <option value="Vero o Falso" {{ $exercise->type == 'Vero o Falso' ? 'selected' : '' }}>Vero o Falso</option>
        </select><br><br>

        <div id="multiple_choice" style="display: {{ $exercise->type == 'Risposta Multipla' ? 'block' : 'none' }};">
            <label for="option1">Opzione 1:</label>
            <input type="text" id="option1" name="options[]" value="{{ $exercise->options[0] ?? '' }}"><br><br>

            <label for="option2">Opzione 2:</label>
            <input type="text" id="option2" name="options[]" value="{{ $exercise->options[1] ?? '' }}"><br><br>

            <label for="option3">Opzione 3:</label>
            <input type="text" id="option3" name="options[]" value="{{ $exercise->options[2] ?? '' }}"><br><br>

            <label for="option4">Opzione 4:</label>
            <input type="text" id="option4" name="options[]" value="{{ $exercise->options[3] ?? '' }}"><br><br>

            <label for="correct_option">Opzione corretta:</label>
            <input type="text" id="correct_option" name="correct_option" value="{{ $exercise->correct_option }}"><br><br>

            <label for="explanation">Spiegazione:</label>
            <textarea id="explanation" name="explanation">{{ $exercise->explanation }}</textarea><br><br>
        </div>
        <div id="true_false" style="display: {{ $exercise->type == 'Vero o Falso' ? 'block' : 'none' }};">
    <label for="answer1">Opzione 1:</label>
    <input type="text" id="answer1" name="answer1" value="{{ $exercise->answer1 }}"><br><br>

    <label for="answer2">Opzione 2:</label>
    <input type="text" id="answer2" name="answer2" value="{{ $exercise->answer2 }}"><br><br>

    <label for="correct_answer">Opzione corretta:</label>
    <select id="correct_answer" name="correct_answer">
        <option value="vero" {{ $exercise->correct_answer == 'vero' ? 'selected' : '' }}>Vero</option>
        <option value="falso" {{ $exercise->correct_answer == 'falso' ? 'selected' : '' }}>Falso</option>
    </select><br><br>

    <label for="explanation">Spiegazione:</label>
    <textarea id="explanation" name="explanation">{{ $exercise->explanation }}</textarea><br><br>
    </div>
        <button type="submit">Aggiorna Esercizio</button>
    </form>


<script>

    document.getElementById('type').addEventListener('change', function() {
        if (this.value === 'Risposta Multipla') {
            document.getElementById('multiple_choice').style.display = 'block';
            document.getElementById('true_false').style.display = 'none';
        } else if (this.value === 'Vero o Falso') {
            document.getElementById('multiple_choice').style.display = 'none';
            document.getElementById('true_false').style.display = 'block';
        } else {
            document.getElementById('multiple_choice').style.display = 'none';
            document.getElementById('true_false').style.display = 'none';
        }
    });

</script>