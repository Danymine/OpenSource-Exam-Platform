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
                @php
                    $rand = true; //Qui va messo altro
                    if( $rand == true){

                        shuffle($exercises);
                    }
                @endphp
                @for($i = 0; $i < count($exercises); $i++)
                    <div class="domanda">
                        <input type="text" name="id[]" value="{{ $exercises[$i]['id'] }}" style="display: none"> <!--Da considerare utile in occassione della randomizzazione delle domande -->
                        <p class="testo-domanda"> {{ $exercises[$i]["question"] }} <span> Score: {{ $exercises[$i]["score"] }}</span> </p>
                        @if( $exercises[$i]["type"] == "Risposta Aperta")
                            <textarea name="risposte[{{ $exercises[$i]['id'] }}]" cols="30" rows="2"></textarea>
                        @else
                            <span> {{  $exercises[$i]["option_1"] }} </span>
                            <input type="radio" name="risposte[{{ $exercises[$i]['id'] }}]" value="{{ $exercises[$i]['option_1'] }}">
                            <br>
                            <span> {{  $exercises[$i]["option_2"] }} </span>
                            <input type="radio" name="risposte[{{ $exercises[$i]['id'] }}]" value="{{ $exercises[$i]['option_2'] }}">
                            <br>
                            <span> {{  $exercises[$i]["option_3"] }} </span>
                            <input type="radio" name="risposte[{{ $exercises[$i]['id'] }}]" value="{{ $exercises[$i]['option_3'] }}">
                            <br>
                            <span> {{  $exercises[$i]["option_4"] }} </span>
                            <input type="radio" name="risposte[{{ $exercises[$i]['id'] }}]" value="{{ $exercises[$i]['option_4'] }}">
                            <br>
                        @endif
                    </div>
                    <hr>
                @endfor

                <button type="submit">Invia Risposte</button>
            </form>
        </div>
    </body>
</html>