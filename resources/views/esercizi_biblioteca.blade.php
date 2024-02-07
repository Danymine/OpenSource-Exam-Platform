<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Biblioteca di Esercizi</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 20px;
    }

    h1 {
      margin-top: 0;
    }

    .create-exercise-button {
      position: absolute;
      top: 20px;
      right: 20px;
      padding: 10px 20px;
      background-color: #007bff;
      color: white;
      border: none;
      cursor: pointer;
      border-radius: 5px;
    }

    .create-exercise-button:hover {
      background-color: #0056b3;
    }

    .sorting-buttons {
      display: flex;
      justify-content: center;
      margin-bottom: 10px;
    }

    .sorting-buttons button {
      padding: 5px 10px;
      background-color: #f0f0f0;
      border: 1px solid #ccc;
      border-radius: 3px;
      cursor: pointer;
      font-size: 14px;
    }

    table {
      border-collapse: collapse;
      width: 100%;
    }

    th, td {
      border: 1px solid #ccc;
      padding: 8px;
      text-align: left;
    }

    th {
      background-color: #f0f0f0;
    }

    /* Custom buttons for sorting */

    .sort-button {
      background-color: #ccc;
      color: #333;
      border: none;
      cursor: pointer;
      padding: 5px 10px;
      border-radius: 3px;
    }

    .sort-button.asc {
      background-color: #28a745;
      color: white;
    }

    .sorting-buttons button:hover,
    .sort-button.asc:hover,
    .sort-button.desc:hover {
      background-color: #ddd;
    }

    .sort-button.desc {
      background-color: #dc3545;
      color: white;
    }

    .details-dialog {
      display: none;
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background-color: white;
      padding: 20px;
      border: 1px solid #ccc;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      z-index: 1000;
      width: 50%;
      max-width: 800px;
      overflow: auto;
    }

    .details-dialog h2 {
      margin-top: 0;
    }

    .details-dialog p {
      margin-bottom: 0;
    }
  </style>
</head>
  <body>

    <h1>Biblioteca di Esercizi</h1>

    <button class="create-exercise-button" onclick="location.href='{{ route('exercises.store') }}'">Crea Esercizio</button>

    <table id="exercise-table">
      <thead>
        <tr>
          <th>
            Nome dell'esercizio
            <button class="sort-button" id="sort-name-button" tabindex="0" onclick="sortTable(0)">Ordinamento Nomi</button>
          </th>
          <th>
            Tipo
            <button class="sort-button" id="sort-type-button" tabindex="1" onclick="sortTable(1)">Ordinamento Tipo</button>
          </th>
          <th>
            Difficoltà
            <button class="sort-button" id="sort-difficulty-button" tabindex="2" onclick="sortTable(2)">Ordinamento Difficoltà</button>
          </th>
          <th>
            Materia
            <button class="sort-button" id="sort-difficulty-button" tabindex="2" onclick="sortTable(3)">Ordinamento Materia</button>
          </th>
          <th>Dettagli</th>
          <th>Modifica</th>
          <th>Elimina</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($exercises as $exercise)
          
          <tr data-id="{{ $exercise->id }}">
            <td>{{ $exercise->name }}</td>
            <td>{{ $exercise->type }}</td>
            <td>{{ $exercise->difficulty }}</td>
            <td>{{ $exercise->subject }}</td>
            <td><a class="details-button" href="javascript:void(0)" onclick="showDetails('{{ $exercise->id }}')"><i class="fas fa-search"></i></a></td>
            <td><a href="javascript:void(0)" class="edit-button" onclick="editExercise('{{ $exercise->id }}')"><i class="fas fa-pencil-alt"></i></a></td>
            <td><a href="{{ route('deleteExercise', ['id' => $exercise->id]) }}" onclick="return confirm('Sei sicuro di voler eliminare questo esercizio?');"><i class="fas fa-trash-alt"></i></a></td>
          </tr>
        @endforeach
      </tbody>

    </table>

    <div id="details-dialog" class="details-dialog">

      <h2 id="details-title"></h2>
      <div id="details-content"></div>
      <button onclick="closeDetailsDialog()">Chiudi</button>
    </div>

    <div id="edit-dialog" class="details-dialog">

      <h2>Modifica Esercizio</h2>
      <form id="edit-exercise-form" method="POST">
        @csrf
        @method('PUT')

        <label for="edit-name">Nome:</label>
        <input type="text" id="edit-name" name="name"><br><br>

        <label for="edit-question">Domanda:</label>
        <textarea id="edit-question" name="question"></textarea><br><br>

        <label for="score">Punteggio:</label>
            <input type="text" id="score" name="score" value="{{ $exercise->score }}"><br><br>

                
            <label for="difficulty">Difficoltà:</label>
            <select id="difficulty" name="difficulty">
                <option value="Bassa">Bassa</option>
                <option value="Media">Media</option>
                <option value="Alta">Alta</option>
            </select><br><br>

            <label for="subject">Materia:</label>
            <input type="text" id="subject" name="subject" value="{{ $exercise->subject }}"><br><br>
            

            <label for="type">Tipo:</label>
            <select id="type" name="type">
                <option value="Risposta Aperta" {{ $exercise->type == 'Risposta Aperta' ? 'selected' : '' }}>Risposta Aperta</option>
                <option value="Risposta Multipla" {{ $exercise->type == 'Risposta Multipla' ? 'selected' : '' }}>Risposta Multipla</option>
                <option value="Vero o Falso" {{ $exercise->type == 'Vero o Falso' ? 'selected' : '' }}>Vero o Falso</option>
            </select><br><br>

            <div id="multiple_choice" style="display: {{ $exercise->type == 'Risposta Multipla' ? 'block' : 'none' }};">
                <label for="option1">Opzione 1:</label>
                <input type="text" id="option1" name="options[]" value="{{ $exercise->options[0] ?? '' }}"><br><br>

                <label for="option2">Opzione 2:</label>
                <input type="text" id="option2" name="options[]" value="{{ $exercise->options[1] ?? '' }}"><br><br>

                <label for="option3">Opzione 3:</label>
                <input type="text" id="option3" name="options[]" value="{{ $exercise->options[2] ?? '' }}"><br><br>

                <label for="option4">Opzione 4:</label>
                <input type="text" id="option4" name="options[]" value="{{ $exercise->options[3] ?? '' }}"><br><br>

                <label for="correct_option">Opzione corretta:</label>
                <input type="text" id="correct_option" name="correct_option" value="{{ $exercise->correct_option }}"><br><br>

                <label for="explanation">Spiegazione:</label>
                <textarea id="explanation" name="explanation">{{ $exercise->explanation }}</textarea><br><br>
            </div>
            <div id="true_false" style="display: {{ $exercise->type == 'Vero o Falso' ? 'block' : 'none' }};">
        <label for="correct_answer">Opzione corretta:</label>
        <select id="correct_answer" name="correct_answer">
            <option value="vero" {{ $exercise->correct_answer == 'vero' ? 'selected' : '' }}>Vero</option>
            <option value="falso" {{ $exercise->correct_answer == 'falso' ? 'selected' : '' }}>Falso</option>
        </select><br><br>

        <label for="explanation">Spiegazione:</label>
        <textarea id="explanation" name="explanation">{{ $exercise->explanation }}</textarea><br><br>
        </div>
        
        <button type="button" onclick="updateExercise()">Aggiorna Esercizio</button>
        <button type="button" onclick="cancelEditExercise()">Annulla Modifiche</button>
      </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
    var exercises = @json($exercises);
    var sortState = {}; // Oggetto per tenere traccia dello stato di ordinamento per ciascuna colonna

    function sortTable(columnIndex) {
      var table, rows, switching, i, x, y, shouldSwitch;
      table = document.getElementById("exercise-table");
      switching = true;

      // Inizializza lo stato di ordinamento per la colonna se non è già presente
      if (!sortState.hasOwnProperty(columnIndex)) {
        sortState[columnIndex] = "asc";
      } else {
        // Se lo stato di ordinamento esiste, inverti l'ordine
        sortState[columnIndex] = sortState[columnIndex] === "asc" ? "desc" : "asc";
      }

      while (switching) {
        switching = false;
        rows = table.rows;

        for (i = 1; i < (rows.length - 1); i++) {
          shouldSwitch = false;
          x = rows[i].getElementsByTagName("td")[columnIndex];
          y = rows[i + 1].getElementsByTagName("td")[columnIndex];

          var xValue = x.innerHTML.trim();
          var yValue = y.innerHTML.trim();

          // Verifica lo stato di ordinamento e inverti la logica in base a "asc" o "desc"
          var compareResult;
          switch (columnIndex) {
            case 0: // Sorting by Name
              compareResult = xValue.localeCompare(yValue);
              break;
            case 1: // Sorting by Type
              var typeOrder = ["Vero o Falso", "Risposta Multipla", "Risposta Aperta"];
              var xIndex = typeOrder.indexOf(xValue);
              var yIndex = typeOrder.indexOf(yValue);
              compareResult = xIndex - yIndex;
              break;
            case 2: // Sorting by Difficulty
              var difficultyOrder = ["Bassa", "Media", "Alta"];
              var xIndex = difficultyOrder.indexOf(xValue);
              var yIndex = difficultyOrder.indexOf(yValue);
              compareResult = xIndex - yIndex;
              break;
              case 3: // Sorting by subject
              compareResult = xValue.localeCompare(yValue);
              break;
          }

          // Inverti il risultato se l'ordinamento è "desc"
          if (sortState[columnIndex] === "desc") {
            compareResult = -compareResult;
          }

          if (compareResult > 0) {
            shouldSwitch = true;
            break;
          }
        }

        if (shouldSwitch) {
          rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
          switching = true;
        }
      }
    }
    function showDetails(exerciseId) {
      // Trova l'esercizio corrispondente nell'array $exercises
      var exercise = null;
      for (var i = 0; i < exercises.length; i++) {
        if (exercises[i].id == exerciseId) {
          exercise = exercises[i];
          break;
        }
      }

      if (exercise) {
        // Ottenere riferimenti agli elementi DOM della finestra di dialogo
        var dialog = document.getElementById('details-dialog');
        var titleElement = document.getElementById('details-title');
        var contentElement = document.getElementById('details-content');

        // Popola il titolo e il contenuto della finestra di dialogo in base al tipo di esercizio
        titleElement.textContent = exercise.name;
        contentElement.innerHTML = `
          <p><strong>Difficoltà:</strong> ${exercise.difficulty}</p>
          <p><strong>Materia:</strong> ${exercise.subject}</p>
          <p><strong>Tipo:</strong> ${exercise.type}</p>
        `;

        // Aggiungi informazioni specifiche in base al tipo di esercizio
        if (exercise.type === 'Vero o Falso') {
          console.log('Vero o Falso');
          contentElement.innerHTML += `
            <p><strong>Risposta Corretta:</strong> ${exercise.correct_option}</p>
            <p><strong>Spiegazione:</strong> ${exercise.explanation}</p>
          `;
        }  else if (exercise.type === 'Risposta Multipla') {
          console.log('Risposta Multipla');
          contentElement.innerHTML += `
            <p><strong>Risposta Corretta:</strong> ${exercise.correct_option}</p>
            <p><strong>Opzione 1:</strong> ${exercise.option_1}</p>
            <p><strong>Opzione 2:</strong> ${exercise.option_2}</p>
            <p><strong>Opzione 3:</strong> ${exercise.option_3}</p>
            <p><strong>Opzione 4:</strong> ${exercise.option_4}</p>
            <p><strong>Spiegazione:</strong> ${exercise.explanation}</p>
          `;
        } else if (exercise.type === 'Risposta Aperta') {
          console.log('Risposta Aperta');
          contentElement.innerHTML += `
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
    function editExercise(exerciseId) {
      // Trova l'esercizio corrispondente nell'array $exercises
      var exercise = null;
      for (var i = 0; i < exercises.length; i++) {
        if (exercises[i].id == exerciseId) {
          exercise = exercises[i];
          break;
        }
      }

      if (exercise) {
        // Popola il form di modifica con i dati dell'esercizio
        document.getElementById('edit-exercise-form').setAttribute('action', '{{ route('exercises.update', '') }}/' + exercise.id);
        document.getElementById('edit-name').value = exercise.name;
        document.getElementById('edit-question').value = exercise.question;
        // Aggiungi altre righe per popolare gli altri campi del form

        // Mostra la finestra di dialogo
        document.getElementById('edit-dialog').style.display = 'block';
      }
    }

    function cancelEditExercise() {
      // Chiudi la finestra di dialogo senza inviare il form
      document.getElementById('edit-dialog').style.display = 'none';
    }

    function updateExercise() {
      // Invia il form al server per l'aggiornamento
      document.getElementById('edit-exercise-form').submit();
    }
    const typeSelect = document.getElementById('type');
    const multipleChoiceFields = document.getElementById('multiple_choice');
    const trueFalseFields = document.getElementById('true_false');

    typeSelect.addEventListener('change', () => {
      if (typeSelect.value === 'Risposta Multipla') {
        multipleChoiceFields.style.display = 'block';
        trueFalseFields.style.display = 'none';
      } else if (typeSelect.value === 'Vero o Falso') {
        multipleChoiceFields.style.display = 'none';
        trueFalseFields.style.display = 'block';
      } else {
        multipleChoiceFields.style.display = 'none';
        trueFalseFields.style.display = 'none';
      }
    });

    // Mostra i campi appropriati quando la pagina viene caricata
    typeSelect.dispatchEvent(new Event('change'));
    </script>
  </body>
</html>