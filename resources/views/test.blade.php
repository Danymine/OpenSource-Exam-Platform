<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esame - Nome Esame</title>
    <style>
    </style>
</head>
    <body>
        <h1>{{ $test->title }}</h1>
        <div>
            <form action="{{ route('pratices.send') }}" method="post">
                @csrf
                <input type="text" name="id_practices" value="{{ $test->id }}" style="display: none">
                @foreach($test->exercises as $exercise)
                    <div class="domanda">
                        <input type="text" name="id[]" value="{{ $exercise->id }}" style="display: none"> <!--Da considerare utile in occassione della randomizzazione delle domande -->
                        <p class="testo-domanda"> {{ $exercise->question }} <span> Score: {{ $exercise->score }}</span> </p>
                        @if( $exercise->type == "Risposta Aperta")
                            <textarea name="risposte[{{ $exercise->id }}]" cols="30" rows="2"></textarea>
                        @else
                            <span> {{  $exercise->option_1 }} </span>
                            <input type="radio" name="risposte[{{ $exercise->id }}]" value="{{ $exercise->option_1 }}">
                            <br>
                            <span> {{  $exercise->option_2 }} </span>
                            <input type="radio" name="risposte[{{ $exercise->id }}]" value="{{ $exercise->option_2 }}">
                            <br>
                            <span> {{  $exercise->option_3 }} </span>
                            <input type="radio" name="risposte[{{ $exercise->id }}]" value="{{ $exercise->option_3 }}">
                            <br>
                            <span> {{  $exercise->option_4 }} </span>
                            <input type="radio" name="risposte[{{ $exercise->id }}]" value="{{ $exercise->option_4 }}">
                            <br>
                        @endif
                    </div>
                    <hr>
                @endforeach

                <button type="submit">Invia Risposte</button>
            </form>
        </div>
    </body>
</html>