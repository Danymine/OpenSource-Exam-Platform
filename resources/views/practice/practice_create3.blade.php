<x-app-layout>
  
    <x-slot name="header">
        <h4>
            {{ __('Crea Esercitazione') }}
        </h4>
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

        <div class="small-container">
            <div class="circle-container">
                <div class="circle"><i class="fas fa-check-circle text-success"></i></div>
                <div class="circle"><i class="fas fa-check-circle text-success"></i></div>
                <div class="circle active-circle">3</div>
                <div class="connector-line"></div>
                <div class="connector-line"></div>
            </div>
        </div>
        <h2>{{ __('Ultimi Dettagli') }}</h2>
        <form method="POST" action="{{ route('save_practice') }}">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="data_programmazione" class="form-label">{{ __('Data di programmazione') }}</label>
                        <input type="date" class="form-control" id="data_programmazione" name="practice_date" required>
                        <span id="error_date" style="color: red;"></span>
                    </div>
                    <div class="mb-3">
                        <label for="difficulty">{{ __('Difficoltà') }}:</label>
                        <select class="form-control p-2" name="difficulty" required>
                            <option value="Bassa" {{ session()->has('exercise_step1') && array_key_exists('difficulty', session('exercise_step1')) && session('exercise_step1')['difficulty'] == 'Bassa' ? 'selected' : '' }}>Bassa</option>
                            <option value="Media" {{ session()->has('exercise_step1') && array_key_exists('difficulty', session('exercise_step1')) && session('exercise_step1')['difficulty'] == 'Media' ? 'selected' : '' }}>Media</option>
                            <option value="Alta" {{ session()->has('exercise_step1') && array_key_exists('difficulty', session('exercise_step1')) && session('exercise_step1')['difficulty'] == 'Alta' ? 'selected' : '' }}>Alta</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        @php
                            $session = session('exame_step1')["exercise"];
                            $bool = true;
                            for( $i = 0; $i < count($session); $i++ ){

                                $exercise = DB::table('exercises')->where('id', $session[$i])->first();
                                if( $exercise->type == "Risposta Aperta" ){
                                    
                                    $bool = false;
                                    break;
                                }
                            }
                        @endphp
                        @if( $bool == true )
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
                        @endif
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <label class="form-check-label" for="feedback">
                                {{ __('Vuoi abilitare la randomizzazione delle domande?') }}
                            </label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="randomize_questions" id="randomize_questions_si" value="1" required>
                                <label class="form-check-label" for="randomize_questions_si">{{ __('Sì') }}</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="randomize_questions" id="randomize_questions_no" value="0">
                                <label class="form-check-label" for="randomize_questions_no">{{ __('No') }}</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottoni -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <a class="btn btn-danger text-white" id="annulla" href="{{ route('exit_exame_process') }}">{{ __('Annulla') }}</a>
                </div>
                <div class="col-md-6 text-right">
                    <a class="btn btn-info text-white" id="back" href="{{ route('exame_step2') }}">{{ __('Indietro') }}</a>
                    <button type="submit" class="btn btn-primary ml-2" id="avanti">{{ __('Avanti') }}</button>
                </div>
            </div>

        </form>
    </div>

</x-app-layout>

<script>
    prova = "{{ __('La data deve essere successiva a quella attuale.') }}";

    document.addEventListener("DOMContentLoaded", function() {
        const dataInput = document.getElementById("data_programmazione");
        const errorSpan = document.getElementById("error_date");

        dataInput.addEventListener("change", function() {
            const dataInserita = new Date(dataInput.value).toLocaleDateString();
            const dataAttuale = new Date().toLocaleDateString();

            if (dataInserita < dataAttuale) {
                errorSpan.textContent = prova;
            } else {
                errorSpan.textContent = "";
            }
        });
    });
</script>