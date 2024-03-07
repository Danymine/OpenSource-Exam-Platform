function showDetails(exerciseId) {
    // Trova l'esercizio corrispondente nell'array $exercises
    var exercise = exercises.find(ex => ex.id == exerciseId);

    if (exercise) {
        // Ottenere riferimenti agli elementi DOM della finestra di dialogo
        var titleElement = document.getElementById('details-title');
        var contentElement = document.getElementById('details-content');

        // Popola il titolo e il contenuto della finestra di dialogo in base al tipo di esercizio
        titleElement.textContent = exercise.name;

        // Costruisci il contenuto della finestra di dialogo
        var contentHtml = `
            <p style="color: black;" ><strong>Question:</strong> ${exercise.question}</p>
            <p style="color: black;" ><strong>Difficoltà:</strong> ${exercise.difficulty}</p>
            <p style="color: black;" ><strong>Materia:</strong> ${exercise.subject}</p>
            <p style="color: black;" ><strong>Tipo:</strong> ${exercise.type}</p>
        `;

        // Aggiungi informazioni specifiche in base al tipo di esercizio
        if (exercise.type === 'Vero o Falso') {
            contentHtml += `
                <p style="color: black;" ><strong>Risposta Corretta:</strong> ${exercise.correct_option}</p>
                <p style="color: black;" ><strong>Spiegazione:</strong> ${exercise.explanation}</p>
            `;
        } else if (exercise.type === 'Risposta Multipla') {
            contentHtml += `
                <p style="color: black;"><strong>Risposta Corretta:</strong> ${exercise.correct_option}</p>
                <p style="color: black;" ><strong>Opzione 1:</strong> ${exercise.option_1}</p>
                <p style="color: black;" ><strong>Opzione 2:</strong> ${exercise.option_2}</p>
                <p style="color: black;" ><strong>Opzione 3:</strong> ${exercise.option_3}</p>
                <p style="color: black;" ><strong>Opzione 4:</strong> ${exercise.option_4}</p>
                <p style="color: black;" ><strong>Spiegazione:</strong> ${exercise.explanation}</p>
            `;
        }

        // Inserisci il contenuto nella finestra di dialogo
        contentElement.innerHTML = contentHtml;

        // Mostra la finestra di dialogo utilizzando Bootstrap modal
        $('#details-dialog').modal('show');
    }
}

function closeDetailsDialog() {

    $('#details-dialog').modal('hide');
}

function toggleFilterSection() {
    var filterSection = document.getElementById('filterSection');
    var resetButton = document.querySelector('.btn-secondary'); // Seleziona il bottone "Cancella Filtri"
    
    if (filterSection.style.display === 'none') {
        filterSection.style.display = 'block';
        resetButton.style.display = 'inline-block'; // Mostra il bottone "Cancella Filtri"
    } else {
        filterSection.style.display = 'none';
        resetButton.style.display = 'none'; // Nascondi il bottone "Cancella Filtri"
        resetFilters(); // Resetta i filtri quando il modulo dei filtri viene chiuso
    }
}


function resetFilters() {
    document.getElementById('materiaInput').value = '';
    document.getElementById('typeInput').value = '';
    document.getElementById('difficoltaInput').value = '';
    applyFilters(); // Applica i filtri resettati
}

function applyFilters() {
    var materia = document.getElementById('materiaInput').value.toLowerCase();
    var tipo = document.getElementById('typeInput').value.toLowerCase();
    var difficolta = document.getElementById('difficoltaInput').value.toLowerCase();
    var exercises = document.getElementsByClassName('exercise');

    for (var i = 0; i < exercises.length; i++) {
        var exercise = exercises[i];
        var subject = exercise.getAttribute('data-subject').toLowerCase();
        var exerciseType = exercise.getAttribute('data-type').toLowerCase();
        var difficulty = exercise.getAttribute('data-difficulty').toLowerCase();

        // Verifica se l'esercizio corrente soddisfa i filtri
        var showExercise = true;

        if (materia !== '' && subject.indexOf(materia) === -1) {
            showExercise = false;
        }

        if (tipo !== '' && exerciseType.indexOf(tipo) === -1) {
            showExercise = false;
        }

        if (difficolta !== '' && difficulty !== difficolta) {
            showExercise = false;
        }

        // Se l'esercizio non soddisfa i filtri, nascondilo
        if (showExercise) {
            exercise.style.display = 'block';
        } else {
            exercise.style.display = 'none';
        }
    }
}

function updateScoreCounter() {
    // Seleziona tutti gli input checkbox degli esercizi selezionati
    var selectedExercises = document.querySelectorAll('input[type="checkbox"]:checked');
    
    // Calcola il punteggio totale
    var totalScore = Array.from(selectedExercises).reduce((accumulator, exercise) => {
        return accumulator + parseInt(exercise.getAttribute('data-score'));
    }, 0);
    
    // Aggiorna il contenuto del counter del punteggio
    document.getElementById('score').textContent = 'Punteggio: ' + totalScore;
}

// Chiamata alla funzione al caricamento della pagina per aggiornare il punteggio iniziale
updateScoreCounter();

// Aggiungi un event listener per ogni input checkbox per aggiornare il punteggio quando vengono selezionati o deselezionati
var checkboxes = document.querySelectorAll('input[type="checkbox"]');
checkboxes.forEach(function(checkbox) {
    checkbox.addEventListener('change', updateScoreCounter);
});

