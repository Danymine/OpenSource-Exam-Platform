
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if (Auth::User()->roles == 'Student')

                        <h2>Ciao, {{ Auth::User()->name }}</h2>

                        <button id="esamiButton" class="button" style="display: inline-block" onclick="showExams()">Esami</button>
                        <button id="esercitazioniButton" class="button" style="display: inline-block" onclick="showPractices()">Esercitazioni</button>

                        <div class="relative overflow-x-auto">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">
                                            Nome
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Data
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Voto
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach(Auth::user()->delivereds as $delivered)
                                        
                                        @php
                                            $practice = $delivered->practice()->withTrashed()->first();
                                        @endphp
                                        
                                        @if( $practice->type == "esercitazione")

                                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 row-type row-practice clickable" onclick="window.location='{{ route('view-details-delivered', ['delivered' =>  $delivered ] ) }}'">
                                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                    <span class="practice_title"> {{  $practice->title }} </span>
                                                </th>
                                                @if( $delivered->valutation != NULL )
                                                    <td class="px-6 py-4">
                                                        <span class="practice_date"> {{ $practice->practice_date }} </span>
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        <span class="practice_valutation" > {{ $delivered->valutation }} </span>
                                                    </td>
                                                @else
                                                    <td class="px-6 py-4">
                                                        <span> {{ $practice->practice_date }} </span>
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        <span> Non valutata </span>
                                                    </td>
                                                @endif
                                            </tr>

                                        @else

                                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 row-type row-exame clickable" onclick="window.location='{{ route('view-details-delivered', ['delivered' =>  $delivered ] ) }}'">
                                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                    <span class="exame_title"> {{ $practice->title }} </span>
                                                </th>
                                                @if( $delivered->valutation != NULL and $practice->public == 1)
                                                    <td class="px-6 py-4">
                                                        <span class="exame_date"> {{ $practice->practice_date }} </span>
                                                    </td>

                                                    @if( $delivered->valutation >= $practice->total_score * 0.6)

                                                        <td class="px-6 py-4">
                                                            <span class="exame_valutation" > {{ $delivered->valutation }} </span>
                                                        </td>
                                                    @else
                                                        <td class="px-6 py-4">
                                                            <span> Insufficiente </span>
                                                        </td>
                                                    @endif
                                                @else
                                                    <td class="px-6 py-4">
                                                        <span> {{ $practice->practice_date }} </span>
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        <span> Non valutata </span>
                                                    </td>
                                                @endif
                                            </tr>

                                        @endif
                                    @endforeach
                                </tbody>
                            </table>

                            <canvas id="Chartexame" style="width:100%;max-width:600px"></canvas>

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

                            <canvas id="Chartpractice" style="width:100%;max-width:600px; display: none;"></canvas>

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

                    @elseif(Auth::User()->roles == 'Teacher')

                        <h2>Benvenut Docente {{ Auth::User()->name }}</h2>

                        <button id="esamiButton" class="button" style="display: inline-block" onclick="showExams()">Esami</button>
                        <button id="esercitazioniButton" class="button" style="display: inline-block" onclick="showPractices()">Esercitazioni</button>
                        <a href="{{ route('exame-passed') }}" id="StoricoEsami" >Storico Esami</a>
                        <a href="{{ route('practice-passed') }}" style="display: none;" id="StoricoEsercitazioni">Storico Esercitazioni</a>

                        <div class="relative overflow-x-auto">
                            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">
                                            Nome
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Data
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Materia
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach(Auth::user()->practices()->withTrashed()->get() as $practice)
                    
                                        @if( $practice->type == "esame" )

                                            @foreach( $practice->delivereds as $delivered )

                                                @if( $delivered->valutation == NULL )
                                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 row-type row-exame clickable" onclick="window.location='{{ route('view-delivered', ['practice' =>  $practice ] ) }}'">
                                                        <!--Mi serve la gestione degli per adesso farò come se i dispari sono esami e i pari esercitazioni così per capirci -->
                                                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                            <span> {{ $practice->title }} </span>
                                                        </th>
                                                        <td class="px-6 py-4">
                                                            {{ $practice->practice_date }}
                                                        </td>
                                                        <td class="px-6 py-4">
                                                            {{ $practice->subject }}
                                                        </td>
                                                    </tr>
                                                    @break
                                                @endif
                                            @endforeach
                                        
                                        @else

                                            @foreach( $practice->delivereds as $delivered )

                                                @if( $delivered->valutation == NULL )

                                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 row-type row-practice clickable" onclick="window.location='{{ route('view-delivered', ['practice' =>  $practice  ] ) }}'">
                                                        <!--Mi serve la gestione degli per adesso farò come se i dispari sono esami e i pari esercitazioni così per capirci -->
                                                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                            <span> {{ $practice->title }} </span>
                                                        </th>
                                                        <td class="px-6 py-4">
                                                            {{ $practice->practice_date }}
                                                        </td>
                                                        <td class="px-6 py-4">
                                                            {{ $practice->subject }}
                                                        </td>
                                                    </tr>
                                                    @break
                                                @endif
                                            @endforeach
                
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                         </div>
                    @else
                        <h2>Benvenuto Amministratore {{ Auth::User()->name }}</h2>
                        
                        
                        <div>
                            <a href="{{ route('show-add-user-form') }}" class="button">Aggiungi Utente</a>
                            <a href="{{ route('user-list') }}" class="button">Gestione Utenti</a>

                        </div>
                        
            
                    @endif
                </div>

    </div>
</x-app-layout>
