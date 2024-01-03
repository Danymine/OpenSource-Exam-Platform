<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Biblioteca di Esercizi</title>
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
      justify-content: space-between;
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
  </style>
</head>
<body>

<h1>Biblioteca di Esercizi</h1>

<button class="create-exercise-button" onclick="location.href='{{ route('exercises.store') }}'">Crea Esercizio</button>




<table id="exercise-table">
  <tr>
    <th>
      Nome dell'esercizio
      <select id="sort-name" onchange="applySort()">
        <option value="default">Seleziona</option>
        <option value="asc">Nome dell'esercizio A-Z</option>
        <option value="desc">Nome dell'esercizio Z-A</option>
      </select>
      <button onclick="sortTable(0, document.getElementById('sort-name').value)">Ordina</button>
    </th>
    <th>
      Tipo
      <select id="sort-type" onchange="applySort()">
        <option value="default">Seleziona</option>
        <option value="Risposta Multipla">Risposta Multipla</option>
        <option value="Vero o Falso">Vero o Falso</option>
        <option value="Risposta Aperta">Risposta Aperta</option>
      </select>
      <button onclick="sortTable(1, document.getElementById('sort-type').value)">Ordina</button>
    </th>
    <th>
      Difficoltà
      <select id="sort-difficulty" onchange="applySort()">
        <option value="default">Seleziona</option>
        <option value="Bassa">Bassa</option>
        <option value="Media">Media</option>
        <option value="Alta">Alta</option>
      </select>
      <button onclick="sortTable(2, document.getElementById('sort-difficulty').value)">Ordina</button>
    </th>
    <th>Modifica</th>
    <th>Elimina</th>
  </tr>
  
@foreach ($exercises as $exercise)
  <tr>
    <td>{{ $exercise->name }}</td>
    <td>{{ $exercise->type }}</td>
    <td>{{ $exercise->difficulty }}</td>
    <td><a href="{{ route('editExercise', ['id' => $exercise->id]) }}"><i class="fas fa-pencil-alt"></i></a></td>
    <td><a href="{{ route('deleteExercise', ['id' => $exercise->id]) }}" onclick="return confirm('Sei sicuro di voler eliminare questo esercizio?');"><i class="fas fa-trash-alt"></i></a></td>
  </tr>
@endforeach

</table>
<script>
function sortTable(columnIndex, sortOrder) {
  var table, rows, switching, i, shouldSwitch;
  table = document.getElementById("exercise-table");
  switching = true;

  while (switching) {
    switching = false;
    rows = table.rows;

    for (i = 1; i < rows.length - 1; i++) {
      shouldSwitch = false;
      var x = rows[i].getElementsByTagName("td")[columnIndex];
      var y = rows[i + 1].getElementsByTagName("td")[columnIndex];

      if (sortOrder !== "default") {
        var xValue = x.innerHTML.toLowerCase();
        var yValue = y.innerHTML.toLowerCase();

        if (columnIndex === 1) { // Ordinamento per Tipo
          var typeOrder = ["Risposta Aperta", "Risposta Multipla", "Vero o Falso"];
          var xIndex = typeOrder.indexOf(xValue);
          var yIndex = typeOrder.indexOf(yValue);

          if (
            (sortOrder === "asc" && xIndex > yIndex) ||
            (sortOrder === "desc" && xIndex < yIndex)
          ) {
            shouldSwitch = true;
            break;
          }
        } else if (columnIndex === 2) { // Ordinamento per Difficoltà
          var difficultyOrder = ["Bassa", "Media", "Alta"];
          var xIndex = difficultyOrder.indexOf(xValue);
          var yIndex = difficultyOrder.indexOf(yValue);

          if (
            (sortOrder === "asc" && xIndex > yIndex) ||
            (sortOrder === "desc" && xIndex < yIndex)
          ) {
            shouldSwitch = true;
            break;
          }
        } else { // Ordinamento per la colonna Nome
          if (
            (sortOrder === "asc" && xValue > yValue) ||
            (sortOrder === "desc" && xValue < yValue)
          ) {
            shouldSwitch = true;
            break;
          }
        }
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
