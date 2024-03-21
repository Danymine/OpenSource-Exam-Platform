var exameTitles = Array.from(document.querySelectorAll(".exame_title"));
var exameValutation = Array.from(document.querySelectorAll(".exame_valutation"));
var exameDate = Array.from(document.querySelectorAll(".exame_date"));

for (var i = exameValutation.length - 1; i >= 0; i--) {

    var val = parseInt(exameValutation[i].textContent.trim());
    if (isNaN(val)) {

        exameTitles.splice(i, 1);
        exameValutation.splice(i, 1);
        exameDate.splice(i, 1);
    }
}

var exameTitlesArray = Array.from(exameTitles).map(title => title.textContent);
var exameValutationArray = Array.from(exameValutation).map(val => parseFloat(val.textContent));
var exameDateArray = Array.from(exameDate).map(date => date.textContent);

var exameCanvasId = 'Chartexame';
var exameCtx = document.getElementById(exameCanvasId).getContext('2d');

var exameChart = new Chart(exameCtx, {
    type: 'line',
    data: {
        labels: exameDateArray,
        datasets: [{
            label: exame + " " + translations,
            data: exameValutationArray,
            borderColor: '#007bff', 
            pointBackgroundColor: '#007bff',
            pointBorderColor: '#007bff',
            fill: false,
            lineTension: 0
        }]
    },
    options: {
        legend: {
            labels: {
                fontColor: '#007bff', 
                color: '#007bff'
            }
        },
        scales: {
            yAxes: [{
                ticks: {
                    min: 0,
                    max: 100
                }
            }]
        },
        tooltips: {
            callbacks: {
                title: function(tooltipItem, data) {
                    var index = tooltipItem[0].index;
                    return exameTitlesArray[index];
                },
                label: function(tooltipItem, data) {
                    return translations + tooltipItem.yLabel;
                }
            }
        }
    }
});

/**
 * Sezione Relativa alla creazione del Grafico "Esercitazioni" per lo Studente
 *
 * 
 */
var titles = Array.from(document.querySelectorAll(".practice_title"));
var valutation = Array.from(document.querySelectorAll(".practice_valutation"));
var date = Array.from(document.querySelectorAll(".practice_date"));

for (var i = valutation.length - 1; i >= 0; i--) {

    var val = parseInt(valutation[i].textContent.trim());
    if (isNaN(val)) {

        titles.splice(i, 1);
        valutation.splice(i, 1);
        date.splice(i, 1);
    }
}

var titlesArray = Array.from(titles).map(title => title.textContent);
var valutationArray = Array.from(valutation).map(val => parseFloat(val.textContent));
var dateArray = Array.from(date).map(date => date.textContent);

var canvasId = 'Chartpractice';
var ctx = document.getElementById(canvasId).getContext('2d');

var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: dateArray,
        datasets: [{
            label: practice + " " + translations,
            data: valutationArray,
            borderColor: '#007bff',
            pointBackgroundColor: '#007bff',
            fill: false,
            lineTension: 0
        }]
    },
    options: {
        legend: {
            labels: {
                fontColor: '#007bff',
                color: '#007bff'
            }
        },
        scales: {
            yAxes: [{
                ticks: {
                    min: 0,
                    max: 100
                }
            }]
        },
        tooltips: {
            callbacks: {
                title: function(tooltipItem, data) {
                    var index = tooltipItem[0].index;
                    return titlesArray[index];
                },
                label: function(tooltipItem, data) {
                    return "Valutazione: " + tooltipItem.yLabel;
                }
            }
        }
    }
});


/**
 * Sezione Relativa ai bottoni per visualizzare i dati corretti.
 *
 * 
 */
function showExamsStudent() {
    
        

    var chart = document.getElementById("Chartpractice");
    if( chart != null ){

        chart.style.display = "none";
        chart = document.getElementById("Chartexame").style.display = "block";
    }
    showRows("exame");

}

function showPracticesStudent() {

    var chart = document.getElementById("Chartexame");
    if( chart != null ){

        chart.style.display = "none";
        chart = document.getElementById("Chartpractice").style.display = "block";
    }
    showRows("practice");
}

function showRows(type) {

    var rows = document.querySelectorAll(".row-type");

    rows.forEach(function(row) {

        if (row.classList.contains("row-" + type)) {

            row.style.display = "table-row";
        } else {
            row.style.display = "none";
        }
    });
}

