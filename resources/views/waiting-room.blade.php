<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Waiting Room</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-color: #f2f2f2;
        }

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

<div class="waiting-container">
    <div class="waiting-message">Attendi che il test inizi...</div>
    <div class="loading-spinner"></div>
</div>
<script>
    
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

    // Avvia il processo di aggiornamento
    fetchStatus();

</script>
</body>
</html>
