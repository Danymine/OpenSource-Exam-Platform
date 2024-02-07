<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Charts</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }
        .chart-container {
            flex-basis: 48%; /* Imposta la larghezza massima del contenitore del grafico */
            margin-bottom: 20px; /* Spazio tra i grafici */
        }

    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="chart-container">
            <h1 class="mb-4">Bars</h1>
            <canvas id="barChart"></canvas>
        </div>
        <div class="chart-container">
            <h1 class="mb-4">Torta</h1>
            <canvas id="pieChart"></canvas>
        </div>
    </div>

    <script>
        // Ottieni i dati dalla variabile PHP
        var delivereds = <?php echo json_encode($delivereds); ?>;
        var totalValuations = {{ $delivereds->pluck('valutation')->sum() }};
        var averageValuation = totalValuations / {{ $delivereds->count() }};    

        // Prepara i dati per il grafico
        var labels = [];
        var scores = [];

        // Estrai i dati dalle delivereds
        delivereds.forEach(function(delivered) {
            labels.push(delivered.user.name); 
            scores.push(delivered.valutation);
        });

        // Crea il grafico a barre
        var ctx = document.getElementById('barChart').getContext('2d');
        var barChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Voto',
                    data: scores,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        max: {!! $practice->total_score !!}, // Valore massimo per l'asse Y
                        grid: {
                            drawBorder: false,
                            lineWidth: 2,
                            color: 'rgba(255,255,255, 0.9)',
                        }
                    }
                },
                barPercentage: 0.1,
            }
        });
    </script>


    <script>
        var pieCanvas = document.getElementById('pieChart').getContext('2d');
        
        /*
        pluck('valutation'): Estrae il valore della chiave valutation da ciascun elemento della collezione $delivereds, restituendo una nuova collezione contenente solo i valori di 
        valutation.

        groupBy(function($val) { return $val; }): Raggruppa i valori estratti in precedenza in base al valore stesso. In pratica, crea un array associativo in cui le chiavi sono i valori 
        di valutation e i valori sono un array contenente tutti gli elementi con lo stesso valore di valutation.

        map->count(): Per ciascun gruppo di valori di valutation, conta il numero di elementi presenti nel gruppo.

        values(): Restituisce solo i valori dell'array associativo risultante, ignorando le chiavi.

        */
        var colorCount = new Set({{ $delivereds->pluck('valutation')->toJson() }}).size;
        var colors = [];
        for ( var i = 0; i < colorCount; i++ ) {

            colors.push('rgba(' + Math.floor(Math.random() * 256) + ',' + Math.floor(Math.random() * 256) + ',' + Math.floor(Math.random() * 256) + ', 0.8)');
        }

        var colorsJson = JSON.stringify(colors);

        var pieData = {
            labels: {!! json_encode($delivereds->pluck('valutation')->groupBy(function($val) { return $val; })->map->count()->keys()) !!},
            datasets: [{
                label: 'Numero di Studenti',
                data: {!! json_encode($delivereds->pluck('valutation')->groupBy(function($val) { return $val; })->map->count()->values()) !!},
                backgroundColor: JSON.parse(colorsJson),
                borderColor: JSON.parse(colorsJson),
                borderWidth: 1,
            }]
        };

        var pieChart = new Chart(pieCanvas, {
            type: 'pie',
            data: pieData,
            options: {
                responsive: false,
                legend: {
                    position: 'top',
                },
            }
        });
    </script>
</body>
</html>
