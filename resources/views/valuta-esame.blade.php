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
            color: #007bff;
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

        @foreach($exercises as $exercise)
            <div class="exercise">
                <h3 class="question">{{ $exercise->question }}</h3>
                @if(isset($response[$exercise->id]))
                    <h4 class="response">{{ $response[$exercise->id]->response }}</h4>
                @endif
            </div>
        @endforeach

        @if( Auth::user()->roles == "Student" )
            <div class="info">
                Qui sta il voto: {{ $delivered->valutation }}
            </div>
            <div>
                {{ $delivered->note }}
            </div>
        @else
                <div class="valuta">
                    <form action="{{ route('store-valutation') }}" method="post"  enctype="multipart/form-data">
                        @csrf
                        <input type="number" name="id" value="{{ $delivered->id }}"  style="display: none">
                        Voto<br/><input type="number" name="valutation" class="@error('valutation') is-invalid @enderror"><br/>
                        @error('valutation')
                            <div class="alert alert-danger" style="color: red;">{{ $message }}</div>
                        @enderror
                        Note<br/><input type="text" name="note" class="@error('note') is-invalid @enderror"><br/>
                        @error('note')
                            <div class="alert alert-danger" style="color: red;">{{ $message }}</div>
                        @enderror
                        Correzione<br/><input type="file" name="correct-file" class="@error('file') is-invalid @enderror"><br/>
                        @error('file')
                            <div class="alert alert-danger" style="color: red;">{{ $message }}</div>
                        @enderror
                        <input type="submit">
                    </form>
                </div>
        @endif
    </div>
</body>
</html>
