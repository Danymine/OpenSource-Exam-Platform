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

        <div class="small-container">
            <div class="circle-container">
                <div class="circle"><i class="fas fa-check-circle text-success"></i></div>
                <div class="circle active-circl">2</div>
                <div class="circle">3</div>
                <div class="connector-line"></div>
                <div class="connector-line"></div>
            </div>
        </div>

        <form method="POST" action="{{ route('create_step_2') }}">
            @csrf
            @if(session()->has('exercise_step1'))
                @php
                    $exerciseData = session('exercise_step1');
                @endphp
                @if( $exerciseData["type"] == "Risposta Aperta" )

                    <div class="form-group">
                        <label for="domanda">{{ __('Domanda') }}:</label>
                        <textarea class="form-control" id="domanda" name="question" rows="6" required minlength="5" maxlength="255" placeholder="{{ __('Inserisci qui la domanda dell\'esercizio') }}">{{ session()->has('exercise_step1') && array_key_exists('question', session('exercise_step1')) ? session('exercise_step1')['question'] : '' }}</textarea>
                    </div>


                    <div class="form-group">
                        <label for="spiegazione">{{ __('Spiegazione') }}:</label>
                        <input type="text" class="form-control" id="spiegazione" name="explanation" maxlength="255" placeholder="{{ __('Spiegazione') }}" value="{{ session()->has('exercise_step1') && array_key_exists('explanation', session('exercise_step1')) ? session('exercise_step1')['explanation'] : '' }}">
                    </div>
                
                @elseif ( $exerciseData["type"] == "Risposta Multipla" )

                    <div class="form-group mt-4">
                        <label for="domanda">{{ __('Domanda') }}:</label>
                        <textarea class="form-control" id="domanda" name="question" rows="6" required minlength="5" maxlength="255" placeholder="{{ __('Inserisci qui la domanda dell\'esercizio') }}">{{ session()->has('exercise_step1') && array_key_exists('question', session('exercise_step1')) ? session('exercise_step1')['question'] : '' }}</textarea>
                    </div>
                    <!-- Opzioni di risposta -->
                    <label>{{ __('Opzioni di risposta') }}:</label>
                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="form-group m-0">
                                <input type="text" class="form-control mb-2" name="options[]" required maxlength="255"  placeholder="{{ __('Opzione A') }}" value="{{ session()->has('exercise_step1') && array_key_exists('option_a', session('exercise_step1')) ? session('exercise_step1')['option_a'] : '' }}">
                                <input type="text" class="form-control mb-2" name="options[]" required maxlength="255"  placeholder="{{ __('Opzione B') }}" value="{{ session()->has('exercise_step1') && array_key_exists('option_b', session('exercise_step1')) ? session('exercise_step1')['option_b'] : '' }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" class="form-control mb-2"  name="options[]" required maxlength="255" placeholder="{{ __('Opzione C') }}" value="{{ session()->has('exercise_step1') && array_key_exists('option_c', session('exercise_step1')) ? session('exercise_step1')['option_c'] : '' }}">
                                <input type="text" class="form-control mb-2"  name="options[]" required maxlength="255" placeholder="{{ __('Opzione D') }}" value="{{ session()->has('exercise_step1') && array_key_exists('option_d', session('exercise_step1')) ? session('exercise_step1')['option_d'] : '' }}">
                            </div>
                        </div>
                    </div>
                    <!-- Opzione corretta -->
                    <div class="form-group">
                        <label>{{ __('Opzione corretta') }}:</label>
                        <select class="form-control p-2" name="correct_option" required>
                            <option value="a" {{ session()->has('exercise_step1') && array_key_exists('correct_option', session('exercise_step1')) && session('exercise_step1')['correct_option'] == 'a' ? 'selected' : '' }}>A</option>
                            <option value="b" {{ session()->has('exercise_step1') && array_key_exists('correct_option', session('exercise_step1')) && session('exercise_step1')['correct_option'] == 'b' ? 'selected' : '' }}>B</option>
                            <option value="c" {{ session()->has('exercise_step1') && array_key_exists('correct_option', session('exercise_step1')) && session('exercise_step1')['correct_option'] == 'c' ? 'selected' : '' }}>C</option>
                            <option value="d" {{ session()->has('exercise_step1') && array_key_exists('correct_option', session('exercise_step1')) && session('exercise_step1')['correct_option'] == 'd' ? 'selected' : '' }}>D</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="spiegazione">{{ __('Spiegazione') }}:</label>
                        <input type="text" class="form-control" id="spiegazione" name="explanation" maxlength="255" placeholder="{{ __('Spiegazione') }}" value="{{ session()->has('exercise_step1') && array_key_exists('explanation', session('exercise_step1')) ? session('exercise_step1')['explanation'] : '' }}">
                    </div>
                
                @else

                    <div class="form-group mt-4">
                        <label for="domanda">{{ __('Domanda') }}:</label>
                        <textarea class="form-control" id="domanda" name="question" rows="6" required minlength="5" maxlength="255" placeholder="{{ __('Inserisci qui la domanda dell\'esercizio') }}">{{ session()->has('exercise_step1') && array_key_exists('question', session('exercise_step1')) ? session('exercise_step1')['question'] : '' }}</textarea>
                    </div>

                    <!-- Opzione corretta -->
                    <div class="form-group">
                        <label>{{ __('Opzione corretta') }}:</label>
                        <select class="form-control p-2" name="correct_option" required>
                            <option value="vero" {{ session()->has('exercise_step1') && array_key_exists('correct_option', session('exercise_step1')) && session('exercise_step1')['correct_option'] == 'vero' ? 'selected' : '' }}>{{ __('Vero') }}</option>
                            <option value="falso" {{ session()->has('exercise_step1') && array_key_exists('correct_option', session('exercise_step1')) && session('exercise_step1')['correct_option'] == 'falso' ? 'selected' : '' }}>{{ __('Falso') }}</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="spiegazione">{{ __('Spiegazione') }}:</label>
                        <input type="text" class="form-control" id="spiegazione" name="explanation" maxlength="255" placeholder="{{ __('Spiegazione') }}" value="{{ session()->has('exercise_step1') && array_key_exists('explanation', session('exercise_step1')) ? session('exercise_step1')['explanation'] : '' }}">
                    </div>
                @endif     

            <!-- Bottoni -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <a class="btn btn-danger text-white" id="annulla" href="{{ route('exit_process_create') }}">{{ __('Annulla') }}</a>
                </div>
                <div class="col-md-6 text-right">
                    <a class="btn btn-info text-white" id="back" href="{{ route('exercise.step1') }}">{{ __('Indietro') }}</a>
                    <button type="submit" class="btn btn-primary ml-2" id="avanti">{{ __('Avanti') }}</button>
                </div>
            </div>
        @endif

        </form>
    </div>

</x-app-layout>