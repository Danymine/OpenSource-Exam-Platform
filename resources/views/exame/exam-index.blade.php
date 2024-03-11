<x-app-layout>
    <div class="container">
        <x-slot name="header">
            <h4>{{ __('Elenco degli Esami')}}</h4>
            <hr style="border-top: 1px solid #0000004a width: 90%;" />
        </x-slot>

        <div class="container">
            @if($errors->any())
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    {{ session('success') }}
                </div>
            @endif
        </div>
        <script>
            trans = "{{ __('Sei sicuro di voler eliminare questo Esame?') }}";
        </script>

        <div class="text-right mb-3">
            <button class="btn btn-secondary" style="display: none;" onclick="resetFilters()"><i class="fas fa-times"></i> {{ __('Cancella Filtri') }}</button>
            <button class="btn btn-info" onclick="toggleFilterModal()"><i class="fas fa-filter"></i> {{ __('Filtri') }}</button>
            <!-- Bottone di creazione con menu a discesa -->
            <div class="dropdown" style="display: inline-block;">
                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownCreateMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
                    <input id="punteggioMinInput" type="number" class="form-control w-100 filter-select" aria-label="Inserisci punteggio minimo" onchange="applyFilters()" placeholder="{{ __('Minimo punteggio') }}">
                </div>
                <div class="col-md-3">
                    <input id="punteggioMaxInput" type="number" class="form-control w-100 filter-select" aria-label="Inserisci punteggio massimo" onchange="applyFilters()" placeholder="{{ __('Massimo punteggio') }}">
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
                    <col style="width: 30%;">
                    <col style="width: 10%;">
                    <col style="width: 10%;">
                    <col style="width: 10%;">
                    <col style="width: 15%;">
                    <col style="width: 20%;">
                </colgroup>
                <thead>
                    <tr>
                        <th onclick="sortTable(0)">{{ __('Titolo') }} <i class="fas fa-chevron-down"></i></th>
                        <th onclick="sortTable(1)">{{ __('Materia') }} <i class="fas fa-chevron-down"></i></th>
                        <th onclick="sortTable(2)">{{ __('Difficoltà') }} <i class="fas fa-chevron-down"></i></th>
                        <th onclick="sortTable(3)">{{ __('Data') }} <i class="fas fa-chevron-down"></i></th>
                        <th onclick="sortTable(4)">{{ __('Punteggio') }} <i class="fas fa-chevron-down"></i></th>
                        <th>{{ __('Azioni') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($practices as $practice)
                    <tr>
                        <td>{{ ucfirst($practice->title) }}</td>
                        <td>{{ ucfirst($practice->subject) }}</td>
                        <td>{{ __($practice->difficulty) }}</td>
                        <td>{{ $practice->practice_date }}</td>
                        <td>{{ $practice->total_score }}</td>
                        <td>
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('practices.duplicate', ['practice' => $practice]) }}" class="btn btn-primary"><i class="fas fa-copy"></i></a>
                                <a href="{{ route('practices.show', ['practice' => $practice]) }}" class="btn btn-info"><i class="fas fa-search"></i></a>
                                <a class="btn btn-warning edit-button" href="{{ route('practices.edit', ['practice' => $practice]) }}"><i class="fas fa-pencil-alt"></i></a>
                                <form action="{{ route('practices.destroy', ['practice' => $practice]) }}" method="POST" onsubmit="return confirm(trans);">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        var practices = @json($practices);
        var translations = {
            'translate': {
                "Bassa": "{{ __('Bassa') }}",
                "Media": "{{ __('Media') }}",
                "Alta": "{{ __('Alta') }}",
            }
        };
    </script>
    <script src='/js/Test.js'></script>

</x-app-layout>
