<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Biblioteca di Esercizi</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <!-- Altri stili... -->
</head>
<body>

<h1>Biblioteca di Esercizi</h1>

<table>
  <tr>
    <th>Nome dell'esercizio</th>
    <th>Tipo</th>
    <th>Difficoltà</th>
    <th>Matita</th>
    <th>Cestino</th>
  </tr>
  @foreach ($exercises as $exercise)
  <tr>
    <td>{{ $exercise->name }}</td>
    <td>{{ $exercise->type }}</td>
    <td>{{ $exercise->difficulty }}</td>
    <td><span class="pencil"><i class="fas fa-pencil-alt"></i></span></td>
    <td><span class="trash"><i class="fas fa-trash-alt"></i></span></td>
  </tr>
  @endforeach
</table>

</body>
</html>

