<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-4">
            Correzione
        </h2>
    </x-slot>

    <div class="container mt-4">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h2 style="margin: 0;">{{ $delivered->practice->title }}</h2>
            <a href="{{ route('download-details-delivered', ['delivered' => $delivered]) }}" class="print">Stampa Test<i class="fa fa-print" style="font-size:36px"></i></a>
            @if ( $delivered->path != NULL )
                <a href="{{ route('download-correct-delivered', ['delivered' => $delivered]) }}" class="print">Stampa Correzione<i class="fa fa-print" style="font-size:36px"></i></a>
            @endif
        </div>
        <hr stile="border-top: 1px solid #000000; width: 90%;" />
        
        @if(Auth::user()->roles == "Student")
            @foreach($exercises as $exercise)
                <div class="exercise mb-4">
                    <div class="question">
                        <h3>{{ $exercise->question }}</h3>
                    </div>
                    <div class="answers">
                        <h3>{{ $response[$exercise->id]->response }}</h3>
                    </div>
                </div>
            @endforeach

            @if($delivered->practice->public == 1)
                <div class="mt-4">
                    <h4>{{ $delivered->valutation }}</h4>
                    <h5>{{ $delivered->note }}</h5>
                </div>
            @else
                <div class="mt-4">
                    <h4>I risultati non sono stati ancora pubblicati</h4>
                </div>
            @endif
        @else
            @if($delivered->practice->public == 0)
                <form action="{{ route('store-valutation') }}" method="POST">
                    @csrf
                    <input type="number" name="id_delivered" value="{{ $delivered->id }}" style="display: none">
                    @foreach($exercises as $exercise)
                        <div class="exercise mb-4">
                            <div>
                                <h3 class="question">{{ $exercise->question }}</h3>
                                @if($exercise->type == "Risposta Multipla" || $exercise->type == "Vero o Falso")
                                    <input type="radio" id="correct_{{ $exercise->id }}" name="risposta[{{ $exercise->id }}]" value="{{$exercise->pivot->custom_score }}" required>
                                    <label for="correct_{{ $exercise->id }}">Corretta</label><br>
                                    <input type="radio" id="wrong_{{ $exercise->id }}" name="risposta[{{ $exercise->id }}]" value="0" required>
                                    <label for="wrong_{{ $exercise->id }}">Sbagliata</label><br>
                                @else
                                    Assegna un punteggio (Deve essere compreso fra 0 e {{ $exercise->pivot->custom_score }}): <input type="number" name="risposta_aperta[{{ $exercise->id }}]" placeholder="Score" step="0.01" min="0" max="{{ $exercise->pivot->custom_score }}" > <br>
                                    Segna delle note sull'esercizio: <input type="text" name="note[{{ $exercise->id }}]" placeholder="Note"><br>
                                @endif
                            </div>
                            <div class="answers">
                                <h3>{{ $response[$exercise->id]->response }}</h3>
                            </div>
                        </div>
                    @endforeach
                    <input type="text" name="note_general" class="mt-4">
                    <input type="file" name="correct-file" class="mt-4"><br>
                    <input type="submit" value="Salva" class="btn btn-primary mt-4">
                    @if($errors->any())
                        <div class="mt-4">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </form>
            @else
                @foreach($exercises as $exercise)
                    <div class="exercise mb-4">
                        <div class="question">
                            <h3>{{ $exercise->question }}</h3>
                        </div>
                        <div class="answers">
                            <h3>{{ $response[$exercise->id]->response }}</h3>
                        </div>
                    </div>
                @endforeach
            @endif
        @endif
    </div>
</x-app-layout>
