<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="font-semibold text-xl leading-tight">
                {{ __('Creazione Automatica') }}
            </h3>
            <div>
                <a href="{{ back()->getTargetUrl() }}" class="btn btn-info">{{ __('Torna Indietro') }}</a>   
            </div>
        </div>
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
    
    <div class="container p-4 rounded" style="background-color: #fff; box-shadow: 0.15rem 0.25rem 0 rgb(33 40 50 / 15%); border: 1px solid rgba(0,0,0,.125);">
        <form method="POST" action="{{ route('save_automation') }}">
            @csrf

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="title">{{ __('Titolo') }}</label>
                        <input type="text" class="form-control" id="title" name="title" required value="{{ old('title') }}" placeholder="{{ __('Inserisci il Titolo') }}">
                    </div>
                    <div class="form-group">
                        <label for="description">{{ __('Descrizione') }}</label>
                        <textarea class="form-control" id="description" name="description" required rows="3" placeholder="{{ __('Inserisci una Descrizione') }}">{{ old('description') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="difficulty">{{ __('Difficoltà') }}</label>
                        <select class="form-control p-2" id="difficulty" name="difficulty" required>
                            <option value="Bassa">{{ __('Bassa') }}</option>
                            <option value="Media">{{ __('Media') }}</option>
                            <option value="Alta">{{ __('Alta') }}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="subject">{{ __('Materia') }}</label>
                        <input type="text" class="form-control" id="subject" name="subject" value="{{ old('subject') }}" required placeholder="{{ __('Inserisci la Materia') }}">
                    </div>
                    <div class="form-group">
                        <label for="total_score">{{ __('Punteggio Totale') }}</label>
                        <input type="number" class="form-control" id="total_score" name="total_score" value="{{ old('total_score') }}" required placeholder="{{ __('Inserisci un punteggio massimo') }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="practice_date">{{ __('Data programmata') }}</label>
                        <input type="date" class="form-control" id="practice_date" name="practice_date" value="{{ old('practice_date') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="type">{{ __('Tipo') }}</label>
                        <select class="form-control p-2" id="type" name="type" required>
                            <option value="Exam"> {{ __('Esame') }} </option>
                            <option value="Practice"> {{ __('Esercitazione') }} </option>
                        </select>
                    </div>
                    <div class="form-check">
                        <label class="form-check-label" for="randomize_questions">
                            {{ __("Vuoi abilitare la randomizzazione delle domande?") }}
                        </label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="randomize_questions" id="randomize_questionssi" value="1" required>
                            <label class="form-check-label" for="randomize_questions">{{ __('Sì') }}</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="randomize_questions" id="randomize_questionsno" value="0">
                            <label class="form-check-label" for="randomize_questionsno">{{ __('No') }}</label>
                        </div>
                    </div>
                    <div class="form-check">
                        <label class="form-check-label" for="feedback">
                            {{ __('Vuoi abilitare il feedback automatico?') }}
                        </label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="feedback" id="feedback_si" value="1" required>
                            <label class="form-check-label" for="feedback_si">{{ __('Sì') }}</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="feedback" id="feedback_no" value="0">
                            <label class="form-check-label" for="feedback_no">{{ __('No') }}</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary" id="crea">{{ __('Crea') }}</button>
            </div>
        </form>
    </div>

</x-app-layout>