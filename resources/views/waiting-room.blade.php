<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Waiting Room</title>
    <style>

        .waiting-container {
            text-align: center;
        }

        .waiting-message {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .loading-spinner {
            border: 6px solid #f3f3f3;
            border-top: 6px solid #3498db;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    @if(Auth::user()->roles == 1 and $practices->user_id == Auth::user()->id)
        <div class="waiting-container">
            <div class="waiting-message">Salve docente sei nella gestione della waiting room per il test {{ $practices->title }}</div>
        </div>
        <p id="prova"></p>
        @if (\Session::has('success'))
            <div class="alert alert-success">
                <p><b>{{ \Session::get('success') }}</b></p>
            </div>
        @endif
        <div>
            <a href="{{ route('empower', ['key' => $practices->key]) }}">Consenti Accesso</a>
        </div>
    @else
        <div class="waiting-container">
                <div class="waiting-message">Attendi che il test inizi...</div>
                <div class="loading-spinner"></div>
        </div>
    @endif
<script>
    
    // Avvia il processo di aggiornamento
    @if( Auth::user()->roles != 1 or Auth::user()->id != $practices->user_id)
        function fetchStatus() {
            fetch("{{ route('status', ['key' => $practices->key]) }}", {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                // Aggiorna la pagina o apporta altre modifiche in base alla risposta del server
                if (data.status === 1) {
                    // Reindirizza l'utente alla view test
                    window.location.href = "{{ route('test', ['key' => $practices->key]) }}";
                }
                else{
                    // Chiama nuovamente la funzione dopo 5 secondi (5000 millisecondi)
                    setTimeout(fetchStatus, 5000);
                }
            })
            .catch(error => console.error("Errore nell'aggiornamento ", error));
        }

        fetchStatus(); 
    @else

    function fetchUser() {
            fetch("{{ route('user', ['key' => $practices->key]) }}", {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                // Aggiorna la pagina o apporta altre modifiche in base alla risposta del server
               
                document.getElementById('prova').innerHTML = data.user;
                setTimeout(fetchUser, 5000);
                
            })
            .catch(error => console.error("Errore nell'aggiornamento ", error));
        }

        fetchUser(); 
    @endif

</script>
</body>
</html>
