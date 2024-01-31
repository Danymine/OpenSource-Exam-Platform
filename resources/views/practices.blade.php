<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elenco delle Pratiche</title>
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
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #fff;
            border-radius: 4px;
            margin-bottom: 10px;
            padding: 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            position: relative; 
        }

        a {
            text-decoration: none;
            color: #333;
        }

        a:hover {
            text-decoration: underline;
        }

        .actions {
            display: flex;
            gap: 10px;
            position: absolute; 
            right: 15px; 
            top: 50%; 
            transform: translateY(-50%); 
        }

        form {
            display: flex;
        }

        button {
            background: none;
            border: none;
            cursor: pointer;
            padding: 5px;
        }

        button:hover {
            text-decoration: underline;
        }

        .new-practice-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #3498db;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s ease;
            margin-top: 20px;
            display: block;
            max-width: 200px;
        }

        .new-practice-button:hover {
            background-color: #2980b9;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 50%;
            text-align: center;
        }

        .close {
            color: #aaaaaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }

        button.modal-button {
            padding: 10px 20px;
            margin: 10px;
            background-color: #3498db;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button.modal-button:hover {
            background-color: #2980b9;
        }

    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var modal = document.getElementById('myModal');
            var btn = document.getElementById('newPracticeBtn');
            var span = document.getElementsByClassName('close')[0];

            btn.onclick = function () {
                modal.style.display = 'block';
            }

            span.onclick = function () {
                modal.style.display = 'none';
            }

            window.onclick = function (event) {
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            }
        });

        function generateAutomatically() {
            // Chiudi la finestra modale
            document.getElementById('myModal').style.display = 'none';

            // Esegui una richiesta di reindirizzamento al server
            window.location.href = '{{ route("practices.create", ['type' => $type]) }}';
        }

        function createManually() {
            // Chiudi la finestra modale
            document.getElementById('myModal').style.display = 'none';

            // Reindirizza alla vista per la creazione manuale
            window.location.href = '{{ route("exercise.list", ['type' => $type]) }}';
        }
    </script>
</head>
<body>
    <h1>
        @if ($type === 'esame')
            Elenco degli Esami
        @elseif ($type === 'esercitazione')
            Elenco delle Esercitazioni
        @endif
    </h1>

    @if ($practices->where('type', $type)->isEmpty())
        <p>Non sono state ancora create @if ($type === 'esercitazione') esercitazioni @elseif ($type === 'esame') esami @endif.</p>
    @else
        <ul>
            @foreach($practices->where('type', $type) as $practice)
                <li>
                    <div class="title-section">
                        <a href="{{ route('practices.show', ['type' => $type, 'practice' => $practice->id]) }}">
                            <strong>{{ $practice->title }}</strong> - {{ $practice->description }}
                        </a>
                        <div class="actions">
                            <form method="POST" action="{{ route('practices.duplicate', ['type' => $type, 'practice' => $practice->id]) }}">
                                @csrf
                                @method('GET')
                                <button type="submit"><i class="">Duplica</i></button>
                            </form>
                            <form method="POST" action="{{ route('practices.edit', ['type' => $type, 'practice' => $practice->id]) }}">
                                @csrf
                                @method('GET')
                                <button type="submit"><i class="fas fa-pencil-alt"></i></button>
                            </form>
                            <form method="POST" action="{{ route('practices.destroy', ['type' => $type, 'practice' => $practice->id]) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif


    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p>Scegli come creare la nuova pratica:</p>
            <button onclick="generateAutomatically()">Genera automaticamente</button>
            <button onclick="createManually()">Crea manualmente</button>
        </div>
    </div>

    <a href="#" id="newPracticeBtn" class="new-practice-button">Crea una nuova pratica</a>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var modal = document.getElementById('myModal');
            var btn = document.getElementById('newPracticeBtn');
            var span = document.getElementsByClassName('close')[0];

            btn.onclick = function () {
                modal.style.display = 'block';
            }

            span.onclick = function () {
                modal.style.display = 'none';
            }

            window.onclick = function (event) {
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            }
        });

        function generateAutomatically() {
            // Chiudi la finestra modale
            document.getElementById('myModal').style.display = 'none';

            // Esegui una richiesta di reindirizzamento al server
            window.location.href = '{{ route("practices.create", ['type' => $type]) }}';
        }

        function createManually() {
            // Chiudi la finestra modale
            document.getElementById('myModal').style.display = 'none';

            // Reindirizza alla vista per la creazione manuale
            window.location.href = '{{ route("exercise.list", ['type' => $type]) }}';
        }
    </script>
</body>
</html>
