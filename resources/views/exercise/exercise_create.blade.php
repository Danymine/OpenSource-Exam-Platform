<x-app-layout>
  
    <x-slot name="header">
        <h4>
            {{ __('Crea Esercizio') }}
        </h4>
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
    </div>

    <div class="container p-4 rounded" style="background-color: #fff; box-shadow: 0.15rem 0.25rem 0 rgb(33 40 50 / 15%); border: 1px solid rgba(0,0,0,.125);">

        <div class="small-container">
            <div class="circle-container">
                <div class="circle active-circle">1</div>
                <div class="circle">2</div>
                <div class="circle">3</div>
                <div class="connector-line"></div>
                <div class="connector-line"></div>
            </div>
        </div>

        <form method="POST" action="{{ route('create_step_1') }}">
            @csrf
            <div class="row mt-4">
                <div class="col-md-6">
                        <div class="form-group">
                            <label for="titolo">{{ __('Titolo') }}:</label>
                            <input type="text" class="form-control" id="titolo" placeholder="{{ __('Inserisci il Titolo') }}" name="name" required minlength="5" maxlength="30" value="{{ session()->has('exercise_step1') ? session('exercise_step1')['name'] : '' }}">
                        </div>
                        <div class="form-group">
                            <label for="materia">{{ __('Materia') }}:</label>
                            <input type="text" class="form-control" id="materia" placeholder="{{ __('Inserisci la Materia') }}" name="subject" required minlength="5" maxlength="30" value="{{ session()->has('exercise_step1') ? session('exercise_step1')['subject'] : '' }}">
                        </div>
                </div>
                <div class="col-md-6 p-3">
                    <div class="form-group">
                        <label>{{ __('Tipo di esercizio') }}:</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="type" id="esercizio1" value="Risposta Aperta" required 
                            {{ (session('exercise_step1') && session('exercise_step1')['type'] === 'Risposta Aperta') || old('type') === 'Risposta Aperta' ? 'checked' : '' }}>
                            <label class="form-check-label" for="esercizio1">{{ __('Risposta Aperta') }}</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="type" id="esercizio2" value="Risposta Multipla"
                            {{ (session('exercise_step1') && session('exercise_step1')['type'] === 'Risposta Multipla') || old('type') === 'Risposta Aperta' ? 'checked' : '' }}>
                            <label class="form-check-label" for="esercizio2">{{ __('Risposta Multipla') }}</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="type" id="esercizio3" value="Vero o Falso"
                            {{ (session('exercise_step1') && session('exercise_step1')['type'] === 'Vero o Falso') || old('type') === 'Risposta Aperta' ? 'checked' : '' }}>
                            <label class="form-check-label" for="esercizio3">{{ __('Vero o Falso') }}</label>
                        </div>
                    </div>

                    <div id="selected-option-explanation">
                        <p id="risposta-aperta" style="color: black; display: none">{{ __('Creerai un esercizio con una risposta aperta, dove gli studenti possono inserire liberamente la loro risposta.') }}</p>
                        <p id="risposta-multipla" style="color: black; display: none">{{ __('Creerai un esercizio a risposta chiusa, dove gli studenti possono selezionare una risposta tra le opzioni fornite.') }}</p>
                        <p id="vero-falso" style="color: black; display: none">{{ __('Creerai un esercizio a vero o falso, dove gli studenti devono scegliere se una dichiarazione è vera o falsa.') }}</p>

                    </div>
                </div>
            </div>

            <!-- Bottoni -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <a class="btn btn-danger text-white" id="annulla" href="{{ route('exit_process_create') }}">{{ __('Annulla') }}</a>
                </div>
                <div class="col-md-6 text-right">
                    <button type="button" class="btn btn-info">{{ __('Indietro') }}</button>
                    <button type="submit" class="btn btn-primary ml-2" id="avanti">{{ __('Avanti') }}</button>
                </div>
            </div>

        </form>
    </div>

    <script src="/js/createExercise.js"></script>

</x-app-layout>
