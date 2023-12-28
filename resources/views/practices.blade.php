<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elenco delle Esercitazioni</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }

        h1 {
            color: #333;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            background-color: #fff;
            border-radius: 4px;
            margin-bottom: 10px;
            padding: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #3498db;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        a:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <h1>Elenco delle Esercitazioni</h1>
    @if($practices->isEmpty())
        <p>Nessuna esercitazione trovata.</p>
    @else
        <ul>
            @foreach($practices as $practice)
                <li>
                    <strong>{{ $practice->title }}</strong> - {{ $practice->description }}
                </li>
            @endforeach
        </ul>
    @endif

    <a href="{{ route('practices.create') }}">Crea una nuova esercitazione</a>

</body>
</html>

