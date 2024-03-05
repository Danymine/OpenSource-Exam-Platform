<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="font-semibold text-xl leading-tight">
                @if( Auth::user()->roles == "Teacher" &&  $delivered->practice->public == 0) {{ __('Correzione') }} @else {{ $delivered->practice->title }} @endif
            </h4>
            <div>
                <a href="{{ route('view-delivered', ['practice' => $delivered->practice]) }}" class="btn btn-info">{{ __('Torna Indietro') }}</a>
                <a href="{{ route('download-details-delivered', ['delivered' => $delivered]) }}" class="btn btn-sm btn-warning" title="{{ __('Stampa consegna') }}" style="height: 38px; width: 40px; text-align: center; padding: 0;">
                    <i class="fas fa-print" style="line-height: 38px;"></i>
                </a>
            </div>
        </div>
        <hr style="border-top: 1px solid #0000004a width: 90%;" />
    </x-slot>


    <!--Dato che è un docente e che i risultati del test non sono stati ancora pubblicati lasciamo sempre la possibilità di modificare la valutazione-->
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

    <div class="container p-4 rounded" style="background-color: #fff; box-shadow: 0.15rem 0.25rem 0 rgb(33 40 50 / 15%); border: 1px solid rgba(0,0,0,.125);" >
        <div class="row">
            <div class="col-md-6">
                <!-- Domanda 1 a sinistra -->
                <h4 id="cont">{{ __('Domanda') }}</h4>
            </div>
            <div class="col-md-6 text-right">
                <span class="small"><i>{{ $delivered->user->name }} {{ $delivered->user->first_name }}</i></h5>
                <span id="score"></span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <!-- Esercizio Question - Risposta -->
                <form method="POST" action="{{ route('store-valutation', ['delivered' => $delivered]) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="exercise mb-4">
                        <h5>{{ $exercises[0]->question }}</h5>
                        <h6 class="mb-3">{{ $response[$exercises[0]->id][0]["response"] }}</h6>
                        @if( $exercises[0]->type == "Risposta Aperta")
                        <div class="form-group">
                            <label for="valutation">{{ __('Valutazione', ['score' => $exercises[0]->score]) }} </label>
                            <input type="number" class="form-control mb-2" id="valutation" name='correct[{{$response[$exercises[0]->id][0]["id"]}}]' min="1" max="{{ $exercises[0]->score }}" placeholder="{{ __('Assegna un punteggio') }}" value="{{ $response[$exercises[0]->id][0]['score_assign'] }}" >
                        </div>
                        <div class="form-group">
                            <label for="note">{{ __('Note') }}</label>
                            <textarea class="form-control mb-2" id="note" name='note[{{ $response[$exercises[0]->id][0]["id"] }}]' rows="2" placeholder="{{ __('Note') }}"> {{ $response[$exercises[0]->id][0]['note'] }} </textarea>
                        </div>
                        @else
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name='correct[{{ $response[$exercises[0]->id][0]["id"]}}]' id="correct-yes" value="{{ $exercises[0]->score }}" {{ isset($response[$exercises[0]->id][0]["score_assign"]) && $response[$exercises[0]->id][0]["score_assign"] == $exercises[0]->score ? 'checked' : '' }} >
                                <label class="form-check-label" for="correct-yes" style="margin-top: 0px" > {{ __('Corretta') }} </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name='correct[{{$response[$exercises[0]->id][0]["id"]}}]' id="correct-no" value="0" {{ isset($response[$exercises[0]->id][0]["score_assign"]) && $response[$exercises[0]->id][0]["score_assign"] != $exercises[0]->score ? 'checked' : '' }}>
                                <label class="form-check-label" for="correct-no" style="margin-top: 0px" > {{ __('Sbagliata') }} </label>
                            </div>
                            <textarea class="form-control mb-2" name='note[{{$response[$exercises[0]->id][0]["id"]}}]' rows="2" placeholder="{{ __('Note') }}"> {{ $response[$exercises[0]->id][0]['note'] }} </textarea>
                        @endif
                    </div>
                    @php $firstExercise = true; @endphp
                    @foreach( $exercises as $exercise )
                        @if(!$firstExercise)

                            <div class="exercise mb-4 hide-total">
                                <h5>{{ $exercise->question }}</h5>
                                <h6>{{ $response[$exercise->id][0]["response"] }}</h6>
                                @if( $exercise->type == "Risposta Aperta")
                                <div class="form-group">
                                    <label for="valutation">{{ __('Valutazione', ['score' => $exercise->score]) }} </label>
                                    <input type="number" class="form-control mb-2" id="valutation" name='correct[{{$response[$exercise->id][0]["id"]}}]' min="1" max="{{ $exercise->score }}" placeholder="{{ __('Assegna un punteggio') }}" value="{{ $response[$exercise->id][0]['score_assign'] }}">
                                </div>
                                <div class="form-group">
                                    <label for="note">{{ __('Note') }}</label>
                                    <textarea class="form-control mb-2" id="note" name='note[{{ $response[$exercise->id][0]["id"] }}]' rows="2" placeholder="{{ __('Note') }}"> {{ $response[$exercise->id][0]['note'] }} </textarea>
                                </div>
                                @else
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name='correct[{{$response[$exercise->id][0]["id"]}}]' id="correct-yes" value="{{ $exercise->score }}" {{ isset($response[$exercise->id][0]["score_assign"]) && $response[$exercise->id][0]["score_assign"] == $exercise->score ? 'checked' : '' }} >
                                        <label class="form-check-label" for="correct-yes"> {{ __('Corretta') }} </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name='correct[{{ $response[$exercise->id][0]["id"]}}]' id="correct-no" value="0" {{ isset($response[$exercise->id][0]["score_assign"]) && $response[$exercise->id][0]["score_assign"] != $exercise->score ? 'checked' : '' }}>
                                        <label class="form-check-label" for="correct-no"> {{ __('Sbagliata') }} </label>
                                    </div>
                                    <textarea class="form-control mb-2" name='note[{{$response[$exercise->id][0]["id"]}}]' rows="2" placeholder="Note"> {{ $response[$exercise->id][0]['note'] }} </textarea>
                                @endif
                            </div>
                        @else
                            @php $firstExercise = false; @endphp
                        @endif
                    @endforeach

                    <div class="finish hide-total">
                        <textarea class="form-control mb-2" name="note_general" rows="2" placeholder="Note"> {{ $delivered->note }}</textarea>
                        <input class="mt-3 mb-3" type="file" accept=".pdf, image/*" name="file-correct">
                    </div>
            </div>
        </div>
        <div class="row justify-content-between">
            <div class="col-auto">
                <button id="backButton" class="btn btn-info" type="button" >{{ __('Indietro') }}</button>
            </div>
            <div class="col-auto">
                <button id="nextButton" class="btn btn-primary" type="button">{{ __('Avanti') }}</button>
                <button id="finishButton" class="btn btn-primary hide-total" type="submit">{{ __('Termina') }}</button>
            </div>
        </div>
        </form>
    </div>

    <script>
        str="{{ __('Note finali') }}";
        dmd="{{ __('Domana') }}";
    </script>

    <script src="/js/ValutaEsame.js"></script>    
</x-app-layout>


