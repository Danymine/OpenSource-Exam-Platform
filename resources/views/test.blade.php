<style>
    .exercise-btn.selected {
        background-color: #007bff;
        color: #fff;
    }

    #questionCard .card-body {
        min-height: 335px; /* Imposta un'altezza minima per il riquadro della domanda */
    }
</style>


<x-app-layout>
    <div class="container mt-5 p-4 bg-white rounded-lg shadow"> 
        <!-- Sezione Title, Description e Total Score -->
        <div class="mb-4">
            <h2 class="mb-2 text-lg font-semibold">{{ $test->title }}</h2>
            <h6 class="mb-2">{{ $test->description }}</h6>
            <h6>{{ __('Punteggio massimo della prova') }}: <span class="font-semibold">{{ $test->total_score }}</span></h6>
        </div>

        <!-- Sezione con i riquadri delle domande -->
        <div class="mb-4">
            <hr>
            <h6 class="mb-2">{{ __('Domande') }}:</h6>
            @for($i = 1; $i <= count($exercises); $i++)
                <button class="btn btn-outline-primary mr-2 exercise-btn" data-index="{{ $i - 1 }}">{{ $i }}</button>
            @endfor
            <!-- progress-bar -->
            <div class="progress mt-4">
                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="{{ count($exercises) }}" style="width: 0%"></div>
            </div>
        </div>

        <!-- Sezione delle domande -->
        <div class="mb-4">
            <form action="{{ route('pratices.send') }}" method="post">
                @csrf
                <input type="hidden" name="id_practices" value="{{ $test->id }}">
                <div class="card mb-4" id="questionCard">
                    <!-- Il codice per visualizzare la domanda corrente verrà aggiunto dinamicamente qui -->
                </div>
            </form>
        </div>

        <!-- Pulsante per tornare alla domanda precedente -->
        <button id="previousBtn" class="btn btn-secondary mr-2">Indietro</button>

        <!-- Pulsante per passare alla domanda successiva -->
        <button id="nextBtn" class="btn btn-primary">Avanti</button>

    </div>
</x-app-layout>

<!-- Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">Conferma Invio Risposte</h5>
            </div>
            <div class="modal-body">
                Sei sicuro di voler inviare le risposte?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-danger" data-bs-dismiss="modal" id="cancelSendBtn">Annulla</button>
                <button type="button" class="btn btn-primary" id="confirmSendBtn">Invia</button>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        const exerciseButtons = document.querySelectorAll('.exercise-btn');
        const questionCard = document.getElementById("questionCard");
        const prevButton = document.getElementById("previousBtn");
        const nextButton = document.getElementById("nextBtn");
        const form = document.querySelector("form");
        const progressBar = document.querySelector(".progress-bar");
        const exercises = {!! json_encode($exercises) !!};
        let currentExerciseIndex = 0;
        
        function showExercise(index) {
            if (index >= 0 && index < exercises.length) {
                const exercise = exercises[index];
                questionCard.innerHTML = `
                    <div class="card-body">
                        <input type="hidden" name="id[]" value="${exercise['id']}">
                        <h6 class="mb-3 font-medium">${exercise["question"]}</h6>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center">
                                <span class="mr-2">Score:</span>
                                <span class="badge bg-secondary text-white">${exercise['score']}</span>
                            </div>
                        </div>
                        ${exercise["type"] === "Risposta Aperta" ? `<textarea class="form-control" name="risposte[${exercise['id']}]" rows="2"></textarea>` : ""}
                        ${exercise["type"] === "Risposta Multipla" ? `
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="risposte[${exercise['id']}]" id="option1_${exercise['id']}" value="${exercise['option_1']}">
                                <label class="form-check-label" for="option1_${exercise['id']}">${exercise['option_1']}</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="risposte[${exercise['id']}]" id="option2_${exercise['id']}" value="${exercise['option_2']}">
                                <label class="form-check-label" for="option2_${exercise['id']}">${exercise['option_2']}</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="risposte[${exercise['id']}]" id="option3_${exercise['id']}" value="${exercise['option_3']}">
                                <label class="form-check-label" for="option3_${exercise['id']}">${exercise['option_3']}</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="risposte[${exercise['id']}]" id="option4_${exercise['id']}" value="${exercise['option_4']}">
                                <label class="form-check-label" for="option4_${exercise['id']}">${exercise['option_4']}</label>
                            </div>
                        ` : ""}
                    </div>
                `;
                prevButton.disabled = index === 0;
                nextButton.innerText = index === exercises.length - 1 ? "Invia Risposte" : "Avanti";
                
                // Rimuovi la classe 'selected' da tutti i pulsanti delle domande
                exerciseButtons.forEach(button => {
                    button.classList.remove('selected');
                });
                // Aggiungi la classe 'selected' al pulsante della domanda corrente
                exerciseButtons[index].classList.add('selected');

                // Aggiorna la progress bar
                progressBar.style.width = ((index + 1) / exercises.length * 100) + "%";
            }
        }

        // Aggiungi un event listener a ciascun pulsante delle domande
        exerciseButtons.forEach(function(button, index) {
            button.addEventListener('click', function() {
                currentExerciseIndex = index;
                showExercise(currentExerciseIndex);
            });
        });

        // Event listener per il pulsante "Indietro"
        prevButton.addEventListener('click', function() {
            currentExerciseIndex--;
            showExercise(currentExerciseIndex);
        });

        // Event listener per il pulsante "Avanti" o "Invia Risposte"
        nextButton.addEventListener('click', function() {
            if (currentExerciseIndex === exercises.length - 1) {
                $('#confirmModal').modal('show'); // Mostra la modale di conferma
            } else {
                currentExerciseIndex++;
                showExercise(currentExerciseIndex);
            }
        });

        // Event listener per il pulsante di conferma nella modale
        document.getElementById("confirmSendBtn").addEventListener('click', function() {
            form.submit(); // Invia il modulo
        });

        // Event listener per il pulsante di annulla nella modale
        document.getElementById("cancelSendBtn").addEventListener('click', function() {
            $('#confirmModal').modal('hide'); // Chiudi la modale
        });


        // Mostra la prima domanda all'avvio della pagina
        showExercise(currentExerciseIndex);
    });

</script>

