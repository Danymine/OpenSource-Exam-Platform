<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Biblioteca di Esercizi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css">
    
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
    
  @if($errors->any())
                            <h4 style="color: white">{{$errors->first()}}</h4>
                        @endif

    <h1>Biblioteca di Esercizi</h1>

    <button class="create-exercise-button" onclick="location.href='{{ route('exercises.store') }}'">Crea Esercizio</button>

    <table id="exercise-table">

        <thead>

          <tr>
              <!-- Column -->
              <th onclick="sortTable(0)">Nome dell'esercizio <i class="fas fa-chevron-down"></i></th>
              <th onclick="sortTable(1)">Tipo <i class="fas fa-chevron-down"></i></th>
              <th onclick="sortTable(2)">Difficoltà <i class="fas fa-chevron-down"></i></th>
              <th onclick="sortTable(3)">Materia <i class="fas fa-chevron-down"></i></th>
              <th>Dettagli</th>
              <th>Modifica</th>
              <th>Elimina</th>
          </tr>
        </thead>
        <tbody>

          @foreach ($exercises as $exercise)

            <tr data-id="{{ $exercise->id }}">

                <!-- Data -->
                <td>{{ $exercise->name }}</td>
                <td>{{ $exercise->type }}</td>
                <td>{{ $exercise->difficulty }}</td>
                <td>{{ $exercise->subject }}</td>

                <!-- Function -->
                <td><a class="details-button" onclick="showDetails('{{ $exercise->id }}')"><i class="fas fa-search"></i></a></td>

                <td><a class="edit-button" onclick="editExercise('{{ $exercise->id }}')"><i class="fas fa-pencil-alt"></i></a></td>

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
        <input type="text" id="score" name="score"><br><br>

        <label for="difficulty">Difficoltà:</label>
        <select id="difficulty" name="difficulty">

            <option value="Bassa">Bassa</option>
            <option value="Media">Media</option>
            <option value="Alta">Alta</option>
        </select><br><br>

        <label for="subject">Materia:</label>
        <input type="text" id="subject" name="subject"><br><br>
            
        
        <label for="type">Tipo:</label>
        <select id="type" name="type">

            <option value="Risposta Aperta">Risposta Aperta</option>
            <option value="Risposta Multipla">Risposta Multipla</option>
            <option value="Vero o Falso">Vero o Falso</option>
        </select><br><br>


        <div id="multiple_choice">

            <label for="option1">Opzione 1:</label>
            <input type="text" id="option1" name="options[]"><br><br>

            <label for="option2">Opzione 2:</label>
            <input type="text" id="option2" name="options[]"><br><br>

            <label for="option3">Opzione 3:</label>
            <input type="text" id="option3" name="options[]"><br><br>

            <label for="option4">Opzione 4:</label>
            <input type="text" id="option4" name="options[]"><br><br>

            <label for="correct_option">Opzione corretta:</label>
            <input type="text" id="correct_option" name="correct_option"><br><br>

            <label for="explanation">Spiegazione:</label>
            <textarea id="explanation_multiplo" name="explanation"></textarea><br><br>
        </div>

        <div id="true_false">

          <label for="correct_answer">Opzione corretta:</label>
          <select id="correct_answer" name="correct_option">

              <option value="Vero">Vero</option>
              <option value="Falso">Falso</option>
          </select><br><br>

          <label for="explanation">Spiegazione:</label>
          <textarea id="explanation" name="explanation"></textarea><br><br>
        </div>

        <button type="button" onclick="updateExercise()">Aggiorna Esercizio</button>
        <button type="button" onclick="cancelEditExercise()">Annulla Modifiche</button>
      </form>
      </div>

    <script>
      var exercises = @json($exercises);
    </script>
    <script src="/js/tableSorting.js"></script>

  </body>
</html>