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

    
document.addEventListener("DOMContentLoaded", function() {
    // Seleziona gli elementi del DOM
    const filtro = document.getElementById("filtri");
    const valore = document.getElementById("valore");
    const esercizi = document.querySelectorAll(".exercise");

    // Aggiungi un listener per il cambiamento del filtro o del valore
    filtro.addEventListener("change", filterExercises);
    valore.addEventListener("input", filterExercises);

    function filterExercises() {
        
        const filtroSelezionato = filtro.value;
        if( valore.value != "" ){
            const valoreInserito = valore.value.toLowerCase();
            
            switch(filtroSelezionato){

                case 'Materia':
                    esercizi.forEach(function (esercizio){
                        
                        $materiaEsercizio = esercizio.dataset.subject.toLowerCase()
                        if($materiaEsercizio.includes(valoreInserito)){

                            esercizio.style.display = "block"; // Mostra l'esercizio
                        }
                        else {

                            esercizio.style.display = "none"; // Nascondi l'esercizio
                        }
                        
                    })
                break;

                case 'Difficoltà':
                    esercizi.forEach(function (esercizio){
                        
                        $difficoltaEsercizio = esercizio.dataset.difficulty.toLowerCase()
                        if($difficoltaEsercizio.includes(valoreInserito)){

                            esercizio.style.display = "block"; // Mostra l'esercizio
                        }
                        else {
                            
                            esercizio.style.display = "none"; // Nascondi l'esercizio
                        }
                        
                    })
                break;

                case 'Tipologia':
                    esercizi.forEach(function (esercizio){
                        
                        $tipologiaEsercizio = esercizio.dataset.type.toLowerCase()
                        if($tipologiaEsercizio.includes(valoreInserito)){

                            esercizio.style.display = "block"; // Mostra l'esercizio
                        }
                        else {
                            
                            esercizio.style.display = "none"; // Nascondi l'esercizio
                        }
                        
                    })
                break;
            }
        }
        else{

            if( filtroSelezionato == "tutto" ){

                esercizi.forEach(function (esercizio){
                        
                   
                    esercizio.style.display = "block"; 
                    
                })
            }
        }
    }


    const checkboxes = document.querySelectorAll('input[type="checkbox"][name="exercise[]"]');
    let score = 0;
    // Aggiungi un listener per l'evento change a ciascuna casella di controllo
    checkboxes.forEach(function(checkbox) {
        checkbox.addEventListener("change", function() {
            score = 0;
            checkboxes.forEach(function(cb) {
                if (cb.checked) {
                    
                    score += parseInt(cb.dataset.score);
                }
            });

            document.getElementById('score').innerHTML = "<b>Score: " + score + '</b>';
            input = document.getElementById('score_input');
            input.setAttribute('value', score);

        });
    });
});
