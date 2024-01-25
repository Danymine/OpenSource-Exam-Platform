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
  </style>
</head>
<body>

<h1>Biblioteca di Esercizi</h1>

<button class="create-exercise-button" onclick="location.href='{{ route('exercises.store') }}'">Crea Esercizio</button>

<table id="exercise-table">
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
    <th>Modifica</th>
    <th>Elimina</th>
  </tr>
  </thead>
  <tbody>
    @foreach ($exercises as $exercise)
      <tr>
        <td>{{ $exercise->name }}</td>
        <td>{{ $exercise->type }}</td>
        <td>{{ $exercise->difficulty }}</td>
        <td>{{ $exercise->subject }}</td>
        <td><a href="{{ route('editExercise', ['id' => $exercise->id]) }}"><i class="fas fa-pencil-alt"></i></a></td>
        <td><a href="{{ route('deleteExercise', ['id' => $exercise->id]) }}" onclick="return confirm('Sei sicuro di voler eliminare questo esercizio?');"><i class="fas fa-trash-alt"></i></a></td>
      </tr>
    @endforeach
  </tbody>
</table>

<script>
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
</script>
</body>
</html>