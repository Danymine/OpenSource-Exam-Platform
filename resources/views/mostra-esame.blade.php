<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vista</title>
</head>
<body>
    
    <h2> {{ $delivered->practice->title }}</h2>

    @foreach( $delivered->practice->exercises as $exercise )
        <div class="exercise">
            <h3 class="question"> {{ $exercise->question }} </h3>
        @foreach($response as $risposta)
            @if( $risposta->exercise_id == $exercise->id)

                <h4 class="response"> {{ $risposta->response }} </h4>
            @endif
        @endforeach
        </div>
    @endforeach

    <div>
        Qui sta il voto: {{ $delivered->valutation }} e le altre cose note....
    </div>
</body>
</html>