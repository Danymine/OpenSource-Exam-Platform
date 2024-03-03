<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="font-semibold text-xl leading-tight">
                {{ __('Statistiche', ['title' => $practice->title]) }}
            </h4>
            <div>
                @if( $practice->type == "Exam")
                    <a href="{{ route('exame-passed') }}" class="btn btn-info">{{ __('Torna Indietro') }}</a>
                @else
                    <a href="{{ route('practice-passed') }}" class="btn btn-info">{{ __('Torna Indietro') }}</a>
                @endif
            </div>
        </div>
        <hr stile="border-top: 1px solid #000000; width: 90%;" />
    </x-slot>

    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="chart-container">
                    <h5 class="mb-4">{{ __('Valutazioni Partecipanti') }}</h5>
                    <canvas id="barChart"></canvas>
                </div>
            </div>
            <div class="col-md-6">
                <div class="chart-container">
                    <h5 class="mb-4">{{ __('Valutazioni Generale') }}</h5>
                    <canvas id="pieChart"></canvas>
                </div>
            </div>
        </div>
    </div>


    <script>
        // Ottieni i dati dalla variabile PHP
        var delivereds = <?php echo json_encode($delivereds); ?>;
        var totalValuations = {{ $delivereds->pluck('valutation')->sum() }};
        var averageValuation = totalValuations / {{ $delivereds->count() }}; 
        var str = "{{ __('Voto') }}";   

        // Prepara i dati per il grafico
        var labels = [];
        var scores = [];

        // Estrai i dati dalle delivereds
        delivereds.forEach(function(delivered) {
            name = delivered.user.name + " " +  delivered.user.first_name;
            labels.push(name); 
            scores.push(delivered.valutation);
        });

        // Crea il grafico a barre
        var ctx = document.getElementById('barChart').getContext('2d');
        var barChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: str,
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
        
        function generateSoftColors(count) {
            var softColors = [];
            
            for (var i = 0; i < count; i++) {
                var hue = Math.floor(Math.random() * 360); // genera un valore casuale per l'HSL
                var saturation = Math.floor(Math.random() * 30) + 70; // saturazione in una gamma meno vivace (70-100)
                var lightness = Math.floor(Math.random() * 20) + 60; // luminosità in una gamma più scura (60-80)
                
                var color = 'hsla(' + hue + ',' + saturation + '%,' + lightness + '%, 0.8)';
                softColors.push(color);
            }

            return softColors;
        }
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
        var colors = generateSoftColors(colorCount);

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
</x-app-layout>