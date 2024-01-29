<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    </style>
</head>
<body>
    <div class="container">
        <h2>{{ $delivered->practice->title }}</h2>

        @foreach($exercises as $exercise)
            <div class="exercise">
                <h3 class="question">{{ $exercise->question }}</h3>
                @if(isset($response[$exercise->id]))
                    <h4 class="response">{{ $response[$exercise->id]->response }}</h4>
                @endif
            </div>
        @endforeach

        <div class="info">
            Qui sta il voto: {{ $delivered->valutation }} e le altre informazioni note...
        </div>
    </div>
</body>
</html>
