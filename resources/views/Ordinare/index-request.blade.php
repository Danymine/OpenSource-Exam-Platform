
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elenco Richieste</title>
    
</head>
<body>
    <div class="container">
        <h2>Elenco Richieste</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Ruolo</th>
                    <th>Problema</th>
                </tr>
            </thead>
            <tbody>
                @foreach($requests as $request)
                    <tr>
                        <td>{{ $request->id }}</td>
                        <td>{{ $request->name }}</td>
                        <td>{{ $request->roles }}</td>
                        <td>{{ $request->description }}</td>
                      
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>