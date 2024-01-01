<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Biblioteca di Esercizi</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <style>
    body {
      font-family: Arial, sans-serif;
    }

    .create-exercise-button {
      position: absolute;
      top: 10px;
      right: 10px;
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
 </style>
  <!-- Altri stili... -->
</head>


<h1>Biblioteca di Esercizi</h1>
<button class="create-exercise-button" onclick="location.href='{{ route('exercises.store') }}'">Crea Esercizio</button>

<table>
  <tr>
    <th>Nome dell'esercizio</th>
    <th>Tipo</th>
    <th>Difficoltà</th>
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

  </tr>
    

  @endforeach

</table>

