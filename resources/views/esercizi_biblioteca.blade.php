<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Biblioteca di Esercizi</title>
  <style>
    /* Stili... */
  </style>
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
    <td>{{ $exercise['nome'] }}</td>
    <td>{{ $exercise['tipo'] }}</td>
    <td>{{ $exercise['difficolta'] }}</td>
    <td><span class="pencil">Matita</span></td>
    <td><span class="trash">Cestino</span></td>
  </tr>
  @endforeach
</table>

</body>
</html>

