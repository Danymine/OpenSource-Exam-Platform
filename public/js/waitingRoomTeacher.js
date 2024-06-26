function populateStudentsTable2(data) {
    // Ottieni il riferimento al corpo della tabella
    const tableBody = document.getElementById('students-table-body');
    
    // Svuota il corpo della tabella
    tableBody.innerHTML = '';

    // Controlla se data è vuoto
    if (data.length === 0) {
        // Aggiungi una riga di avviso nella tabella
        tableBody.innerHTML = `
            <tr>
                <td colspan="5">${nsp}</td>
            </tr>
        `;
    } 
    else {

        // Itera sui dati degli studenti e crea le righe della tabella
        data.forEach(student => {

            const statusMap = {
                "wait": ina,
                "execute": svl,
            };

            const colorMap = {
                "wait": "red",
                "execute": "orange",
                "Consegnato": "green",
            }
            
            stats = statusMap[student.status] || cgt;
            color = colorMap[student.status];
            
            let row = `
                <tr id="student-${student.id}">
                    <td style="vertical-align: middle;">${student.name}</td>
                    <td style="vertical-align: middle;">${student.first_name}</td>
                    <td style="vertical-align: middle; color:${color};">${stats}</td>
            `;
            if( student.status !== "Consegnato" ){

                row += ` <td>
                            <button class="btn btn-danger" onclick="kickStudent('${student.id}')">${esp}</button>
                        </td> `;
            }
            if (student.status === "wait") {
                row += `
                    <td>
                        <button class="btn btn-success" onclick="allowedStudent('${student.id}')">${apr}</button>
                    </td>
                `;
            }
            row += `</tr>`;
            tableBody.innerHTML += row;
        });        
    }
}

function populateStudentsTable(data) {
    // Ottieni il riferimento al corpo della tabella
    const tableBody = document.getElementById('students-table-body');
    
    // Svuota il corpo della tabella
    tableBody.innerHTML = '';

    // Controlla se data è vuoto
    if (data.length === 0) {
        // Aggiungi una riga di avviso nella tabella
        tableBody.innerHTML = `
            <tr>
                <td colspan="4">${nsp}</td>
            </tr>
        `;
    } 
    else {

        // Itera sui dati degli studenti e crea le righe della tabella
        data.forEach(student => {
            const row = `
                <tr id="student-${student.id}">
                    <td style="vertical-align: middle;">${student.name}</td>
                    <td style="vertical-align: middle;">${student.first_name}</td>
                    <td style="vertical-align: middle;">${ina}</td>
                    <td>
                        <button class="btn btn-danger" onclick="kickStudent('${student.id}')">${esp}</button>
                    </td>
                </tr>
            `;
            tableBody.innerHTML += row;
        });        
    }
}

function fetchStudents() {
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
        // Controlla se "participants" è definito nell'oggetto data
        if (data && data.participants) {
            if( data.participants.status_practice === false ){
                populateStudentsTable(data.participants.data);
            }
            else{

                populateStudentsTable2(data.participants.data);
            }
        } else {
            console.error("Errore: Dati dei partecipanti non validi");
        }
        setTimeout(fetchStudents, 5000);
    })
    .catch(error => console.error("Errore nell'aggiornamento ", error));
}

function kickStudent(studentId) {
    fetch(`/kick/${studentId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
    })
    .then(response => {
        if (response.ok) {
            // Rimuovi lo studente dalla tabella o aggiorna la pagina
            removeStudentFromTable(studentId);
        } else {
            console.error("Errore durante l'espulsione dello studente");
        }
    })
    .catch(error => console.error("Errore nell'invio della richiesta ", error));
}

function removeStudentFromTable(studentId) {

    const row = document.getElementById(`student-${studentId}`);
    if (row) {
        row.remove();
    }
}

function allowedStudent(studentId){

    fetch(`/allowed/${studentId}`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
    })
    .then(response => {
        if (response.ok) {
            // Rimuovi lo studente dalla tabella o aggiorna la pagina
            removeStudentFromTable(studentId);
        } else {
            console.error("Errore durante l'espulsione dello studente");
        }
    })
    .catch(error => console.error("Errore nell'invio della richiesta ", error));
}

fetchStudents(); 
