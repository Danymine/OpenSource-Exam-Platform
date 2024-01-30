

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Richiesta di Assistenza</title>
   
</head>
<body>

    <h2>Richiesta di Assistenza</h2>

    @if(session('success'))
        <div style="color: green;">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('richiesta-assistenza.store') }}">
        @csrf

        <label for="nome">Nome:</label>
        <input type="text" name="nome" required>

        <label for="ruolo">Ruolo:</label>
        <select name="ruolo" required>
            <option value="professore">Professore</option>
            <option value="studente">Studente</option>
        </select>

        <label for="problema">Problema:</label>
        <textarea name="problema" required></textarea>

        <button type="submit">Invia Richiesta</button>
    </form>

</body>
</html>
