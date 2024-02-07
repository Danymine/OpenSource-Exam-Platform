

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

    <form method="POST" action="{{ route('createAssistanceRequest') }}">
        @csrf

        <label for="name">Nome:</label>
        <input type="text" name="name" required>

        <label for="description">Problema:</label>
        <textarea name="description" required></textarea>

        <button type="submit">Invia Richiesta</button>
    </form>

</body>
</html>
