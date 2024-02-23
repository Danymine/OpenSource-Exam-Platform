<x-app-layout>
  
  <x-slot name="header">
    <h4>
        {{ __('Biblioteca') }}
    </h4>
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
  
  <div class="container">
    <div class="text-right mb-3">
        <button class="btn btn-primary" onclick="location.href='{{ route('exercise.step1') }}'"><i class="fas fa-plus"></i> {{ __('Aggiungi') }} </button>
    </div>

    <div class="table-responsive">
        <table id="exercise-table" class="table">
            <thead>
                <tr>
                    <!-- Column -->
                    <th onclick="sortTable(0)">{{ __('Nome') }} <i class="fas fa-chevron-down"></i></th>
                    <th onclick="sortTable(1)">{{ __('Tipo') }} <i class="fas fa-chevron-down"></i></th>
                    <th onclick="sortTable(2)">{{ __('Difficoltà') }} <i class="fas fa-chevron-down"></i></th>
                    <th onclick="sortTable(3)">{{ __('Materia') }} <i class="fas fa-chevron-down"></i></th>
                    <th>{{ __('Dettagli') }}</th>
                    <th>{{ __('Modifica') }}</th>
                    <th>{{ __('Elimina') }}</th>
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
                    <td><a class="btn btn-info details-button" onclick="showDetails('{{ $exercise->id }}')"><i class="fas fa-search"></i></a></td>
                    <td><a class="btn btn-warning edit-button" onclick="editExercise('{{ $exercise->id }}')"><i class="fas fa-pencil-alt"></i></a></td>
                    <td><a class="btn btn-danger" href="{{ route('deleteExercise', ['exercise' => $exercise]) }}" onclick="return confirm('Sei sicuro di voler eliminare questo esercizio?');"><i class="fas fa-trash-alt"></i></a></td>
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
                          <option value="Risposta Multipla" id="type_closed" >{{ __('Risposta Multipla<') }}/option>
                          <option value="Vero o Falso" id="type_true" >{{ __('Vero o Falso') }}</option>
                      </select>
                    </div>

                    <div class="mb-3" id="multiple_choice_container">

                    </div>

                    <div class="mb-3" id="true_false_container">

                    </div>

                    <button type="button" class="btn btn-primary" onclick="updateExercise()">{{ _('Aggiorna Esercizio') }}</button>
                    <button type="button" class="btn btn-danger rounded" data-bs-dismiss="modal" aria-label="Close" onclick="cancelEditExercise()">{{ __('Annulla Modifiche') }}</button>
                </form>
            </div>
        </div>
    </div>
  </div>


  <script>
    var exercises = @json($exercises);
  </script>
  <script src="/js/tableSorting.js"></script>

</x-app-layout>
