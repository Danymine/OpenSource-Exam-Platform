var sortDirection = []; // Array per tenere traccia dell'ordinamento per ciascuna colonna
var language = "translate";
console.log(translations);

function sortTable(columnIndex) {
    var table, rows, switching, i, x, y, shouldSwitch;
    table = document.getElementById("practice-table");
    switching = true;

    if (!sortDirection[columnIndex]) {
        sortDirection[columnIndex] = "asc";
    } else {
        sortDirection[columnIndex] = sortDirection[columnIndex] === "asc" ? "desc" : "asc";
    }

    while (switching) {
        switching = false;
        rows = table.rows;

        for (i = 1; i < (rows.length - 1); i++) {
            shouldSwitch = false;

            if (columnIndex === 4) { // Indice della colonna dello score
                x = parseInt(rows[i].getElementsByTagName("td")[columnIndex].innerHTML);
                y = parseInt(rows[i + 1].getElementsByTagName("td")[columnIndex].innerHTML);
            } else if (columnIndex === 2) { // Indice della colonna della difficoltà
                x = difficultyToNumber(rows[i].getElementsByTagName("td")[columnIndex].innerHTML);
                y = difficultyToNumber(rows[i + 1].getElementsByTagName("td")[columnIndex].innerHTML);
            } else {
                x = rows[i].getElementsByTagName("td")[columnIndex].innerHTML.toLowerCase();
                y = rows[i + 1].getElementsByTagName("td")[columnIndex].innerHTML.toLowerCase();
            }

            if (sortDirection[columnIndex] === "desc") {
                if (x < y) {
                    shouldSwitch = true;
                    break;
                }
            } else {
                if (x > y) {
                    shouldSwitch = true;
                    break;
                }
            }
        }

        if (shouldSwitch) {
            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
            switching = true;
        }
    }

    updateSortIcons(columnIndex);
}

function difficultyToNumber(difficulty) {
    switch (difficulty.toLowerCase()) {
        case translations['translate']["Bassa"].toLowerCase():
            return 1;
        case translations['translate']["Media"].toLowerCase():
            return 2;
        case translations['translate']["Alta"].toLowerCase():
            return 3;
        default:
            return 0;
    }
}

function updateSortIcons(columnIndex) {
    var header = document.getElementById("practice-table").getElementsByTagName("th")[columnIndex];
    var icon = header.getElementsByTagName("i")[0];
    icon.classList.remove("fa-chevron-up", "fa-chevron-down");

    if (columnIndex === 2) {
        if (sortDirection[columnIndex] === "asc") {
            icon.classList.add("fa-chevron-down"); // Ordine inverso per la difficoltà
        } else {
            icon.classList.add("fa-chevron-up"); // Ordine ascendente per la difficoltà
        }
    } else {
        if (sortDirection[columnIndex] === "asc") {
            icon.classList.add("fa-chevron-up");
        } else {
            icon.classList.add("fa-chevron-down");
        }
    }
}

function toggleFilterModal() {
    var filterSection = document.getElementById('filterSection');
    var resetButton = document.querySelector('.btn-secondary');
    if (filterSection.style.display === 'none') {
        filterSection.style.display = 'block';
        resetButton.style.display = 'inline-block'; // Mostra il pulsante di reset
    } else {
        filterSection.style.display = 'none';
        resetButton.style.display = 'none'; // Nascondi il pulsante di reset
        resetFilters(); // Resetta i filtri quando il modulo dei filtri viene chiuso
    }
}

function resetFilters() {
    document.getElementById('materiaInput').value = '';
    document.getElementById('punteggioMinInput').value = '';
    document.getElementById('punteggioMaxInput').value = '';
    document.getElementById('difficoltaInput').value = '';
    applyFilters(); // Apply the reset filters
}

function applyFilters() {
    
    //Catturo il valore dai filtri 
    var materia = document.getElementById('materiaInput').value.toLowerCase();
    var difficolta = document.getElementById('difficoltaInput').value.toLowerCase();
    
    var punteggioMin = document.getElementById('punteggioMinInput').value;
    var punteggioMax = document.getElementById('punteggioMaxInput').value;

    //Catturo la tabella e le righe nella quale andremo ad applicare i filtri
    var table = document.getElementById('practice-table');
    var rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    console.log(rows[0].getElementsByTagName('td'));

    for (var i = 0; i < rows.length; i++) {

        //Prendiamo i valori della Riga in posizione I.
        var materiaCell = rows[i].getElementsByTagName('td')[1].textContent.toLowerCase();
        var difficoltaCell = rows[i].getElementsByTagName('td')[2].textContent.toLowerCase();
        
        //Utilizzeremo questa variabile come "Sentinella" che ci dirà SE mostrare la riga o meno.
        var showRow = true; 

        //Controllo sulla Materia
        if ( materia !== '' && !materiaCell.includes(materia) ) {

            showRow = false;
        }

        //Controllo sul Punteggio Minimo
        if ( punteggioMin !== '' && parseInt(punteggioMin) > 0 ) {

            var punteggioCell = parseInt(rows[i].getElementsByTagName('td')[4].textContent);
            if (isNaN(punteggioCell) || punteggioCell < parseInt(punteggioMin)) {

                showRow = false;
            }
        }

        //Controllo sul Punteggio Massimo
        if (punteggioMax !== '' && parseInt(punteggioMax) > 0) {

            var punteggioCell = parseInt(rows[i].getElementsByTagName('td')[4].textContent);
            if (isNaN(punteggioCell) || punteggioCell > parseInt(punteggioMax)) {

                showRow = false;
            }
        }

        //Controllo sulla Difficoltà
        if (difficolta !== '' && difficolta !== 'tutte le difficoltà' && difficoltaCell !== difficolta) {

            showRow = false;
        }

        if (showRow) {

            rows[i].style.display = '';
        } 
        else {
           
            rows[i].style.display = 'none';
        }
    }
}