<style>
    #countdown {
        float: right;
    }
</style>
<x-app-layout>
    <div class="container mt-5 p-4 bg-white rounded-lg shadow"> 
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

        <div id="countdown" class="mb-4"></div>
        
        <!-- Sezione Title, Description e Total Score -->
        <div class="mb-4">
            <h2 class="mb-2 text-lg font-semibold">{{ $test->title }}</h2>
            <h6 class="mb-2">{{ $test->subject }}</h6>
            <h6>{{ __('Punteggio massimo della prova') }}: <span class="font-semibold">{{ $test->total_score }}</span></h6>
            <p>{{ $test->description }}</p>
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
            <form action="{{ route('pratices.send') }}" method="post" id="invia">
                @csrf
                <input type="hidden" name="id_practices" value="{{ $test->id }}">
                @foreach($exercises as $index => $exercise)
                    <div class="card mb-4" id="questionCard{{$index}}">
                        <div class="card-body">
                            <input type="hidden" name="id[]" value="{{ $exercise['id'] }}">
                            <h6 class="mb-3 font-medium">{{ $exercise['question'] }}</h6>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="d-flex align-items-center">
                                    <span class="mr-2">{{ __('Punteggio') }}:</span>
                                    <span class="badge bg-secondary text-white">{{ $exercise['score'] }}</span>
                                </div>
                            </div>
                            @if($exercise['type'] === "Risposta Multipla")
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="risposte[{{ $exercise['id'] }}]" id="option1_{{ $exercise['id'] }}" value="{{ $exercise['option_1'] }}">
                                    <label class="form-check-label" for="option1_{{ $exercise['id'] }}">{{ $exercise['option_1'] }}</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="risposte[{{ $exercise['id'] }}]" id="option2_{{ $exercise['id'] }}" value="{{ $exercise['option_2'] }}">
                                    <label class="form-check-label" for="option2_{{ $exercise['id'] }}">{{ $exercise['option_2'] }}</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="risposte[{{ $exercise['id'] }}]" id="option3_{{ $exercise['id'] }}" value="{{ $exercise['option_3'] }}">
                                    <label class="form-check-label" for="option3_{{ $exercise['id'] }}">{{ $exercise['option_3'] }}</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="risposte[{{ $exercise['id'] }}]" id="option4_{{ $exercise['id'] }}" value="{{ $exercise['option_4'] }}">
                                    <label class="form-check-label" for="option4_{{ $exercise['id'] }}">{{ $exercise['option_4'] }}</label>
                                </div>
                            @elseif($exercise['type'] === "Risposta Aperta")
                                <textarea class="form-control" name="risposte[{{ $exercise['id'] }}]" rows="7" id="text_{{ $exercise['id'] }}"></textarea>
                            @else
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="risposte[{{ $exercise['id'] }}]" id="vero_{{ $exercise['id'] }}" value="vero">
                                    <label class="form-check-label" for="vero_{{ $exercise['id'] }}">{{ __('Vero') }}</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="risposte[{{ $exercise['id'] }}]" id="falso_{{ $exercise['id'] }}" value="falso">
                                    <label class="form-check-label" for="falso_{{ $exercise['id'] }}">{{ __('Falso') }}</label>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </form>
        </div>

        <!-- Pulsante per tornare alla domanda precedente -->
        <button id="previousBtn" class="btn btn-secondary mr-2">{{ __('Indietro') }}</button>

        <!-- Pulsante per passare alla domanda successiva -->
        <button id="nextBtn" class="btn btn-primary">{{ __('Avanti') }}</button>

        <!-- Pulsante per inviare il modulo  di risposta -->
        <button id="submitBtn" class="btn btn-primary" style="float: right;" >{{ __('Invia') }}</button>
    </div>

    <!-- Modal di conferma -->
    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">{{ __('Conferma Invio') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{ __('Sei sicuro di voler inviare le risposte?') }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">{{ __('Annulla') }}</button>
                <button type="button" class="btn btn-primary" id="confirmSend">{{ __('Invia') }}</button>
            </div>
            </div>
        </div>
    </div>

</x-app-layout>
    <script>
        route = "{!! route('status_test', ['practice' => $test]) !!}";
        routeDashboard = "{!! route('dashboard') !!}";
        durata = "{!! $test->time ?? null !!}";
        
        // Verifica se durata è diverso da NULL prima di avviare il timer
        if (durata !== null) {
            // Aggiungi il codice del timer qui
            dateStart = new Date("{!! $test->updated_at  !!}");
            dateStart.setHours(dateStart.getHours() + 1); // Aggiungi un'ora
            dateEnd = new Date(dateStart.getTime() + (durata * 60000));

            function timer() {
                // Ottieni l'orario attuale
                const currentDate = new Date();

                // Calcola il tempo rimanente sottraendo l'orario attuale all'orario di fine
                const remainingTime = dateEnd - currentDate;

                if (remainingTime <= 0) {
                    document.getElementById("countdown").textContent = "";
                    document.getElementById("countdown").style.color = "red";
                    return;
                }

                // Converti il tempo rimanente in ore, minuti e secondi
                const hoursRemaining = Math.floor(remainingTime / (1000 * 60 * 60));
                const minutesRemaining = Math.floor((remainingTime % (1000 * 60 * 60)) / (1000 * 60));
                const secondsRemaining = Math.floor((remainingTime % (1000 * 60)) / 1000);

                //Costruisci la parte grafica nell'elemento con id = countdown metti ORA-MINUTI-SECONDI 
                // Costruisci la stringa dell'orario rimanente
                const countdownString = `${hoursRemaining.toString().padStart(2, '0')}:${minutesRemaining.toString().padStart(2, '0')}:${secondsRemaining.toString().padStart(2, '0')}`;

                // Aggiorna il contenuto dell'elemento con id "countdown"
                document.getElementById("countdown").textContent = countdownString;
            }

            setInterval(timer, 1000);
        }

        function fetchStatus() {
            fetch(route, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {

                if( data.status == "finished" ){

                    form = document.getElementById('invia');
                    form.submit();
                }
                else if( data.status == "kicked" ){

                    window.location.href = routeDashboard;
                }
                else{

                    setTimeout(fetchStatus, 5000);
                }
            })    
            .catch(error => console.error("Errore nell'aggiornamento ", error));
        }

        fetchStatus(); 
        setInterval(timer, 1000);
        
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var questions = document.querySelectorAll('.card');
            var totalQuestions = questions.length;
            var nextButton = document.getElementById('nextBtn');
            var previousButton = document.getElementById('previousBtn');
            var progressBar = document.querySelector('.progress-bar');

            // Imposta il tasto Indietro come disabilitato all'avvio
            previousButton.disabled = true;
            nextButton.disabled = false;

            hideAllQuestionsExcept(0); // Nasconde tutte le domande tranne la prima
            updateProgressBar(); // Inizializza la progress bar

            // Aggiungi un gestore di eventi per ciascun campo di input
            var inputFields = document.querySelectorAll('input[type="radio"], input[type="text"], textarea');
            inputFields.forEach(function(field) {
                field.addEventListener('input', updateProgressBar);
            });

            // Aggiungi un gestore di eventi per i campi di input di tipo radio
            var radioGroups = document.querySelectorAll('input[type="radio"]');
            radioGroups.forEach(function(radioGroup) {
                radioGroup.addEventListener('change', updateProgressBar);
            });

            // Aggiungi un gestore di eventi per ciascun pulsante numerato
            var exerciseButtons = document.querySelectorAll('.exercise-btn');
            exerciseButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    var indexToShow = parseInt(this.getAttribute('data-index'));
                    hideAllQuestionsExcept(indexToShow);
                    showQuestion(indexToShow);
                    updateProgressBar();
                    // Imposta la condizione per abilitare o disabilitare il pulsante Indietro
                    if (indexToShow === 0) {
                        previousButton.disabled = true;
                    } else {
                        previousButton.disabled = false;
                    }
                    // Imposta la condizione per abilitare o disabilitare il pulsante Avanti
                    if (indexToShow === totalQuestions - 1) {
                        nextButton.disabled = true;
                    } else {
                        nextButton.disabled = false;
                    }
                });
            });

            // Gestisci il clic sul pulsante Avanti
            nextButton.addEventListener('click', function() {
                var currentQuestionIndex = getCurrentQuestionIndex();
                if (currentQuestionIndex < totalQuestions - 1) {
                    hideQuestion(currentQuestionIndex);
                    showQuestion(currentQuestionIndex + 1);
                    updateProgressBar();
                    // Abilita il tasto Indietro quando si passa alla domanda successiva
                    previousButton.disabled = false;
                    
                    // Disabilita il tasto Avanti se si passa all'ultima domanda
                    if (currentQuestionIndex === totalQuestions - 2 ) {
                        nextButton.disabled = true;
                    }
                }
            });

            // Gestisci il clic sul pulsante Indietro
            previousButton.addEventListener('click', function() {
                var currentQuestionIndex = getCurrentQuestionIndex();
                if (currentQuestionIndex > 0) {
                    hideQuestion(currentQuestionIndex);
                    showQuestion(currentQuestionIndex - 1);
                    updateProgressBar();
                    nextButton.disabled = false;
                }
                // Disabilita il tasto Indietro se si torna alla prima domanda
                if (currentQuestionIndex === 1) {
                    previousButton.disabled = true;
                }
            }); 

            // Nascondi la domanda corrente
            function hideQuestion(index) {
                questions[index].classList.add('hide-total');
            }

            // Mostra la domanda corrente
            function showQuestion(index) {
                questions[index].classList.remove('hide-total');
            }

            // Nascondi tutte le domande tranne quella con l'indice specificato
            function hideAllQuestionsExcept(indexToShow) {
                for (var i = 0; i < totalQuestions; i++) {
                    if (i !== indexToShow) {
                        hideQuestion(i);
                    }
                }
            }

            // Ottieni l'indice della domanda correntemente visualizzata
            function getCurrentQuestionIndex() {
                for (var i = 0; i < totalQuestions; i++) {
                    if (!questions[i].classList.contains('hide-total')) {
                        return i;
                    }
                }
                return -1;
            }

            // Aggiorna il valore della progress bar in base al numero di domande completate
            function updateProgressBar() {
                var completedQuestions = 0;

                questions.forEach(function(question) {
                    var inputFields = question.querySelectorAll('input[type="radio"], input[type="text"], textarea');
                    var completedField = false;

                    inputFields.forEach(function(field) {
                        if ((field.tagName === 'INPUT' && field.type === 'radio' && field.checked) || (field.tagName !== 'INPUT' && field.value.trim() !== '')) {
                            completedField = true;
                        }
                    });

                    if (completedField) {
                        completedQuestions++;
                    }
                });

                var progressValue = (completedQuestions / totalQuestions) * 100;
                progressBar.style.width = progressValue + '%';
                progressBar.setAttribute('aria-valuenow', progressValue);
            }


            // Pulsante per inviare il modulo di risposta
            var submitButton = document.getElementById('submitBtn');
            // Modal di conferma
            var confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));

            // Gestisci il clic sul pulsante Invia
            submitButton.addEventListener('click', function() {
                // Mostra il modal di conferma
                confirmationModal.show();
            });

            // Gestisci il clic sul pulsante di conferma all'interno del modal
            document.getElementById('confirmSend').addEventListener('click', function() {
                // Invia il modulo di risposta
                document.getElementById('invia').submit();
            });

        });

    </script>
