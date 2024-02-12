 // Recupero i container
 var trueFalseContainer = document.getElementById('true_false_container');
 var multipleChoiceContainer = document.getElementById('multiple_choice_container');

 var typeSelect = document.getElementById('type');
 
 typeSelect.addEventListener('change', function() {
     
     if (typeSelect.value === 'Risposta Multipla') {

         multipleChoiceContainer.style.display = 'block';
         trueFalseContainer.style.display = 'none';
         buildMultipleChoiceOptions();
     }
     else if (typeSelect.value === 'Vero o Falso') {

         trueFalseContainer.style.display = 'block';
         multipleChoiceContainer.style.display = 'none';

         // Aggiungi gli input per la risposta corretta e la spiegazione
         const correctAnswerLabel = document.createElement('label');
         correctAnswerLabel.setAttribute('for', 'correct_answer');
         correctAnswerLabel.textContent = 'Risposta corretta: ';

         const correctAnswerSelect = document.createElement('select');
         correctAnswerSelect.setAttribute('id', 'correct_answer');
         correctAnswerSelect.setAttribute('name', 'correct_option');

         const optionVero = document.createElement('option');
         optionVero.setAttribute('value', 'Vero');
         optionVero.textContent = 'Vero';

         const optionFalso = document.createElement('option');
         optionFalso.setAttribute('value', 'Falso');
         optionFalso.textContent = 'Falso';

         correctAnswerSelect.appendChild(optionVero);
         correctAnswerSelect.appendChild(optionFalso);

         const explanationLabel = document.createElement('label');
         explanationLabel.setAttribute('for', 'explanation');
         explanationLabel.textContent = 'Spiegazione: ';

         const explanationInput = document.createElement('input');
         explanationInput.setAttribute('type', 'text');
         explanationInput.setAttribute('id', 'explanation');
         explanationInput.setAttribute('name', 'explanation');

         trueFalseContainer.appendChild(correctAnswerLabel);
         trueFalseContainer.appendChild(correctAnswerSelect);
         trueFalseContainer.appendChild(document.createElement('br'));
         trueFalseContainer.appendChild(explanationLabel);
         trueFalseContainer.appendChild(explanationInput);
         trueFalseContainer.appendChild(document.createElement('br'));
     }
     else {
         // Nascondi il contenitore delle opzioni di risposta multipla se il tipo non è "Risposta Multipla"
         multipleChoiceContainer.style.display = 'none';
         trueFalseContainer.style.display = 'none';
     }
 });

 // Funzione per costruire il contenuto delle opzioni di risposta multipla
 function buildMultipleChoiceOptions() {
     // Numero di opzioni per la domanda a scelta multipla
     const numOptions = 4;

     // Cancella eventuali opzioni preesistenti
     multipleChoiceContainer.innerHTML = '';

     // Ciclo per creare gli input per le opzioni
     for (let i = 1; i <= numOptions; i++) {
         const label = document.createElement('label');
         label.setAttribute('for', `option${i}`);
         label.textContent = `Opzione ${i}: `;
         
         const input = document.createElement('input');
         input.setAttribute('type', 'text');
         input.setAttribute('id', `option${i}`);
         input.setAttribute('name', 'options[]');
         
         multipleChoiceContainer.appendChild(label);
         multipleChoiceContainer.appendChild(input);
         multipleChoiceContainer.appendChild(document.createElement('br'));
     }

     // Aggiungi l'input per l'opzione corretta
     const correctOptionLabel = document.createElement('label');
     correctOptionLabel.setAttribute('for', 'correct_option');
     correctOptionLabel.textContent = 'Opzione corretta: ';

     const correctOptionInput = document.createElement('input');
     correctOptionInput.setAttribute('type', 'text');
     correctOptionInput.setAttribute('id', 'correct_option');
     correctOptionInput.setAttribute('name', 'correct_option');

     multipleChoiceContainer.appendChild(correctOptionLabel);
     multipleChoiceContainer.appendChild(correctOptionInput);
     multipleChoiceContainer.appendChild(document.createElement('br'));

     // Aggiungi l'input per la spiegazione
     const explanationLabel = document.createElement('label');
     explanationLabel.setAttribute('for', 'explanation');
     explanationLabel.textContent = 'Spiegazione: ';

     const explanationInput = document.createElement('input');
     explanationInput.setAttribute('type', 'text');
     explanationInput.setAttribute('id', 'explanation');
     explanationInput.setAttribute('name', 'explanation');

     multipleChoiceContainer.appendChild(explanationLabel);
     multipleChoiceContainer.appendChild(explanationInput);
     multipleChoiceContainer.appendChild(document.createElement('br'));
 }