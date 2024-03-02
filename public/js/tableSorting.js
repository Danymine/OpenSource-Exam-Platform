var sortDirection = []; // Array per tenere traccia dell'ordinamento per ciascuna colonna

language = "translate";
console.log(translations);

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
    var exercise = exercises.find(ex => ex.id == exerciseId);

    if (exercise) {
        // Ottenere riferimenti agli elementi DOM della finestra di dialogo
        var titleElement = document.getElementById('details-title');
        var contentElement = document.getElementById('details-content');

        // Popola il titolo e il contenuto della finestra di dialogo in base al tipo di esercizio
        titleElement.textContent = exercise.name;

        // Costruisci il contenuto della finestra di dialogo
        var contentHtml = `
            <p style="color: black;" ><strong>${translations[language]["Domanda"]}:</strong> ${exercise.question}</p>
            <p style="color: black;" ><strong>${translations[language]["Difficoltà"]}:</strong> ${exercise.difficulty}</p>
            <p style="color: black;" ><strong>${translations[language]["Materia"]}:</strong> ${exercise.subject}</p>
            <p style="color: black;" ><strong>${translations[language]["Tipo"]}:</strong> ${exercise.type}</p>
        `;

        // Aggiungi informazioni specifiche in base al tipo di esercizio
        if (exercise.type === 'Vero o Falso') {
            contentHtml += `
                <p style="color: black;" ><strong>${translations[language]["Risposta Corretta"]}:</strong> ${exercise.correct_option}</p>
                <p style="color: black;" ><strong>${translations[language]["Spiegazione"]}:</strong> ${exercise.explanation}</p>
            `;
        } else if (exercise.type === 'Risposta Multipla') {
            contentHtml += `
                <p style="color: black;"><strong>${translations[language]["Risposta Corretta"]}:</strong> ${exercise.correct_option}</p>
                <p style="color: black;" ><strong>${translations[language]["Opzione A"]}:</strong> ${exercise.option_1}</p>
                <p style="color: black;" ><strong>${translations[language]["Opzione B"]}:</strong> ${exercise.option_2}</p>
                <p style="color: black;" ><strong>${translations[language]["Opzione C"]}:</strong> ${exercise.option_3}</p>
                <p style="color: black;" ><strong>${translations[language]["Opzione D"]}:</strong> ${exercise.option_4}</p>
                <p style="color: black;" ><strong>${translations[language]["Spiegazione"]}:</strong> ${exercise.explanation}</p>
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

function buildMultipleChoiceOptions(exercise, bool) {

    var multipleChoiceContainer = document.getElementById('multiple_choice_container');
    if( bool === true){

        // Numero di opzioni per la domanda a scelta multipla
        multipleChoiceContainer.style.display = 'block';
        const numOptions = 4;

        // Cancella eventuali opzioni preesistenti
        multipleChoiceContainer.innerHTML = '';
        array = [
            exercise.option_1, exercise.option_2, exercise.option_3, exercise.option_2
        ]
        alpha = ['A', 'B', 'C', 'D'];
        // Ciclo per creare gli input per le opzioni
        for (let i = 1; i <= numOptions; i++) {
            const label = document.createElement('label');
            label.setAttribute('for', `option${i}`);
            label.setAttribute('class', `form-label`);
            label.textContent = translations[language]["Opzione"] + ": " +  alpha[i-1];

            const input = document.createElement('input');
            input.setAttribute('type', 'text');
            input.setAttribute('id', `option${i}`);
            input.setAttribute('name', 'options[]');
            if( array[i-1] != null ){

                input.setAttribute('value', array[i-1]);
            }
            else{

                input.setAttribute('placeholder', translations[language]["Inserisci l'opzione"] + " " + alpha[i-1]);
            }
            input.setAttribute('class', 'form-control mb-3');

            multipleChoiceContainer.appendChild(label);
            multipleChoiceContainer.appendChild(input);
            multipleChoiceContainer.appendChild(document.createElement('br'));
        }

        // Aggiungi l'input per l'opzione corretta
        const correctOptionLabel = document.createElement('label');
        correctOptionLabel.setAttribute('for', 'correct_option');
        correctOptionLabel.setAttribute('class', `form-label`);
        correctOptionLabel.textContent = translations[language]["Risposta Corretta"];

        const correctOptionInput = document.createElement('select');
        correctOptionInput.setAttribute('id', 'correct_option');
        correctOptionInput.setAttribute('name', 'correct_option');
        correctOptionInput.setAttribute('class', 'form-select form-select-lg mb-3 rounded p-2');
        correctOptionInput.setAttribute('value',  exercise.correct_option);

        array = ['a', 'b', 'c', 'd'];
        for (let i = 0; i < numOptions; i++) {

            var alt1 = document.createElement('option');
            alt1.value = array[i];
            alt1.textContent = array[i].toUpperCase();
            if( exercise.correct_option == array[i] ){

                alt1.setAttribute('selected', 'selected');
            }
            correctOptionInput.appendChild(alt1);
        }


        multipleChoiceContainer.appendChild(correctOptionLabel);
        multipleChoiceContainer.appendChild(document.createElement('br'));
        multipleChoiceContainer.appendChild(correctOptionInput);
        multipleChoiceContainer.appendChild(document.createElement('br'));

        // Aggiungi l'input per la spiegazione
        const explanationLabel = document.createElement('label');
        explanationLabel.setAttribute('for', 'explanation');
        explanationLabel.setAttribute('class', `form-label`);
        explanationLabel.textContent = translations[language]["Spiegazione"];

        const explanationInput = document.createElement('input');
        explanationInput.setAttribute('type', 'text');
        explanationInput.setAttribute('id', 'explanation');
        explanationInput.setAttribute('name', 'explanation');
        explanationInput.setAttribute('class', 'form-control mb-3');
        if( exercise.explanation != null ){

            explanationInput.setAttribute('value',  exercise.explanation);
        }
        else{

            explanationInput.setAttribute('placeholder', translations[language]["Inserisci la spiegazione"]);
        }

        multipleChoiceContainer.appendChild(explanationLabel);
        multipleChoiceContainer.appendChild(explanationInput);
        multipleChoiceContainer.appendChild(document.createElement('br'));
    }
    else{

        multipleChoiceContainer.innerHTML = ''; // Rimuove tutto il contenuto HTML all'interno dell'elemento 
    }
}

function buildVeroFalso(exercise, bool){

    var true_false_container = document.getElementById('true_false_container');
    if( bool === true ){

        const correctOptionLabel = document.createElement('label');
        correctOptionLabel.setAttribute('for', 'correct_option');
        correctOptionLabel.setAttribute('class', `form-label`);
        correctOptionLabel.textContent = translations[language]["Risposta Corretta"];

        const correctOptionInput = document.createElement('select');
        correctOptionInput.setAttribute('id', 'correct_option');
        correctOptionInput.setAttribute('name', 'correct_option');
        correctOptionInput.setAttribute('class', 'form-select form-select-lg mb-3 rounded p-2');
        correctOptionInput.setAttribute('value',  exercise.correct_option);
        

        const option1 = document.createElement('option');
        option1.value = 'Vero';
        option1.textContent = translations[language]["Vero"];
        correctOptionInput.appendChild(option1);

        const option2 = document.createElement('option');
        option2.value = 'Falso';
        option2.textContent = translations[language]["Falso"];
        correctOptionInput.appendChild(option2);

        true_false_container.appendChild(correctOptionLabel);
        true_false_container.appendChild(document.createElement('br'));
        true_false_container.appendChild(correctOptionInput);
        true_false_container.appendChild(document.createElement('br'));


        const explanationLabel = document.createElement('label');
        explanationLabel.setAttribute('for', 'explanation');
        explanationLabel.setAttribute('class', `form-label`);
        explanationLabel.textContent =  translations[language]["Spiegazione"];

        const explanationInput = document.createElement('input');
        explanationInput.setAttribute('type', 'text');
        explanationInput.setAttribute('id', 'explanation');
        explanationInput.setAttribute('name', 'explanation');
        explanationInput.setAttribute('class', 'form-control mb-3');
        if( exercise.explanation != null ){

            explanationInput.setAttribute('value',  exercise.explanation);
        }
        else{

            explanationInput.setAttribute('placeholder', translations[language]["Inserisci la spiegazione"]);
        }

        true_false_container.appendChild(explanationLabel);
        true_false_container.appendChild(explanationInput);
        true_false_container.appendChild(document.createElement('br'));

    }
    else{

        true_false_container.innerHTML = '';
    }

}

function editExercise(id) {

    // Trova l'esercizio corrispondente dall'array di esercizi
    var exercise = exercises.find(function(exercise) {
        return exercise.id == id;
    });

    var editForm = document.getElementById("edit-exercise-form");
    editForm.setAttribute("action", "http://127.0.0.1/exercises/edit-exercise");
    document.getElementById("primary").setAttribute('value', id);
    
    
    // Popola i campi del form con i dati dell'esercizio
    document.getElementById('edit-name').value = exercise.name;
    document.getElementById('edit-question').value = exercise.question;
    document.getElementById('score').value = exercise.score;
    document.getElementById('difficulty').value = exercise.difficulty;
    document.getElementById('subject').value = exercise.subject;
    
    if(exercise.type == "Risposta Aperta" ){

        document.getElementById('type_open').setAttribute('selected', 'selected');
        buildMultipleChoiceOptions(exercise, false);
        buildVeroFalso(exercise, false);

    }
    else if( exercise.type == "Risposta Multipla"){

        document.getElementById('type_closed').setAttribute('selected', 'selected');
        buildMultipleChoiceOptions(exercise, false);
        buildMultipleChoiceOptions(exercise, true);
        buildVeroFalso(exercise, false);
        //Costruisco il form a lui necessario.
    }
    else{

        document.getElementById('type_true').setAttribute('selected', 'selected');

        buildMultipleChoiceOptions(exercise, false);
        buildVeroFalso(exercise, false);
        buildVeroFalso(exercise, true);
        //Costrisco il form a lui necessario.
    }

    // Mostra il modale di modifica
    $('#edit-dialog').modal('show');
}

function cancelEditExercise() {

    $('#edit-dialog').modal('hide');
}

function updateExercise() {
    // Invia il form al server per l'aggiornamento
    document.getElementById('edit-exercise-form').submit();
}


type =  document.getElementById('type');
type.addEventListener('change', function(){
    
    var id = document.getElementById('primary').value;
    var exercise = exercises.find(function(exercise) {
        return exercise.id == id;
    });
    
    if( type.value === "Risposta Aperta" ){

        buildMultipleChoiceOptions(exercise, false);
        buildVeroFalso(exercise, false);
    }
    else if( type.value == "Risposta Multipla"){

        buildMultipleChoiceOptions(exercise, false);
        buildMultipleChoiceOptions(exercise, true);
        buildVeroFalso(exercise, false);
    }
    else{

        buildMultipleChoiceOptions(exercise, false);
        buildVeroFalso(exercise, false);
        buildVeroFalso(exercise, true);
    }
});