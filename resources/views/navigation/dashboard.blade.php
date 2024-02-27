
<x-app-layout>

    <x-slot name="header">
        @if( Auth::user()->roles == "Student")
            <div class="d-flex justify-content-between align-items-center">
                <h4>{{ __('Dashboard') }}</h4>
                <form action="{{ route('pratices.join') }}" method="POST" class="form-inline">
                    @csrf
                    <input type="text" id="key" name="key" class="form-control mr-3 equal-height" placeholder="{{ __('Inserisci la chiave') }}">
                    <button type="submit" class="btn btn-primary equal-height">{{ __('Partecipa') }}</button>
                </form>
            </div>
            <hr stile="border-top: 1px solid #000000; width: 90%;" />
        @else
            <x-slot name="header">
            <h4>
                {{ __('Dashboard') }}
            </h4>
            <hr stile="border-top: 1px solid #000000; width: 90%;" />
        @endif
    </x-slot>


    @if( Auth::user()->roles == "Teacher" )
        
        <div class="container">
            @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif
        </div>
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <div class="container">
                        <div class="row">
                            <!-- Primo elemento: bottoni Esami ed Esercitazioni -->
                            <div class="col">
                                <div class="btn-group" role="group" aria-label="Bottoni Esami ed Esercitazioni">
                                    <button type="button" class="btn btn-success rounded  mr-2" onclick="showExams()">{{ __('Esami') }}</button>
                                    <button type="button" class="btn btn-success rounded " onclick="showPractices()">{{ __('Esercitazioni') }}</button>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <!-- Secondo elemento: tabella -->
                            <div class="col">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">{{ __('Nome') }}</th>
                                            <th scope="col">{{ __('Data') }}</th>
                                            <th scope="col">{{ __('Alunni mancanti') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach( Auth::user()->practices()->withTrashed()->get() as $practice )

                                            @foreach( $practice->delivereds as $delivered )

                                                @if( $delivered->valutation === NULL )

                                                    @if ( $practice->type  == "Exam" )

                                                        <tr class="exame">
                                                            <td>
                                                                <a href="{{ route('view-delivered', ['practice' => $practice]) }}">
                                                                    {{ $practice->title }}
                                                                </a>
                                                            </td>
                                                            <td>{{ $practice->practice_date }}</td>
                                                            <td>
                                                                {{ count($practice->delivereds->filter(function ($delivered) {
                                                                    return $delivered->valutation === NULL;
                                                                })) }}
                                                            </td>
                                                        </tr>
                                                    @else
                                                        
                                                        <tr class="practice" style="display: none">
                                                            <td>
                                                                <a href="{{ route('view-delivered', ['practice' => $practice]) }}">{{ $practice->title }}</a>
                                                            </td>
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
        <script>

            // Filtra gli eventi per mostrare solo quelli con data uguale o successiva a oggi
            events = {!! json_encode(Auth::user()->practices()->where('practice_date', '>=', now()->toDateString())->get()->map(function ($practice) {
                return [
                    'title' => $practice->title,
                    'start' => $practice->practice_date,
                    'url' => route('waiting-room', ['key' => $practice->key]),
                ];
            })) !!};

            document.addEventListener('DOMContentLoaded', function() {
                var calendarEl = document.getElementById('calendar');

                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    events: events,
                    eventClick: function(info) {
                        if (info.event.url) {
                            window.location.href = info.event.url; // Reindirizza alla URL dell'evento
                        }
                    }
                });

                calendar.render();
            });
        </script>

    @elseif( Auth::user()->roles == "Student" )
        
        <div class="container">
            @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif
        </div>
        <div class="container">
            <h4>Ciao, {{ Auth::User()->name }}</h4>
                <div class="row">
                    <div class="col">
                        <button id="esamiButton" class="btn btn-primary" onclick="showExamsStudent()">{{ __('Esami') }}</button>
                        <button id="esercitazioniButton" class="btn btn-primary" onclick="showPracticesStudent()">{{ __('Esercitazioni') }}</button>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col">
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">{{ __('Nome') }}</th>
                                        <th scope="col">{{ __('Data') }}</th>
                                        <th scope="col">{{ __('Voto') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(Auth::user()->delivereds as $delivered)
                                        @php
                                            $practice = $delivered->practice()->withTrashed()->first();
                                        @endphp

                                        @if( $practice->type == "Practice")

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
</script>
