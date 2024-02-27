<x-app-layout>
    <div class="container">
        <x-slot name="header">
            <h4>Elenco degli Esami</h4>
            <hr stile="border-top: 1px solid #000000; width: 90%;" />
        </x-slot>

        <div class="container">
            @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif
        </div>

        <div class="text-right mb-3">
            <button class="btn btn-secondary" style="display: none;" onclick="resetFilters()"><i class="fas fa-times"></i> {{ __('Reset Filtri') }}</button>
            <button class="btn btn-info" onclick="toggleFilterModal()"><i class="fas fa-filter"></i> {{ __('Filtri') }}</button>
            <!-- Bottone di creazione con menu a discesa -->
            <div class="dropdown" style="display: inline-block;">
                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-plus"></i> {{ __('Crea') }}
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="{{ route('create_automation') }}"><i class="fas fa-magic"></i> {{ __('Genera automaticamente') }}</a>
                    <a class="dropdown-item" href="{{ route('exame.step1') }}"><i class="fas fa-edit"></i> {{ __('Crea manualmente') }}</a>
                </div>
            </div>
        </div>

        <!-- Aggiunta dei filtri sopra la tabella -->
        <div id="filterSection" class="row mb-3" style="display: none;">
            <div class="row" style="margin-bottom: 0;">
                <div class="col-md-3">
                    <select id="materiaInput" class="form-select w-100 filter-select" aria-label="Seleziona materia" onchange="applyFilters()">
                        <option value="">{{ __('Tutte le materie') }}</option>
                        @foreach ($subjects as $subject)
                            <option value="{{ $subject }}">{{ $subject }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <input id="punteggioMinInput" type="number" class="form-control w-100 filter-select" aria-label="Inserisci punteggio minimo" onchange="applyFilters()" placeholder="Minimo punteggio">
                </div>
                <div class="col-md-3">
                    <input id="punteggioMaxInput" type="number" class="form-control w-100 filter-select" aria-label="Inserisci punteggio massimo" onchange="applyFilters()" placeholder="Massimo punteggio">
                </div>
                <div class="col-md-3">
                    <select id="difficoltaInput" class="form-select w-100 filter-select" aria-label="Seleziona difficoltà" onchange="applyFilters()">
                        <option value="">{{ __('Tutte le difficoltà') }}</option>
                        <option value="Bassa">{{ __('Bassa') }}</option>
                        <option value="Media">{{ __('Media') }}</option>
                        <option value="Alta">{{ __('Alta')}}</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table id="practice-table" class="table table-bordered table-striped">
                <colgroup>
                    <col style="width: 20%;">
                    <col style="width: 20%;">
                    <col style="width: 15%;">
                    <col style="width: 15%;">
                    <col style="width: 15%;">
                    <col style="width: 5%;">
                    <col style="width: 5%;">
                    <col style="width: 5%;">
                </colgroup>
                <thead>
                    <tr>
                        <th onclick="sortTable(0)">{{ __('Titolo') }} <i class="fas fa-chevron-down"></i></th>
                        <th onclick="sortTable(1)">{{ __('Materia') }} <i class="fas fa-chevron-down"></i></th>
                        <th onclick="sortTable(2)">{{ __('Difficoltà') }} <i class="fas fa-chevron-down"></i></th>
                        <th onclick="sortTable(3)">{{ __('Data') }} <i class="fas fa-chevron-down"></i></th>
                        <th onclick="sortTable(4)">{{ __('Punteggio') }} <i class="fas fa-chevron-down"></i></th>
                        <th>{{ __('Duplica') }}</th>
                        <th>{{ __('Visualizza') }}</th>
                        <th>{{ __('Modifica') }}</th>
                        <th>{{ __('Elimina') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($practices as $practice)
                    <tr>
                        <td>{{ $practice->title }}</td>
                        <td>{{ $practice->subject }}</td>
                        <td>{{ $practice->difficulty }}</td>
                        <td>{{ $practice->practice_date }}</td>
                        <td>{{ $practice->total_score }}</td>
                        <td>
                            <a href="{{ route('practices.duplicate', ['practice' => $practice]) }}" class="btn btn-primary"><i class="fas fa-copy"></i></a>
                        </td>
                        <td>
                            <a href="{{ route('practices.show', ['practice' => $practice]) }}" class="btn btn-info"><i class="fas fa-search"></i></a>
                        </td>
                        <td>
                            <a class="btn btn-warning edit-button" href="{{ route('practices.edit', ['practice' => $practice]) }}"><i class="fas fa-pencil-alt"></i></a>
                        </td>
                        <td>
                            <form action="{{ route('practices.destroy', ['practice' => $practice]) }}" method="POST" onsubmit="return confirm('Sei sicuro di voler eliminare questa pratica?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('myModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('myModal').style.display = 'none';
        }

        function toggleFilterModal() {
            var filterSection = document.getElementById('filterSection');
            var resetButton = document.querySelector('.btn-secondary');
            if (filterSection.style.display === 'none') {
                filterSection.style.display = 'block';
                resetButton.style.display = 'inline-block'; // Mostra il pulsante di reset
            } else {
                filterSection.style.display = 'none';
                resetButton.style.display = 'none'; // Nascondi il pulsante di reset
                resetFilters(); // Resetta i filtri quando il modulo dei filtri viene chiuso
            }
        }

        function resetFilters() {
            document.getElementById('materiaInput').value = '';
            document.getElementById('punteggioMinInput').value = '';
            document.getElementById('punteggioMaxInput').value = '';
            document.getElementById('difficoltaInput').value = '';
            applyFilters(); // Applica i filtri resettati
        }

        function applyFilters() {
            var materia = document.getElementById('materiaInput').value.toLowerCase();
            var punteggioMin = parseInt(document.getElementById('punteggioMinInput').value) || '';
            var punteggioMax = parseInt(document.getElementById('punteggioMaxInput').value) || '';
            var difficolta = document.getElementById('difficoltaInput').value.toLowerCase();
            var table = document.getElementById('practice-table');
            var rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

            for (var i = 0; i < rows.length; i++) {
                var title = rows[i].getElementsByTagName('td')[0].textContent.toLowerCase();
                var materiaCell = rows[i].getElementsByTagName('td')[1].textContent.toLowerCase();
                var punteggiCells = rows[i].getElementsByTagName('td')[4].textContent.trim().split(' ').map(score => parseInt(score));
                var difficoltaCell = rows[i].getElementsByTagName('td')[2].textContent.toLowerCase();

                // Aggiungi questa condizione per verificare se la riga è vuota
                if (title === '' && materiaCell === '' && punteggiCells.length === 0 && difficoltaCell === '') {
                    continue;
                }

                var showRow = true;

                if (materia !== '' && !(title.includes(materia) || materiaCell.includes(materia))) {
                    showRow = false;
                }

                if ((punteggioMin !== '' || punteggioMax !== '') && punteggiCells.length > 0) {
                    if (!punteggiCells.some(value => !isNaN(value) && (punteggioMin === '' || value >= punteggioMin) && (punteggioMax === '' || value <= parseInt(punteggioMax, 10)))) {
                        showRow = false;
                    }
                }

                if (difficolta !== '' && difficoltaCell !== difficolta) {
                    showRow = false;
                }

                if (showRow) {
                    rows[i].style.display = '';
                } else {
                    rows[i].style.display = 'none';
                }
            }
        }

        function sortTable(n) {
            var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
            table = document.getElementById("practice-table");
            switching = true;
            dir = "asc";

            while (switching) {
                switching = false;
                rows = table.rows;

                for (i = 1; i < (rows.length - 1); i++) {
                    shouldSwitch = false;
                    x = rows[i].getElementsByTagName("td")[n];
                    y = rows[i + 1].getElementsByTagName("td")[n];

                    if (dir == "asc") {
                        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                            shouldSwitch = true;
                            break;
                        }
                    } else if (dir == "desc") {
                        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                            shouldSwitch = true;
                            break;
                        }
                    }
                }

                if (shouldSwitch) {
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                    switching = true;
                    switchcount++;
                } else {
                    if (switchcount == 0 && dir == "asc") {
                        dir = "desc";
                        switching = true;
                    }
                }
            }
        }
    </script>
</x-app-layout>
