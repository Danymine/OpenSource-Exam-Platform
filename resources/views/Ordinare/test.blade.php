<x-app-layout>
    <div class="container mt-5 p-4" style="background-color: #fff;">
        <h2 class="mb-4">{{ $test->title }}</h2>
        <form action="{{ route('pratices.send') }}" method="post">
            @csrf
            <input type="hidden" name="id_practices" value="{{ $test->id }}">
            @foreach($exercises as $exercise)
                <div class="card mb-3">
                    <div class="card-body">
                        <input type="hidden" name="id[]" value="{{ $exercise['id'] }}">
                        <h6 class="card-text text-black"><i>{{ $exercise["question"] }} <span class="badge badge-secondary">Score: {{ $exercise['score'] }}</span></i></h6>
                        @if($exercise["type"] == "Risposta Aperta")
                            <textarea class="form-control" name="risposte[{{ $exercise['id'] }}]" rows="2"></textarea>
                        @elseif( $exercise["type"] == "Risposta Multipla")
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="risposte[{{ $exercise['id'] }}]" id="option1_{{ $exercise['id'] }}" value="{{ $exercise['option_1'] }}">
                                <label class="form-check-label" for="option1_{{ $exercise['id'] }}">{{ $exercise['option_1'] }}</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="risposte[{{ $exercise['id'] }}]" id="option2_{{ $exercise['id'] }}" value="{{ $exercise['option_2'] }}">
                                <label class="form-check-label" for="option2_{{ $exercise['id'] }}">{{ $exercise['option_2'] }}</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="risposte[{{ $exercise['id'] }}]" id="option3_{{ $exercise['id'] }}" value="{{ $exercise['option_3'] }}">
                                <label class="form-check-label" for="option3_{{ $exercise['id'] }}">{{ $exercise['option_3'] }}</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="risposte[{{ $exercise['id'] }}]" id="option4_{{ $exercise['id'] }}" value="{{ $exercise['option_4'] }}">
                                <label class="form-check-label" for="option4_{{ $exercise['id'] }}">{{ $exercise['option_4'] }}</label>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
            <button type="submit" class="btn btn-primary">Invia Risposte</button>
        </form>
    </div>
</x-app-layout>