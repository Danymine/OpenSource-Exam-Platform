<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elenco delle Esercitazioni</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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
            display: flex; /* Utilizzo Flexbox per allineare gli elementi */
            align-items: center; /* Allineo verticalmente gli elementi */
        }

        a {
            text-decoration: none;
            color: #333;
        }

        a:hover {
            text-decoration: underline;
        }

        .actions {
            margin-left: auto; /* Sposta gli elementi a destra */
        }

        form {
            display: inline; /* Permette di visualizzare i bottoni in linea */
            margin-right: 10px; /* Spazio tra i bottoni */
        }

        button {
            background: none; /* Rimuove lo sfondo del bottone */
            border: none;
            cursor: pointer;
        }

        button:hover {
            text-decoration: underline; /* Effetto di sottolineatura al passaggio del mouse */
        }

        .new-practice-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #3498db;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .new-practice-button:hover {
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
                    <div class="title-section">
                        <a href="{{ route('practices.show', $practice->id) }}">
                            <strong>{{ $practice->title }}</strong> - {{ $practice->description }}
                        </a>
                    </div>
                    <div class="actions">
                        <form method="POST" action="{{ route('practices.edit', $practice->id) }}">
                            @csrf
                            @method('GET')
                            <button type="submit"><i class="fas fa-pencil-alt"></i></button>
                        </form>
                        <form method="POST" action="{{ route('practices.destroy', $practice->id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit"><i class="fas fa-trash-alt"></i></button>
                        </form>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif

    <a href="{{ route('practices.create') }}" class="new-practice-button">Crea una nuova esercitazione</a>
</body>
</html>
