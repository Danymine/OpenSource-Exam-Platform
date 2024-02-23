<x-app-layout>
    <x-slot name="header">
        <h4 class="text-2xl font-bold text-black mb-4">{{ $practice->title }}</h4>
        <hr stile="border-top: 1px solid #000000; width: 90%;" />
    </x-slot>

    <div class="container mx-auto mt-5">
        <div class="practice-details">
            <!-- Description -->
            <p class="mb-3"><strong class="text-black">Descrizione:</strong> {{ $practice->description }}</p>

            <!-- Difficulty -->
            <p class="mb-3"><strong class="text-black">Difficoltà:</strong> {{ $practice->difficulty }}</p>

            <!-- Subject -->
            <p class="mb-3"><strong class="text-black">Materia:</strong> {{ $practice->subject }}</p>

            <!-- Total Score -->
            <p class="mb-3"><strong class="text-black">Punteggio Totale:</strong> {{ $practice->total_score }}</p>

            <!-- Feedback Enabled -->
            <p class="mb-3">
                <input type="checkbox" id="feedbackEnabled" disabled {{ $practice->feedback_enabled ? 'checked' : '' }}>
                <label for="feedbackEnabled" class="text-black">Feedback Automatico</label>
            </p>

            <!-- Randomize Questions -->
            <p class="mb-3">
                <input type="checkbox" id="randomizeQuestions" disabled {{ $practice->randomize_questions ? 'checked' : '' }}>
                <label for="randomizeQuestions" class="text-black">Randomizzazione Domande</label>
            </p>

            <!-- Creation Date -->
            <p class="mb-3"><strong class="text-black">Data di Creazione:</strong> {{ $practice->created_at }}</p>

            <!-- Practice Date -->
            <p class="mb-3"><strong class="text-black">Data programmata:</strong> {{ \Carbon\Carbon::parse($practice->practice_date)->format('d-m-Y') }}</p>
        </div>

        <!-- Linea di separazione -->
        <hr class="my-5">

        <h2 class="mt-5 mb-3 text-xl font-bold text-black">Esercizi:</h2>

        @foreach($practice->exercises as $exercise)
            <div class="exercise border-b-2 border-gray-200 pb-3 mb-4">
                <!-- Exercise Name -->
                <h3 class="text-lg font-semibold text-black">{{ $exercise->name }}</h3>

                <!-- Question -->
                <p class="mb-1"><strong class="text-black">Domanda:</strong> {{ $exercise->question }}</p>

                <!-- Score -->
                <p class="mb-1"><strong class="text-black">Punteggio:</strong> {{ $exercise->score }}</p>
            </div>
        @endforeach

        <!-- Linea di separazione -->
        <hr class="my-5">

        <div class="key-section mt-5">
            <!-- Key Title -->
            <strong class="text-black">Chiave:</strong>

            <!-- Key Content -->
            <span id="generatedKey" class="text-black">{{ $practice->key }}</span>

            <!-- Copy Key Button -->
            <button onclick="copyKey()" class="btn btn-primary ml-3">Copia</button>
        </div>

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
