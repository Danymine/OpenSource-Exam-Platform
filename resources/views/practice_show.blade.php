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
        <hr style="border-top: 1px solid #0000004a width: 90%;" />
    </x-slot>

    <div class="container p-4 rounded" style="background-color: #fff; box-shadow: 0.15rem 0.25rem 0 rgb(33 40 50 / 15%); border: 1px solid rgba(0,0,0,.125);">
        <h3 class="mb-4">{{ __('Dettagli') }}:</h3>
        <div class="practice-details">
            <!-- Description -->
            <p class="mb-3 text-black"><strong>{{ __('Descrizione') }}:</strong> {{ $practice->description }}</p>

            <!-- Difficulty -->
            <p class="mb-3 text-black"><strong>{{ __('Difficoltà') }}:</strong> {{ $practice->difficulty }}</p>
          
            <p class="mb-3 text-black">
                <strong class="text-black">{{ __('Chiave') }}:</strong>
                @if( $practice->key != NULL )
                    <!-- Key Content -->
                    <span id="generatedKey" class="text-black">{{ $practice->key }}</span>
                @else
                    <span id="generatedKey" class="text-black">{{ __('La chiave è stata eliminata.') }}</span>
                @endif
                
                <!-- Copy Key Button -->
                <button onclick="copyKey()" class="btn btn-primary ml-3">{{ __('Copia') }}</button>

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

        <h2 class="mt-5 mb-3 text-xl font-bold text-black">{{ __('Esercizi') }}:</h2>

        @foreach($practice->exercises as $exercise)
            <div class="exercise border-b-2 border-gray-200 pb-3 mb-4">
                <!-- Exercise Name -->
                <h4 class="text-lg font-semibold text-black">{{ $exercise->name }}</h4>

                <!-- Question -->
                <p class="mb-1 text-black "><strong>{{ __('Domanda') }}:</strong> {{ $exercise->question }}</p>

                <!-- Score -->
                <p class="mb-1 text-black"><strong>{{ __('Punteggio') }}:</strong> {{ $exercise->score }}</p>
            </div>
            <hr stile="border-top: 1px solid #000000; width: 90%;" />
        @endforeach

    </div>

    <script>
        function copyKey() {
            var keyElement = document.getElementById('generatedKey');
            var tempTextArea = document.createElement('textarea');
            tempTextArea.value = keyElement.innerText;

            document.body.appendChild(tempTextArea);
            tempTextArea.select();
            document.execCommand('copy');
            document.body.removeChild(tempTextArea);

            alert('Chiave copiata negli appunti!');
        }
    </script>
</x-app-layout>
