<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="font-semibold text-xl leading-tight">
                {{ __('Storico') }}
            </h4>
            <div> 
                <a href="{{ route('dashboard') }}" class="btn btn-info">{{ __('Torna Indietro') }}</a>
            </div>
        </div>
        <hr style="border-top: 1px solid #0000004a; width: 90%;" />
    </x-slot>

    <div class="container">
        <!-- Bottone per mostrare i filtri e resetta filtri -->
        <div class="col-md-3 mt-3 mb-3"> 
            <button class="btn btn-info" onclick="toggleFilters()">{{ __('Filtri') }}</button>
            <button class="btn btn-secondary" style="display: none;" id="resetFiltersBtn" onclick="resetFilters()">{{ __('Resetta Filtri') }}</button>
        </div>
        
        <!-- Sezione dei filtri (inizialmente nascosta) -->
        <div id="filterSection" class="row mb-3" style="display: none;">
            <div class="col-md-6 offset-md-3">
                <div class="row">
                    <div class="col-md-6">
                        <select id="materiaInput" class="form-select w-100 filter-select" aria-label="Seleziona materia" onchange="applyFilters()">
                            <option value="">{{ __('Tutte le materie') }}</option>
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject }}">{{ $subject }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <input type="date" id="dataInput" class="form-control filter-select" onchange="applyFilters()">
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabella con le pratiche  -->
        <div class="row mt-3"> <!-- Aggiunto mt-3 qui -->
            <div class="col-md-12">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>{{ __('Titolo') }}</th>
                            <th>{{ __('Data') }}</th>
                            <th>{{ __('Materia') }}</th>
                            <th>{{ __('Dettagli') }}</th>
                            <th>{{ __('Statistica') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($practices as $practice)
                            <tr>
                                <td>{{ $practice->title }}</td>
                                <td>{{ $practice->practice_date }}</td>
                                <td>{{ $practice->subject }}</td>
                                <td>
                                    <a href="{{ route('view-delivered', ['practice' => $practice]) }}" class="btn btn-primary"><i class="fas fa-search"></i></a>
                                </td>
                                <td>
                                    <a href="{{ route('stats', ['practice' => $practice]) }}" class="btn btn-success"><i class="fas fa-chart-bar"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    function toggleFilters() {
        var filterSection = document.getElementById("filterSection");
        var resetFiltersBtn = document.getElementById("resetFiltersBtn");
        
        if (filterSection.style.display === "none" || filterSection.style.display === "") {
            filterSection.style.display = "flex";
            resetFiltersBtn.style.display = "inline-block"; // Mostra il bottone di resetta filtri
        } else {
            filterSection.style.display = "none";
            resetFiltersBtn.style.display = "none"; // Nasconde il bottone di resetta filtri
        }
    }

    function applyFilters() {
        var materiaInput = document.getElementById("materiaInput").value;
        var dataInput = document.getElementById("dataInput").value;

        var practices = document.querySelectorAll("tbody tr");
        practices.forEach(function(practice) {
            var practiceMateria = practice.children[2].textContent;
            var practiceDate = practice.children[1].textContent;

            var showPractice = true;

            if (materiaInput !== "" && practiceMateria !== materiaInput) {
                showPractice = false;
            }
            if (dataInput !== "" && practiceDate !== dataInput) {
                showPractice = false;
            }

            if (showPractice) {
                practice.style.display = "table-row";
            } else {
                practice.style.display = "none";
            }
        });
    }

    function resetFilters() {
        document.getElementById("materiaInput").value = "";
        document.getElementById("dataInput").value = "";
        applyFilters(); // Resetta immediatamente dopo il reset
    }
</script>
