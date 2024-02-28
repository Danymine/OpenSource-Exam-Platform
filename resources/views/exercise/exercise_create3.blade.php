<x-app-layout>
  
    <x-slot name="header">
        <h4>
            {{ __('Crea Esercizio') }}
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
    </div>  

    <div class="container p-4 rounded" style="background-color: #fff; box-shadow: 0.15rem 0.25rem 0 rgb(33 40 50 / 15%); border: 1px solid rgba(0,0,0,.125);">
        <div class="row">
            <div class="col-md-6 text-center">
                <!-- Immagine -->
                <img src="/system/Traguardo.jfif" alt="Traguardo Quasi Raggiunto" class="img-fluid" style="height: 400px;">
            </div>
            <div class="col-md-6 mt-4">
                <div class="small-container">
                    <div class="circle-container">
                        <div class="circle"><i class="fas fa-check-circle text-success"></i></div>
                        <div class="circle"><i class="fas fa-check-circle text-success"></i></div>
                        <div class="circle active-circl">3</div>
                        <div class="connector-line"></div>
                        <div class="connector-line"></div>
                    </div>
                </div>
                <form method="POST" action="{{ route('save') }}" class="mt-4">
                    @csrf
                    <div class="form-group">
                        <label for="difficulty">{{ __('Difficoltà') }}:</label>
                        <select class="form-control p-2" name="difficulty" required>
                            <option value="Bassa" {{ session()->has('exercise_step1') && array_key_exists('difficulty', session('exercise_step1')) && session('exercise_step1')['difficulty'] == 'Bassa' ? 'selected' : '' }}>{{ __('Bassa') }}</option>
                            <option value="Media" {{ session()->has('exercise_step1') && array_key_exists('difficulty', session('exercise_step1')) && session('exercise_step1')['difficulty'] == 'Media' ? 'selected' : '' }}>{{ __('Media') }}</option>
                            <option value="Alta" {{ session()->has('exercise_step1') && array_key_exists('difficulty', session('exercise_step1')) && session('exercise_step1')['difficulty'] == 'Alta' ? 'selected' : '' }}>{{ __('Alta') }}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="score">{{ __('Punteggio') }}:</label>
                        <input type="number" class="form-control" id="score" name="score" required placeholder="{{ __('Inserisci il punteggio') }}">
                    </div>
            </div>
        </div>

        <!-- Bottoni -->
        <div class="row mt-4">
            <div class="col-md-6">
                <a class="btn btn-danger text-white" id="annulla" href="{{ route('exit_process_create') }}">{{ __('Annulla') }}</a>
            </div>
            <div class="col-md-6 text-right">
                <a class="btn btn-info text-white" id="back" href="{{ route('exercise.step2') }}">{{ __('Indietro') }}</a>
                <button type="submit" class="btn btn-primary ml-2" id="avanti">{{ __('Avanti') }}</button>
            </div>
        </div>
        </form>
    </div>

</x-app-layout>