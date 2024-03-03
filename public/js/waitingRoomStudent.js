function fetchStatus() {
    fetch(route, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {

        if (data.status === 'allowed') {

            // Reindirizza l'utente alla view test
            window.location.href = routeNext;

        } 
        else if (data.status === 'kicked') {

            // Reindirizza l'utente alla dashboard
            window.location.href = routeDashboard;
        } 
        else {
            
            setTimeout(fetchStatus, 5000);
        }
    })    
    .catch(error => console.error("Errore nell'aggiornamento ", error));
}

fetchStatus(); 