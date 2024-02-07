<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Vista Esame</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .exercise {
            margin-bottom: 30px;
        }

        .question {
            color: red;
            font-size: 20px;
            margin-bottom: 10px;
        }

        .response {
            color: #28a745;
            font-size: 18px;
            margin-top: 5px;
        }

        .info {
            font-size: 16px;
            color: #6c757d;
        }

        .print {
            color: black; /* Colore bianco di default */
            text-decoration: none; /* Rimuove il sottolineato dal link */
        }

        .print:hover {
            color: red; /* Colore rosso al passaggio del mouse */
        }

    </style>
</head>
<body>
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h2 style="margin: 0;">{{ $delivered->practice->title }}</h2>
            <a href="{{ route('download-details-delivered', ['delivered' => $delivered]) }}" class="print">Stampa Test<i class="fa fa-print" style="font-size:36px"></i></a>
            @if ( $delivered->path != NULL )
                <a href="{{ route('download-correct-delivered', ['delivered' => $delivered]) }}" class="print">Stampa Correzione<i class="fa fa-print" style="font-size:36px"></i></a>
            @endif
        </div>

        @if( Auth::user()->roles == "Student")

            @foreach($exercises as $exercise)
                <div class="exercise">
                    <div class="question">
                        <h3> {{ $exercise->question }} </h3>
                    </div>
                    <div class="answers">
                        <h3> {{ $response[$exercise->id]->response }} </h3>
                    </div>
                </div>
            @endforeach

            @if ( $delivered->practice->public == 1 )

                <div>
                    <h4>{{ $delivered->valutation }}</h4>
                    <h5>{{ $delivered->note }} </h5>
                </div>
            @else

                <div>
                    <h4>I risultati non sono stati ancora pubblicati </h4>
                </div>
            @endif
        @else

            @if ( $delivered->practice->public == 0 )

                <form action="{{ route('store-valutation') }}" method="POST" >
                    @csrf
                    <input type="number" name="id_delivered" value="{{ $delivered->id }}" style="display: none">
                    @foreach($exercises as $exercise)
                        <div class="exercise">
                            <div>
                                <h3 class="question"> {{ $exercise->question }} </h3>
                                @if ( $exercise->type == "Risposta Multipla" or $exercise->type == "Vero o Falso")

                                    <input type="radio" name="risposta[{{ $exercise->id }}]" value="{{$exercise->pivot->custom_score }}" required>
                                    <label>Corretta</label><br>
                                    <input type="radio" id="{{ $exercise->id }}" name="risposta[{{ $exercise->id }}]" value="0" required>
                                    <label>Sbagliata</label><br>
                                
                                @else

                                    Assegna un punteggio (Deve essere compreso fra 0 e {{ $exercise->pivot->custom_score }}): <input type="number" name="risposta_aperta[{{ $exercise->id }}]" placeholder="Score" step="0.01" min="0" max="{{$exercise->pivot->custom_score}}" > <br/>
                                    Segna delle note sull'esercizio: <input type="text" name="note[{{ $exercise->id }}]" placeholder="Note"><br/>
                                @endif
                            </div>
                            <div class="answers">
                                <h3> {{ $response[$exercise->id]->response }} </h3>
                            </div>
                        </div>
                    @endforeach
                    <input type="text" name="note_general">
                    <input type="file" name="correct-file"><br/>
                    <input type="submit" value="Salva">
                    @if ($errors->any())
                        <div>
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        </div>
                    @endif
                </form>
            @else

                @foreach($exercises as $exercise)
                    <div class="exercise">
                        <div class="question">
                            <h3> {{ $exercise->question }} </h3>
                        </div>
                        <div class="answers">
                            <h3> {{ $response[$exercise->id]->response }} </h3>
                        </div>
                    </div>
                @endforeach
            @endif
        @endif

    </div>
</body>
</html>
