<x-app-layout>
  <div class="container">
    <x-slot name="header">
      <h4>{{ __('Biblioteca')}}</h4>
      <hr style="border-top: 1px solid #0000004a width: 90%;" />
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
      <button class="btn btn-secondary" style="display: none;" onclick="resetFilters()"><i class="fas fa-times"></i> {{ __('Cancella Filtri') }}</button>
      <button class="btn btn-info" onclick="toggleFilterModal()"><i class="fas fa-filter"></i> {{ __('Filtri') }}</button>
      <button class="btn btn-primary" onclick="location.href='{{ route('exercise.step1') }}'"><i class="fas fa-plus"></i> {{ __('Crea') }} </button>
    </div>

    <!-- Aggiunta dei filtri sopra la tabella -->
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
          <select>
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
      <table id="exercise-table" class="table table-bordered table-striped">
        <colgroup>
          <col style="width: 45%;">
          <col style="width: 20%;">
          <col style="width: 15%;">
          <col style="width: 15%;">
          <col style="width: 5%;">
        </colgroup>
        <thead>
          <tr>
            <!-- Column -->
            <th onclick="sortTable(0)">{{ __('Nome') }} <i class="fas fa-chevron-down"></i></th>
            <th onclick="sortTable(1)">{{ __('Tipo') }} <i class="fas fa-chevron-down"></i></th>
            <th onclick="sortTable(2)">{{ __('Difficoltà') }} <i class="fas fa-chevron-down"></i></th>
            <th onclick="sortTable(3)">{{ __('Materia') }} <i class="fas fa-chevron-down"></i></th>
            <th>{{ __('Azioni') }}</th>
          </tr>
        </thead>
        <tbody>
            @foreach ($exercises as $exercise)
              <tr data-id="{{ $exercise->id }}">
                <!-- Data -->
                <td class="align-middle" >{{ $exercise->name }}</td>
                <td class="align-middle" >{{ $exercise->type }}</td>
                <td class="align-middle" >{{ $exercise->difficulty }}</td>
                <td class="align-middle" >{{ $exercise->subject }}</td>
                <!-- Function -->
                <td class="align-middle">
                  <div class="d-flex justify-content-between">
                    <a class="btn btn-info details-button mr-2" onclick="showDetails('{{ $exercise->id }}')"><i class="fas fa-search"></i></a>
                    <a class="btn btn-warning edit-button mr-2" onclick="editExercise('{{ $exercise->id }}')"><i class="fas fa-pencil-alt"></i></a>
                    <form action="{{ route('deleteExercise', ['exercise' => $exercise]) }}" method="POST" onsubmit="return confirm('Sei sicuro di voler eliminare questo esercizio?');">
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

  <div class="modal fade" id="edit-dialog" tabindex="-1" aria-labelledby="edit-dialog-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="edit-dialog-title">{{ __('Modifica Esercizio') }}</h4>
                <button type="button" class="btn-danger rounded" data-bs-dismiss="modal" aria-label="Close" onclick="cancelEditExercise()">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="edit-exercise-form" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3" style="display: none;">
                      <input type="number" name="id" class="form-control mb-3" id="primary">
                    </div>

                    <div class="mb-3">
                      <label for="edit-name" class="form-label">{{ __('Nome') }}:</label>
                      <input type="text" id="edit-name" name="name" class="form-control mb-3" required autofocus>
                    </div>

                    <div class="mb-3">
                      <label for="edit-question" class="form-label">{{ __('Domanda') }}:</label>
                      <textarea id="edit-question" name="question" class="form-control mb-3" rows="3" required></textarea>
                    </div>

                    <div class="mb-3">
                      <label for="score" class="form-label">{{ __('Punteggio') }}:</label>
                      <input type="text" id="score" name="score" class="form-control mb-3" required>
                    </div>
                    
                    <div class="mb-3">
                      <label for="difficulty" class="form-label">{{ __('Difficoltà') }}:</label><br/>
                      <select id="difficulty" name="difficulty" class="form-select form-select-lg mb-3 rounded p-2" aria-label=".form-select-lg example" required>
                          <option value="Bassa">{{ __('Bassa') }}</option>
                          <option value="Media">{{ __('Media') }}</option>
                          <option value="Alta">{{ __('Alta') }}</option>
                      </select>
                    </div>
                    
                    <div class="mb-3">
                      <label for="subject" class="form-label">{{ __('Materia') }}:</label>
                      <input type="text" id="subject" name="subject" class="form-control mb-3">
                    </div>

                    <div class="mb-3">
                      <label for="type" class="form-label">{{ __('Tipo') }}:</label><br/>
                      <select id="type" name="type" class="form-select form-select-lg mb-3 rounded p-2" aria-label=".form-select-lg example">
                          <option value="Risposta Aperta" id="type_open">{{ __('Risposta Aperta') }}</option>
                          <option value="Risposta Multipla" id="type_closed" >{{ __('Risposta Multipla') }}</option>
                          <option value="Vero o Falso" id="type_true" >{{ __('Vero o Falso') }}</option>
                      </select>
                    </div>

                    <div class="mb-3" id="multiple_choice_container">

                    </div>

                    <div class="mb-3" id="true_false_container">

                    </div>

                    <button type="button" class="btn btn-primary" onclick="updateExercise()">{{ __('Aggiorna Esercizio') }}</button>
                    <button type="button" class="btn btn-danger rounded" data-bs-dismiss="modal" aria-label="Close" onclick="cancelEditExercise()">{{ __('Annulla Modifiche') }}</button>
                </form>
            </div>
        </div>
    </div>
  </div>

  <script>
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
        document.getElementById('typeInput').value = '';
        document.getElementById('difficoltaInput').value = '';
        applyFilters(); // Applica i filtri resettati
    }

    function applyFilters() {
        var materia = document.getElementById('materiaInput').value.toLowerCase();
        var tipo = document.getElementById('typeInput').value.toLowerCase();
        var difficolta = document.getElementById('difficoltaInput').value.toLowerCase();
        var table = document.getElementById('exercise-table');
        var rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

        for (var i = 0; i < rows.length; i++) {
            var materiaCell = rows[i].getElementsByTagName('td')[3].textContent.toLowerCase();
            var tipoCell = rows[i].getElementsByTagName('td')[1].textContent.toLowerCase();
            var difficoltaCell = rows[i].getElementsByTagName('td')[2].textContent.toLowerCase();

            var showRow = true;

            if (materia !== '' && !materiaCell.includes(materia)) {
                showRow = false;
            }

            if (tipo !== '' && !tipoCell.includes(tipo)) {
                showRow = false;
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
  </script>

  <script>
    var exercises = @json($exercises);
    var translations = {
      'translate': {
        "Domanda": "{{ __('Domanda') }}",
        "Difficoltà": "{{ __('Difficoltà') }}",
        "Materia": "{{ __('Materia') }}",
        "Tipo": "{{ __('Tipo') }}",
        "Risposta Corretta": "{{ __('Risposta Corretta') }}",
        "Opzione A": "{{ __('Opzione A') }}",
        "Opzione B": "{{ __('Opzione B') }}",
        "Opzione C": "{{ __('Opzione C') }}",
        "Opzione D": "{{ __('Opzione D') }}",
        "Spiegazione": "{{ __('Spiegazione') }}",
        "Vero": "{{ __('Vero') }}",
        "Falso": "{{ __('Falso') }}",
        "Opzione": "{{ __('Opzione') }}",
        "Inserisci l'opzione": '{{ __("Opzione") }}',
        "Inserisci la spiegazione": "{{ __('Inserisci la spiegazione') }}"
      }
    };
  </script>
  <script src="/js/tableSorting.js"></script>

</x-app-layout>