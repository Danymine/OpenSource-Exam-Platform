<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Esito') }} "{{ $practices }}"</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
        }

        p {
            color: #666;
        }

        .cta-button {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #4caf50;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>{{ __('Esito Esame') }} "{{ $practices }}"</h2>
        <p>{{ __('Caro,') }}, {{ $name }}, {{ __('le comunichiamo che l\'esito della prova relativa all\'attività didattica') }} "{{ $practices }}", {{ __('da lei sostenuta in data') }} "{{ $practice->practice_date }}" {{ __('è') }}: {{ $score }}/{{ $practice->total_score }}</p>
        <h4>{{ __('Esercizi Sbagliati') }}</h4>
        @for($i = 0; $i < count($explanation); $i++)
            <div>{{ __('All\'esercizio con domanda') }}: "{{ $explanation[$i][0] }}" <br/> {{ __('hai risposto') }}: "{{ $explanation[$i][1] }}" <br/> {{ __('ma quella giusta era') }}: "{{ $explanation[$i][2] }}" <br/> {{ __('Spiegazione') }}: "{{ $explanation[$i][3] }}"</div>
        @endfor

        <a href="{{ route('dashboard') }}" class="cta-button">{{ __('Vai al Libretto') }}</a>
    </div>
</body>
</html>