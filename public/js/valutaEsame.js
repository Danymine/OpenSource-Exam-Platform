document.addEventListener("DOMContentLoaded", function() {
    var currentExerciseIndex = 0;
    var exercises = document.querySelectorAll('.exercise');
    var totalExercises = exercises.length;
    var nextButton = document.getElementById('nextButton');
    var finishButton = document.getElementById('finishButton');
    var backButton = document.getElementById('backButton');
    var isFinish = false; // variabile per tenere traccia dello stato del pulsante "Finish"

    showExercise(currentExerciseIndex);

    // Gestisci il clic sul pulsante Avanti
    nextButton.addEventListener('click', function() {
        if (currentExerciseIndex < totalExercises - 1) {
            hideExercise(currentExerciseIndex);
            currentExerciseIndex++;
            showExercise(currentExerciseIndex);
        } else if (currentExerciseIndex === totalExercises - 1) {
            hideExercise(currentExerciseIndex); // Nascondi l'ultimo esercizio
            showFinish();
            nextButton.classList.add('hide-total');
            finishButton.classList.remove('hide-total');
            isFinish = true;
        }
    });

    // Gestisci il clic sul pulsante Indietro
    backButton.addEventListener('click', function() {
        if (isFinish) { // Se si è nel blocco "Fine"
            hideFinish();
            finishButton.classList.add('hide-total');
            nextButton.classList.remove('hide-total');
            isFinish = false;
            // Torna all'ultimo esercizio
            if (currentExerciseIndex === totalExercises - 1) {
                showExercise(currentExerciseIndex);
            }
        } else { // Se non si è nel blocco "Fine"
            if (currentExerciseIndex > 0) {
                hideExercise(currentExerciseIndex);
                currentExerciseIndex--;
                showExercise(currentExerciseIndex);
            }
        }
    });  

    // Nascondi l'esercizio corrente
    function hideExercise(index) {
        exercises[index].classList.add('hide-total');
    }

    // Mostra l'esercizio corrente
    function showExercise(index) {
        exercises[index].classList.remove('hide-total');
    }

    // Mostra il blocco finish
    function showFinish() {
        // Cambia il testo dell'elemento <h4> a "Note finali"
        document.querySelector('#cont').innerText = str;
        // Mostra il blocco .finish
        document.querySelector('.finish').classList.remove('hide-total');
    }

    // Nascondi il blocco finish
    function hideFinish() {
        // Cambia il testo dell'elemento <h4> a "Note finali"
        document.querySelector('#cont').innerText = dmd;
        document.querySelector('.finish').classList.add('hide-total');
    }
});
