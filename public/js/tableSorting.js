var sortDirection = []; // Array per tenere traccia dell'ordinamento per ciascuna colonna

//Aggiornata le icone della tabella esercizi.
function updateSortIcons(columnIndex) {

    var header = document.getElementsByTagName("th")[columnIndex];
    var icon = header.getElementsByTagName("i")[0];

    // Rimuovi le classi delle icone di ordinamento
    icon.classList.remove("fa-chevron-up", "fa-chevron-down");

    if (sortDirection[columnIndex] === "asc") {

        icon.classList.add("fa-chevron-up");
    } else {

        icon.classList.add("fa-chevron-down");
    }
}

//Ordina la tabella esercizi
function sortTable(columnIndex) {

    var table, rows, switching, i, x, y, shouldSwitch;
    table = document.getElementById("exercise-table");
    switching = true;

    // Inizializza la direzione di ordinamento per la colonna se non è già presente
    if (!sortDirection[columnIndex]) {

        sortDirection[columnIndex] = "asc";
    } else {

        // Se la direzione di ordinamento esiste, inverti l'ordine
        sortDirection[columnIndex] = sortDirection[columnIndex] === "asc" ? "desc" : "asc";
    }

    while ( switching ) {

        switching = false;
        rows = table.rows;

        for ( i = 1; i < (rows.length - 1); i++ ) {

            shouldSwitch = false;
            x = rows[i].getElementsByTagName("td")[columnIndex];
            y = rows[i + 1].getElementsByTagName("td")[columnIndex];

            var xValue = x.innerHTML.toLowerCase();
            var yValue = y.innerHTML.toLowerCase();

            // Inverti la logica di ordinamento se la direzione è "desc"
            if (sortDirection[columnIndex] === "desc") {

                if (xValue < yValue) {

                    shouldSwitch = true;
                    break;
                }       
            } 
            else {

                if (xValue > yValue) {

                    shouldSwitch = true;
                    break;
                }
            }
        }

        if ( shouldSwitch ) {

            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
            switching = true;
        }
    }

    // Aggiorna le icone di ordinamento per mostrare la direzione corrente
    updateSortIcons(columnIndex);
}

function showDetails(exerciseId) {

    // Trova l'esercizio corrispondente nell'array $exercises
    var exercise = null;
    for (var i = 0; i < exercises.length; i++) {

        if ( exercises[i].id == exerciseId ) {

            exercise = exercises[i];
            break;
        }
    }

    if ( exercise ) {

        // Ottenere riferimenti agli elementi DOM della finestra di dialogo
        var dialog = document.getElementById('details-dialog');
        var titleElement = document.getElementById('details-title');
        var contentElement = document.getElementById('details-content');

        // Popola il titolo e il contenuto della finestra di dialogo in base al tipo di esercizio
        titleElement.textContent = exercise.name;
        
        //Questa sintassi prende il nome di "Template literals" che consente di inserire espressioni JavaScript all'interno di stringhe delimitate da `
        contentElement.innerHTML = `
            <p><strong>Difficoltà:</strong> ${exercise.difficulty}</p>
            <p><strong>Materia:</strong> ${exercise.subject}</p>
            <p><strong>Tipo:</strong> ${exercise.type}</p>
        `;

        // Aggiungi informazioni specifiche in base al tipo di esercizio
        if (exercise.type === 'Vero o Falso') {

            contentElement.innerHTML += `
                <p><strong>Risposta Corretta:</strong> ${exercise.correct_option}</p>
                <p><strong>Spiegazione:</strong> ${exercise.explanation}</p>
            `;
        } 
        else if (exercise.type === 'Risposta Multipla') {

            contentElement.innerHTML += `
                <p><strong>Risposta Corretta:</strong> ${exercise.correct_option}</p>
                <p><strong>Opzione 1:</strong> ${exercise.option_1}</p>
                <p><strong>Opzione 2:</strong> ${exercise.option_2}</p>
                <p><strong>Opzione 3:</strong> ${exercise.option_3}</p>
                <p><strong>Opzione 4:</strong> ${exercise.option_4}</p>
                <p><strong>Spiegazione:</strong> ${exercise.explanation}</p>
            `;
        }

        // Mostra la finestra di dialogo
        dialog.style.display = 'block';
    }
}

function closeDetailsDialog() {

    // Nascondi la finestra di dialogo quando viene cliccato il pulsante "Chiudi"
    var dialog = document.getElementById('details-dialog');
    dialog.style.display = 'none';
}

function editExercise(id) {
    // Trova l'esercizio corrispondente dall'array di esercizi
    var exercise = exercises.find(function(exercise) {

        return exercise.id == id;
    });
    var editForm = document.getElementById("edit-exercise-form");
    editForm.setAttribute("action", "http://127.0.0.1/exercises/edit-exercise/" + exercise.id);

    // Popola i campi del form con i dati dell'esercizio
    document.getElementById('edit-name').value = exercise.name;
    document.getElementById('edit-question').value = exercise.question;
    document.getElementById('score').value = exercise.score;
    document.getElementById('difficulty').value = exercise.difficulty;
    document.getElementById('subject').value = exercise.subject;
    document.getElementById('type').value = exercise.type;

    // Nascondi tutti i div degli specifici tipi di esercizio
    document.getElementById('multiple_choice').style.display = 'none';
    document.getElementById('true_false').style.display = 'none';

    // Mostra il div corretto in base al tipo di esercizio
    if ( exercise.type === 'Risposta Multipla' ) {

        document.getElementById('correct_option').value = exercise.correct_option;
        document.getElementById('multiple_choice').style.display = 'block';
        document.getElementById('option1').value = exercise.option_1;
        document.getElementById('option2').value = exercise.option_2;
        document.getElementById('option3').value = exercise.option_3;
        document.getElementById('option4').value = exercise.option_4;
        document.getElementById('explanation_multiplo').value = exercise.explanation;

    } 
    else if ( exercise.type === 'Vero o Falso' ) {

        document.getElementById('true_false').style.display = 'block';
    }

    // Mostra il div di modifica
    document.getElementById('edit-dialog').style.display = 'block';
}

function cancelEditExercise() {

    // Chiudi la finestra di dialogo senza inviare il form
    document.getElementById('edit-dialog').style.display = 'none';
}

function updateExercise() {
    // Invia il form al server per l'aggiornamento
    document.getElementById('edit-exercise-form').submit();
}