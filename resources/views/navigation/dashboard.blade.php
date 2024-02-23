
<x-app-layout>

    <x-slot name="header">
        <h4>
            {{ __('Dashboard') }}
        </h4>
        <hr stile="border-top: 1px solid #000000; width: 90%;" />
    </x-slot>

    @if( Auth::user()->roles == "Teacher" )
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <div class="container">
                        <div class="row">
                            <!-- Primo elemento: bottoni Esami ed Esercitazioni -->
                            <div class="col">
                                <div class="btn-group" role="group" aria-label="Bottoni Esami ed Esercitazioni">
                                    <button type="button" class="btn btn-success rounded  mr-2" onclick="showExams()">Esami</button>
                                    <button type="button" class="btn btn-success rounded " onclick="showPractices()">Esercitazioni</button>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <!-- Secondo elemento: tabella -->
                            <div class="col">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Nome</th>
                                            <th scope="col">Data</th>
                                            <th scope="col">Alunni mancanti</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach( Auth::user()->practices()->withTrashed()->get() as $practice )

                                            @foreach( $practice->delivereds as $delivered )

                                                @if( $delivered->valutation == NULL )

                                                        @if ( $practice->type  == "esame" )

                                                            <tr class="exame">
                                                                <td>{{ $practice->title }}e</td>
                                                                <td>{{ $practice->practice_date  }}</td>
                                                                <td>
                                                                    {{ 
                                                                        count($practice->delivereds->filter(function ($delivered) {
                                                                            return $delivered->valutation === NULL;
                                                                        }));
                                                                    }}
                                                                </td>
                                                            </tr>
                                                        @else
                                                            
                                                            <tr class="practice" style="display: none">
                                                                <td>{{ $practice->title }}e</td>
                                                                <td>{{ $practice->practice_date  }}</td>
                                                                <td>
                                                                    {{ 
                                                                        count($practice->delivereds->filter(function ($delivered) {
                                                                            return $delivered->valutation === NULL;
                                                                        }));
                                                                    }}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @break
                                                @endif
                                            @endforeach
                                        @endforeach
                                            
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <!-- Contenuto del secondo elemento -->
                    <div id='calendar'></div>
                </div>
            </div>
        </div>
    @elseif( Auth::user()->roles == "Student" )
        
        <div class="container">
            <h4>Ciao, {{ Auth::User()->name }}</h4>
                <div class="row">
                    <div class="col">
                        <button id="esamiButton" class="btn btn-primary" onclick="showExams()">Esami</button>
                        <button id="esercitazioniButton" class="btn btn-primary" onclick="showPractices()">Esercitazioni</button>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col">
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">Nome</th>
                                        <th scope="col">Data</th>
                                        <th scope="col">Voto</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(Auth::user()->delivereds as $delivered)
                                        @php
                                            $practice = $delivered->practice()->withTrashed()->first();
                                        @endphp

                                        @if( $practice->type == "esercitazione")

                                            <tr class="clickable row-type row-practice" onclick="window.location='{{ route('view-details-delivered', ['delivered' =>  $delivered ] ) }}'">
                                                <td>{{  $practice->title }}</td>
                                                @if( $delivered->valutation != NULL and $practice->public == 1 )

                                                    <td>{{ $practice->practice_date }}</td>
                                                    <td>{{ $delivered->valutation }}</td>
                                                @else

                                                    <td>{{ $practice->practice_date }}</td>
                                                    <td>Non valutata</td>
                                                @endif
                                            </tr>
                                        @else
                                        <tr class="clickable row-type row-exame" onclick="window.location='{{ route('view-details-delivered', ['delivered' =>  $delivered ] ) }}'">
                                            <td>{{ $practice->title }}</td>
                                            @if( $delivered->valutation != NULL and $practice->public == 1)
                                                <td>{{ $practice->practice_date }}</td>
                                                @if( $delivered->valutation >= $practice->total_score * 0.6)
                                                    <td>{{ $delivered->valutation }}</td>
                                                @else
                                                    <td>Insufficiente</td>
                                                @endif
                                            @else
                                                <td>{{ $practice->practice_date }}</td>
                                                <td>Non valutata</td>
                                            @endif
                                        </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col">
                        <canvas id="Chartexame" style="max-width: 600px;"></canvas>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col">
                        <canvas id="Chartpractice" style="max-width: 600px; display: none;"></canvas>
                    </div>
                </div>
            </div>

            <script>

                var exameTitles = document.querySelectorAll(".exame_title");
                var exameValutation = document.querySelectorAll(".exame_valutation");
                var exameDate = document.querySelectorAll(".exame_date");

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
                            label: 'Valutazioni',
                            data: exameValutationArray,
                            backgroundColor: 'rgba(255, 255, 255)', // Sfondo bianco con opacità
                            borderColor: 'rgba(255, 99, 132, 1)', // Colore verde della linea
                            pointBackgroundColor: 'rgba(255, 99, 132, 1)', // Colore dei pallini per tutte le esercitazioni
                            fill: false,
                            lineTension: 0
                        }]
                    },
                    options: {
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
                                    return "Valutazione: " + tooltipItem.yLabel;
                                }
                            }
                        }
                    }
                });


            </script>

            <script>

                // Ottieni i titoli e le valutazioni dal DOM
                var titles = document.querySelectorAll(".practice_title");
                var valutation = document.querySelectorAll(".practice_valutation");
                var date = document.querySelectorAll(".practice_date");
                
                // Estrai i dati dalle variabili fornite
                var titlesArray = Array.from(titles).map(title => title.textContent);
                var valutationArray = Array.from(valutation).map(val => parseFloat(val.textContent));
                var dateArray = Array.from(date).map(date => date.textContent);

                // Definisci l'ID del canvas
                var canvasId = 'Chartpractice';

                // Ottieni il contesto del canvas
                var ctx = document.getElementById(canvasId).getContext('2d');

                // Crea il grafico
                var myChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: dateArray,
                        datasets: [{
                            label: 'Valutazioni',
                            data: valutationArray,
                            backgroundColor: 'rgba(255, 255, 255)', // Sfondo bianco con opacità
                            borderColor: 'rgba(54, 162, 235, 1))', // Colore verde della linea
                            pointBackgroundColor: 'rgba(54, 162, 235, 1)', // Colore dei pallini per tutte le esercitazioni
                            fill: false,
                            lineTension: 0
                        }]
                    },
                    options: {
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


            
            </script>

        </div>
        
    @else
        <!--Amministratore -->
        <h2>Benvenuto Amministratore {{ Auth::User()->name }}</h2>
                                       
        <div>
            <a href="{{ route('show-add-user-form') }}" class="button">Aggiungi Utente</a>
            <a href="{{ route('user-list') }}" class="button">Gestione Utenti</a>
        </div>
    @endif
                    
</x-app-layout>
<script>
    events = {!! json_encode(Auth::user()->practices()->get()->map(function ($practice) {
        return [
            'title' => $practice->title,
            'start' => $practice->practice_date, // Assumendo che $practice->practice_date sia nel formato corretto
        ];
    })) !!};    
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: events
    });

    calendar.render();
    });

</script>
<script>

    function showExams(){

        practices = document.getElementsByClassName("practice");;
        for (var i = 0; i < practices.length; i++) {
            practices[i].style.display = 'none';
        }
        exame = document.getElementsByClassName("exame");;
        for (var i = 0; i < exame.length; i++) {
            exame[i].style.display = 'table-row';
        }
        
    }
    
    function showPractices(){

        exams = document.getElementsByClassName("exame");;
        for (var i = 0; i < exams.length; i++) {
            exams[i].style.display = 'none';
        }
        practices = document.getElementsByClassName("practice");;
        for (var i = 0; i < practices.length; i++) {
            practices[i].style.display = 'table-row';
        }

    }    
</script>
<script>
/*
function showExams() {
    
        @if ( Auth::user()->roles == "Teacher" || Auth::user()->roles == "Admin")

            var story = document.getElementById('StoricoEsercitazioni');
            if( story != null ){

                story.style.display = "none";
                story = document.getElementById('StoricoEsami').style.display = "block";
            }
            showRows("exame");
        @else

            var chart = document.getElementById("Chartpractice");
            if( chart != null ){

                chart.style.display = "none";
                chart = document.getElementById("Chartexame").style.display = "block";
            }
            showRows("exame");

        @endif
}

function showPractices() {

    @if ( Auth::user()->roles == "Teacher" || Auth::user()->roles == "Admin")

        var story = document.getElementById('StoricoEsami');
        if( story != null ){

            story.style.display = "none";
            story = document.getElementById('StoricoEsercitazioni').style.display = "block";
        }
        showRows("practice");
    @else

        var chart = document.getElementById("Chartexame");
        if( chart != null ){

            chart.style.display = "none";
            chart = document.getElementById("Chartpractice").style.display = "block";
        }
        showRows("practice");
    @endif
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
*/
</script>
