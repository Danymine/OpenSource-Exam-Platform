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
            font-size: 24px;
            margin-bottom: 20px;
        }

        ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        li {
            background-color: #fff;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            padding: 20px;
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
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
        }

        .actions form {
            margin-left: auto;
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
            max-width: 150px;
            text-align: center;
            float: left;
            display: block;
            margin: 20px auto;
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
            border-radius: 8px;
        }

        .closeM {
            color: #aaaaaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .closeM:hover,
        .closeM:focus {
            color: #000;
            text-decoration: none;
        }

        button.modal-button {
            padding: 10px 20px;
            margin: 10px;
            background-color: #3498db;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button.modal-button:hover {
            background-color: #2980b9;
        }

        .dashboard-button {
            display: inline-block;
            padding: 10px;
            background-color: #3498db;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s ease;
            max-width: 40px;
            text-align: center;
            margin-bottom: 20px;
        }

        .dashboard-button i {
            color: #fff;
        }

        .dashboard-button:hover {
            background-color: #2980b9;
        }

        .button-icon {
            background: none;
            border: none;
            cursor: pointer;
            padding: 5px;
        }

        .button-icon:hover {
            text-decoration: underline;
        }

        .button-icon i {
            font-size: 18px;
        }

        .button-primary {
            background-color: #3498db;
            color: #fff;
            border-radius: 4px;
            transition: background-color 0.3s ease;
            padding: 8px 12px;
        }

        .button-primary:hover {
            background-color: #2980b9;
        }

        .button-secondary {
            background-color: #ccc;
            color: #333;
            border-radius: 4px;
            transition: background-color 0.3s ease;
            padding: 8px 12px;
        }

        .button-secondary:hover {
            background-color: #bbb;
        }

        .filter-button {
            position: absolute;
            top: 20px;
            right: 20px;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 18px;
            color: #333;
        }

        .sidenav {
            height: 100%;
            width: 0;
            position: fixed;
            z-index: 2;
            top: 0;
            right: 0;
            background-color: #fefefe;
            overflow-x: hidden;
            transition: 0.5s;
            padding-top: 60px;
            box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.5);
        }

        .sidenav-content {
            padding: 20px;
        }

        .sidenav .close {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 28px;
            margin-left: 50px;
        }

        .sidenav h2 {
            margin-bottom: 20px;
        }

        .sidenav .form-group {
            margin-bottom: 15px;
        }

        .sidenav select {
            width: 100%;
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        .sidenav button {
            width: calc(50% - 5px); 
            margin-top: 10px;
        }

        .openSidenav {
            width: 300px;
        }

        .closeSidenav {
            width: 0;
            transition: width 0s; 
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group select {
            width: 100%; 
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        .reset-button {
            background-color: #ccc;
            color: #333;
            border-radius: 4px;
            transition: background-color 0.3s ease;
            padding: 8px 12px;
            margin-left: 10px;
            cursor: pointer;
        }

        .reset-button:hover {
            background-color: #bbb;
        }
    </style>
</head>
<body>
    <h1>
        @if ($type === 'Exam')
        Elenco degli Esami
        @elseif ($type === 'Practice')
        Elenco delle Esercitazioni
        @endif
    </h1>

    <button class="filter-button" onclick="openNav()"><i class="fas fa-filter"></i></button> <!-- Bottone con icona dei filtri -->

    <div class="sidenav" id="filterSidenav">
        <div class="sidenav-content">
            <span class="close" onclick="closeNav()">&times;</span>
            <h2>Filtri</h2>

            <div class="form-group">
                <label for="subjectFilter">Filtra per Materia:</label>
                <select id="subjectFilter" name="subjectFilter">
                    <option value="">Tutte le Materie</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject }}">{{ $subject }}</option>
                        @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="difficultyFilter">Filtra per Difficoltà:</label>
                <select id="difficultyFilter" name="difficultyFilter">
                    <option value="">Tutte le Difficoltà</option>
                    <option value="Bassa">Bassa</option>
                    <option value="Media">Media</option>
                    <option value="Alta">Alta</option>
                </select>
            </div>
            <button id="applyFiltersBtn" class="button-primary">Applica Filtri</button>
            <button id="resetFiltersBtn" class="reset-button">Resetta Filtri</button>
        </div>
    </div>

    @if ($practices->where('type', $type)->isEmpty())
        <p>Nessun @if ($type === 'esercitazione') esercitazione @elseif ($type === 'esame') esame trovato @endif.</p>
    @else
        <ul>
            @foreach($practices->where('type', $type) as $practice)
                <li>
                    <div class="title-section">
                        <a href="{{ route('practices.show', ['type' => $type, 'practice' => $practice->id]) }}">
                        <strong>{{ $practice->title }}</strong> - {{ $practice->description }}
                    </div>
                </a>
                <div class="actions">
                    <form method="POST"
                        action="{{ route('practices.duplicate', ['type' => $type, 'practice' => $practice->id]) }}">
                        @csrf
                        @method('GET')
                        <button class="button-icon button-primary">
                            <i class="">Duplica</i>
                        </button>
                    </form>
                    <form method="POST"
                        action="{{ route('practices.edit', ['type' => $type, 'practice' => $practice->id]) }}">
                        @csrf
                        @method('GET')
                        <button class="button-icon button-primary">
                            <i class="fas fa-pencil-alt"></i>
                        </button>
                    </form>
                    <form method="POST"
                        action="{{ route('practices.destroy', ['type' => $type, 'practice' => $practice->id]) }}">
                        @csrf
                        @method('DELETE')
                        <button class="button-icon button-secondary">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
                </div>
                <span class="difficulty" style="display: none;">{{ $practice->difficulty }}</span>
                <span class="subject" style="display: none;">{{ $practice->subject }}</span>
            </li>
            @endforeach
        </ul>
        {{ $practices->links() }} <!-- Aggiunta della paginazione -->
    @endif

    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="closeM" onclick="closeModal()">&times;</span>
            <p>Scegli come creare l'{{ $type }}:</p>
            <button onclick="generateAutomatically()" class="modal-button">Genera automaticamente</button>
            <button onclick="createManually()" class="modal-button">Crea manualmente</button>
        </div>
    </div>

    <a href="#" id="newPracticeBtn" class="new-practice-button">Crea</a>
    
    <!--script apertura e chiusura sidenav-->
    <script>
        function openNav() {
            document.getElementById("filterSidenav").classList.add("openSidenav");
        }

        function closeNav() {
            document.getElementById("filterSidenav").classList.remove("openSidenav");
        }
    </script>

    <!-- Script per il filtraggio delle pratiche e il reset dei filtri -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var applyBtn = document.getElementById('applyFiltersBtn');
            var resetBtn = document.getElementById('resetFiltersBtn'); // Seleziona il pulsante di reset

            // Funzione per applicare i filtri
            applyBtn.onclick = function () {
                applyFilters();
            }

            // Funzione per il reset dei filtri
            resetBtn.onclick = function () {
                // Resetta il valore dei filtri a vuoto
                document.getElementById('subjectFilter').value = '';
                document.getElementById('difficultyFilter').value = '';
                // Effettua una richiesta GET per resettare i filtri
                window.location.href = '{{ route("practices.index", ["type" => $type]) }}';
            }

            // Funzione per il filtraggio delle pratiche
            function applyFilters() {
                var subjectFilter = document.getElementById('subjectFilter').value;
                var difficultyFilter = document.getElementById('difficultyFilter').value;

                // Costruisci l'URL con i filtri
                var url = '{{ route("practices.index", ["type" => $type]) }}';

                // Aggiungi i filtri all'URL se non sono vuoti
                if (subjectFilter !== '') {
                    url += '?subject=' + subjectFilter;
                }

                if (difficultyFilter !== '') {
                    url += (subjectFilter !== '' ? '&' : '?') + 'difficulty=' + difficultyFilter;
                }

                // Esegui una richiesta GET all'URL con i filtri
                window.location.href = url;
            }
        });
    </script>

    <!--script per il bottone di creazione-->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var modal = document.getElementById('myModal');
            var btn = document.getElementById('newPracticeBtn');
            var span = document.getElementsByClassName('closeM')[0];

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
            document.getElementById('myModal').style.display = 'none';
            window.location.href = '{{ route("practices.create", ['type' => $type]) }}';
        }

        function createManually() {
            document.getElementById('myModal').style.display = 'none';
            window.location.href = '{{ route("exercise.list", ['type' => $type]) }}';
        }
    </script>

</body>
</html>