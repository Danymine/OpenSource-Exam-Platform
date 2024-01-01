<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Biblioteca di Esercizi</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <!-- Altri stili... -->
</head>


<h1>Biblioteca di Esercizi</h1>

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
    <td><a href="{{ route('deleteExercise', ['id' => $exercise->id]) }}"><i class="fa-solid fa-trash-can"></i></a></td>

  </tr>
    

  @endforeach

</table>

