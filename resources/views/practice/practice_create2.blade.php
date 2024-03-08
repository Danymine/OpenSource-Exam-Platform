<x-app-layout>
  
    <x-slot name="header">
        <h4>
            {{ __('Crea Esercitazione') }}
        </h4>
        <hr style="border-top: 1px solid #0000004a; width: 90%;" />
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
    <div class="container p-4 rounded" style="background-color: #fff; box-shadow: 0.15rem 0.25rem 0 rgb(33 40 50 / 15%); border: 1px solid rgba(0,0,0,.125);">
        <div class="small-container">
            <div class="circle-container">
                <div class="circle"><i class="fas fa-check-circle text-success"></i></div>
                <div class="circle active-circle">2</div>
                <div class="circle">3</div>
                <div class="connector-line"></div>
                <div class="connector-line"></div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-6">
                <h2>{{ __('Aggiungi gli esercizi') }}</h2>
                <span id="score" class="badge bg-primary text-white"></span>
            </div>
            <div class="col-md-6 text-right">
                <!-- Bottone Cancella Filtri -->
                <button class="btn btn-secondary" style="display: none;" onclick="resetFilters()"><i class="fas fa-times"></i> {{ __('Cancella Filtri') }}</button>
                <button class="btn btn-info" onclick="toggleFilterSection()"><i class="fas fa-filter"></i> {{ __('Filtri') }}</button>
            </div>
            <span id="score"></span>
        </div>

        <!-- sezione dei filtri per la ricerca degli esercizi -->
        <div id="filterSection" class="row mb-3" style="display: none;">
            <div class="row justify-content-center text-center" style="margin-bottom: 0;">
                <div class="col-md-3">
                    <select id="materiaInput" class="form-select w-100 filter-select" aria-label="Seleziona materia" onchange="applyFilters()">
                        <option value="">{{ __('Tutte le materie') }}</option>
                        @foreach ($subjects as $subject)
                        <option value="{{ $subject }}">{{ $subject }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select id="typeInput" class="form-select w-100 filter-select" aria-label="Seleziona la tipologia" onchange="applyFilters()">
                        <option value="">{{ __('Tutte le tipologie') }}</option>
                        @foreach ($types as $type)
                        <option value="{{ $type }}">{{ $type }}</option>
                        @endforeach
                    </select>
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
        
        <form method="POST" action="{{ route('create_practice_step2') }}">
            @csrf
            @if(!$exercises->isEmpty())
                
                    @foreach($exercises as $exercise)
                        <div class="exercise p-4 rounded mt-2" style="background-color: #fff; box-shadow: 0.15rem 0.25rem 0 rgb(33 40 50 / 15%); border: 1px solid rgba(0,0,0,.125);" data-subject="{{ $exercise->subject }}"  data-difficulty="{{ $exercise->difficulty }}" data-type="{{ $exercise->type }}">
                            <div class="form-check d-flex justify-content-between align-items-center">
                                <label class="text-center mb-0" for="exercise{{ $exercise->id }}">
                                    {{ $exercise->name }}
                                </label>
                                <div class="d-flex align-items-stretch">
                                    
                                    <a style="color: blue;" class="details-ex mr-2" onclick="showDetails('{{ $exercise->id }}')"><i class="fas fa-search"></i></a>
                                    <input type="checkbox" id="exercise{{ $exercise->id }}" data-score="{{ $exercise->score }}" name="exercise[]" value="{{ $exercise->id }}" class="align-self-stretch" {{ session()->has('exame_step1') && array_key_exists('exercise', session('exame_step1')) && in_array($exercise->id, session('exame_step1')['exercise']) ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>
                    @endforeach

            @else

                <h5>{{ __("Qui non c'è un bel nulla :C") }}</h5>
            @endif

            <!-- Bottoni -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <a class="btn btn-danger text-white" id="annulla" href="{{ route('exit_exame_process') }}">{{ __('Annulla') }}</a>
                </div>
                <div class="col-md-6 text-right">
                    <a class="btn btn-info text-white" id="back" href="{{ route('exame.step1') }}">{{ __('Indietro') }}</a>
                    <button type="submit" class="btn btn-primary ml-2" id="avanti">{{ __('Avanti') }}</button>
                </div>
            </div>

        </form>
    </div>

     <!-- Modal -->
    <div class="modal fade" id="details-dialog" tabindex="-1" aria-labelledby="details-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
            <h4 class="modal-title" id="details-title"></h4>
            <button type="button" class="btn-danger rounded" data-bs-dismiss="modal" aria-label="Close" onclick="closeDetailsDialog()">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body" id="details-content">
            </div>
        </div>
        </div>
    </div>

    <script>
        var exercises = @json($exercises);
    </script>
    <script src="/js/createExame.js"></script>

</x-app-layout>
