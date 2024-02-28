<x-app-layout>
    <x-slot name="header">
        <div class="row">
            <div class="col-6">
                <h4 class="text-2xl font-bold text-black mb-4">{{ $practice->title }}</h4>
            </div>
            <div class="col-6 text-right">
                @if( $practice->type == "Exam")
                    <a href="{{ route('exam.index') }}" class="btn btn-info">{{ __('Torna Indietro') }}</a>
                @else
                    <a href="{{ route('practices.index') }}" class="btn btn-info">{{ __('Torna Indietro') }}</a>
                @endif
            </div>
        </div>
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

    <div class="container p-4 rounded" style="background-color: #fff; box-shadow: 0.15rem 0.25rem 0 rgb(33 40 50 / 15%); border: 1px solid rgba(0,0,0,.125);">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">{{ __('Dettagli') }}:</h3>
            <button class="btn btn-primary" data-toggle="modal" data-target="#editDetailsModal">{{ __('Modifica') }}</button>
        </div>
        <div class="practice-details">
            <!-- Description -->
            <p class="mb-3 text-black"><strong>{{ __('Descrizione') }}:</strong> {{ $practice->description }}</p>

            <!-- Difficulty -->
            <p class="mb-3 text-black"><strong>{{ __('Difficoltà') }}:</strong> {{ $practice->difficulty }}</p>

            <!-- Subject -->
            <p class="mb-3 text-black"><strong>{{ __('Materia') }}:</strong> {{ $practice->subject }}</p>

            <!-- Total Score -->
            <p class="mb-3 text-black"><strong>{{ __('Punteggio Totale') }}:</strong> {{ $practice->total_score }}</p>

            <!-- Feedback Enabled -->
            <p class="mb-3 text-black">
                <input type="checkbox" id="feedbackEnabled" disabled {{ $practice->feedback_enabled ? 'checked' : '' }}>
                <label for="feedbackEnabled" class="text-black">{{ __('Feedback Automatico') }}</label>
            </p>

            <!-- Randomize Questions -->
            <p class="mb-3">
                <input type="checkbox" id="randomizeQuestions" disabled {{ $practice->randomize_questions ? 'checked' : '' }}>
                <label for="randomizeQuestions" class="text-black">{{ __('Randomizzazione Domande') }}</label>
            </p>

            <!-- Creation Date -->
            <p class="mb-3 text-black"><strong>{{ __('Data di Creazione') }}:</strong> {{ $practice->created_at }}</p>

            <!-- Practice Date -->
            <p class="mb-3 text-black"><strong>{{ __('Data programmata') }}:</strong> {{ \Carbon\Carbon::parse($practice->practice_date)->format('d-m-Y') }}</p>
        </div>

        <!-- Linea di separazione -->
        <hr class="my-5">

        <div class="d-flex justify-content-between align-items-center">
            <!-- Testo "Esercizi" a sinistra -->
            <h2 class="text-xl font-bold text-black">{{ __('Esercizi') }}:</h2>

            <!-- Pulsante "Aggiungi" a destra -->
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                {{ __('Aggiungi esercizi') }}
            </button>
        </div>
        <hr class="my-2">

        @foreach($practice->exercises as $exercise)
            <div class="exercise border-b-2 border-gray-200 pb-3 mb-4">
                <!-- Exercise Name -->
                <div class="row">
                    <div class="col-9">
                        <!-- Exercise Name -->
                        <h4 class="text-lg font-semibold text-black">{{ $exercise->name }}</h4>
                    </div>
                    <div class="col-3 d-flex justify-content-end">
                        <!-- Rimuovi esercizio Button -->
                        <form action="{{ route('practices.remove_exercise', ['practice' => $practice, 'exercise' => $exercise]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">{{ __('Rimuovi esercizio') }}</button>
                        </form>
                    </div>
                </div>

                <!-- Question -->
                <p class="mb-1 text-black "><strong>{{ __('Domanda') }}:</strong> {{ $exercise->question }}</p>

                <!-- Score -->
                <p class="mb-1 text-black"><strong>{{ __('Punteggio') }}:</strong> {{ $exercise->score }}</p>
            </div>
            <hr stile="border-top: 1px solid #000000; width: 90%;" />
        @endforeach
    </div>

    <!-- Modal -->
    <div class="modal fade" id="editDetailsModal" tabindex="-1" role="dialog" aria-labelledby="editDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editDetailsModalLabel">{{ __('Modifica Dettagli') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('practices.update.details') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="text" class="form-control" id="id" value="{{ $practice->id }}" name="id" style="display: none;">
                        <!-- Titolo -->
                        <div class="form-group">
                            <label for="title">{{ __('Titolo') }}</label>
                            <input type="text" class="form-control" id="title" value="{{ $practice->title }}" name="title">
                        </div>
                        <div class="form-group">
                        <label for="difficulty">{{ __('Difficoltà') }}:</label>
                            <select class="form-control p-2" name="difficulty" required>
                                <option value="Bassa" {{ $practice->difficulty == 'Bassa' ? 'selected' : '' }}>{{ __('Bassa') }}</option>
                                <option value="Media" {{ $practice->difficulty== 'Media' ? 'selected' : '' }}>{{ __('Media') }}</option>
                                <option value="Alta" {{ $practice->difficulty == 'Alta' ? 'selected' : '' }}>{{ __('Alta') }}</option>
                            </select>           
                        </div>
                        <!-- Descrizione -->
                        <div class="form-group">
                            <label for="description">{{ __('Descrizione') }}</label>
                            <textarea class="form-control" id="description" rows="3" name="description">{{ $practice->description }}</textarea>
                        </div>
                        <!-- Materia -->
                        <div class="form-group">
                            <label for="subject">{{ __('Materia') }}</label>
                            <input type="text" class="form-control" id="subject" name="subject" value="{{ $practice->subject }}">
                        </div>
                        @php $cond = true; @endphp
                        @foreach($practice->exercises as $exercise)
                            @if($exercise->type == "Risposta Aperta")
                                @php $cond = false; @endphp
                                @break
                            @endif
                        @endforeach
                        @if( $cond == true )
                            <!-- Feedback Automatico -->
                            <div class="form-group">
                                <label>{{ __('Feedback Automatico') }}</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="feedback_enabled" id="feedbackYes" value="1" {{ $practice->feedback_enabled ? 'checked' : '' }}>
                                    <label class="form-check-label" for="feedbackYes">
                                        {{ __('Si') }}
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="feedback_enabled" id="feedbackNo" value="0" {{ !$practice->feedback_enabled ? 'checked' : '' }}>
                                    <label class="form-check-label" for="feedbackNo">
                                        {{ __('No') }}
                                    </label>
                                </div>
                            </div>
                        @endif
                        <!-- Randomizzazione -->
                        <div class="form-group">
                            <label>{{ __('Randomizzazione') }}</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="randomize_questions" id="randomizeYes" value="1" {{ $practice->randomize_questions ? 'checked' : '' }}>
                                <label class="form-check-label" for="randomizeYes">
                                    {{ __('Si') }}
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="randomize_questions" id="randomizeNo" value="0" {{ !$practice->randomize_questions ? 'checked' : '' }}>
                                <label class="form-check-label" for="randomizeNo">
                                    {{ __('No') }}
                                </label>
                            </div>
                        </div>
                        <!-- Durata -->
                        <div class="form-group">
                            <label for="duration">{{ __('Durata') }}</label>
                            <input type="text" class="form-control" id="duration" name="time" value="{{ $practice->time }}">
                        </div>
                        <!-- Data Programmata -->
                        <div class="form-group">
                            <label for="practiceDate">{{ __('Data Programmata') }}</label>
                            <input type="date" class="form-control" id="practiceDate" name="practice_date" value="{{ $practice->practice_date }}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Chiudi') }}</button>
                        <!-- Aggiungi un bottone per salvare le modifiche -->
                        <button type="submit" class="btn btn-primary">{{ __('Salva Modifiche') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('Seleziona gli esercizi da aggiungere') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('practices.add_exercises', ['practice' => $practice]) }}"  method="POST">
                        @csrf

                        @foreach($availableExercises as $exercise)
                            @if (!$practice->exercises->contains($exercise) && $exercise->subject == $practice->subject)
                                <div class="form-check p-3">
                                    <input class="form-check-input" type="checkbox" name="exercises[]" value="{{ $exercise->id }}" id="exercise{{ $exercise->id }}">
                                    <label class="form-check-label" for="exercise{{ $exercise->id }}">
                                        <strong>{{ $exercise->question }}</strong><br/>{{ __('Tipo') }}: {{ $exercise->type }},<br/>{{ __('Punteggio') }}: {{ $exercise->score }}
                                    </label>
                                </div>
                            @endif
                        @endforeach
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Chiudi') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Conferma') }}</button>
                </div>
                    </form>
            </div>
        </div>
    </div>
</x-app-layout>
