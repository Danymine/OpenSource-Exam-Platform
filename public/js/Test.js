function openModal() {
    document.getElementById('myModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('myModal').style.display = 'none';
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
    applyFilters(); // Applica i filtri resettati
}

function applyFilters() {
    var materia = document.getElementById('materiaInput').value.toLowerCase();
    var punteggioMin = parseInt(document.getElementById('punteggioMinInput').value) || '';
    var punteggioMax = parseInt(document.getElementById('punteggioMaxInput').value) || '';
    var difficolta = document.getElementById('difficoltaInput').value.toLowerCase();
    var table = document.getElementById('practice-table');
    var rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

    for (var i = 0; i < rows.length; i++) {
        var title = rows[i].getElementsByTagName('td')[0].textContent.toLowerCase();
        var materiaCell = rows[i].getElementsByTagName('td')[1].textContent.toLowerCase();
        var punteggiCells = rows[i].getElementsByTagName('td')[4].textContent.trim().split(' ').map(score => parseInt(score));
        var difficoltaCell = rows[i].getElementsByTagName('td')[2].textContent.toLowerCase();

        // Aggiungi questa condizione per verificare se la riga è vuota
        if (title === '' && materiaCell === '' && punteggiCells.length === 0 && difficoltaCell === '') {
            continue;
        }

        var showRow = true;

        if (materia !== '' && !(title.includes(materia) || materiaCell.includes(materia))) {
            showRow = false;
        }

        if ((punteggioMin !== '' || punteggioMax !== '') && punteggiCells.length > 0) {
            if (!punteggiCells.some(value => !isNaN(value) && (punteggioMin === '' || value >= punteggioMin) && (punteggioMax === '' || value <= parseInt(punteggioMax, 10)))) {
                showRow = false;
            }
        }

        if (difficolta !== '' && difficoltaCell !== difficolta) {
            showRow = false;
        }

        if (showRow) {
            rows[i].style.display = '';
        } else {
            rows[i].style.display = 'none';
        }
    }
}

var currentSortColumn = -1;
var currentSortDirection = 1; // 1 per ascendente, -1 per discendente

function sortTable(columnIndex) {
    var tbody = document.getElementById('table-body');
    var rows = Array.from(tbody.getElementsByTagName('tr'));

    // Imposta la direzione di ordinamento in base alla colonna cliccata
    if (currentSortColumn === columnIndex) {
        currentSortDirection *= -1; // Cambia la direzione di ordinamento se la stessa colonna viene cliccata di nuovo
    } else {
        currentSortColumn = columnIndex;
        currentSortDirection = 1;
    }

    // Effettua l'ordinamento delle righe
    rows.sort(function (a, b) {
        var aValue = getValueFromCell(a.cells[columnIndex]);
        var bValue = getValueFromCell(b.cells[columnIndex]);

        if (aValue < bValue) return -1 * currentSortDirection;
        if (aValue > bValue) return 1 * currentSortDirection;
        return 0;
    });

    // Rimuovi le righe dalla tabella
    while (tbody.firstChild) {
        tbody.removeChild(tbody.firstChild);
    }

    // Riaggiungi le righe nella nuova sequenza ordinata
    rows.forEach(function (row) {
        tbody.appendChild(row);
    });
}

function getValueFromCell(cell) {
    // Ottiene il valore dalla cella della tabella
    var value = cell.textContent.trim();

    // Gestisce il caso delle colonne 'Punteggio' e 'Data' per ordinamento numerico
    if (currentSortColumn === 3 || currentSortColumn === 4) {
        return parseFloat(value.replace(',', '.'));
    }

    // Gestisce il caso della colonna 'Difficoltà' per ordinamento personalizzato
    if (currentSortColumn === 2) {
        return getDifficultyValue(value.toLowerCase());
    }

    return value;
}

function getDifficultyValue(difficulty) {
    // Assegna un valore numerico alle difficoltà per poterle ordinare
    switch (difficulty) {
        case 'bassa':
            return 1;
        case 'media':
            return 2;
        case 'alta':
            return 3;
        default:
            return 0;
    }
}