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
                                    @php
                                        $i = 0;
                                    @endphp
                                    <!--Qui serve gestire le delivereds che si riferiscono a una practices o ad un esame "cancellato" -->
                                    @foreach(Auth::user()->delivereds as $delivered)
                                        @if( $i % 2 == 0)
                                            @if( $delivered->valutation == NULL )

                                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 row-type row-exame clickable" onclick="window.location='{{ route('view-details-delivered', ['delivered' =>  $delivered ] ) }}'">
                                                    <!--Mi serve la gestione degli per adesso farò come se i dispari sono esami e i pari esercitazioni così per capirci -->
                                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                        <span> {{ $delivered->practice->title }} </span>
                                                    </th>
                                                    <td class="px-6 py-4">
                                                        {{ $delivered->practice->practice_date }}
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        <span> Non valutata </span>
                                                    </td>
                                                </tr>
                                            @else
                                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 row-type row-exame clickable" onclick="window.location='{{ route('view-details-delivered', ['delivered' =>  $delivered ] ) }}'">
                                                    <!--Mi serve la gestione degli per adesso farò come se i dispari sono esami e i pari esercitazioni così per capirci -->
                                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                        <span class="exame_title"> {{ $delivered->practice->title }} </span>
                                                    </th>
                                                    <td class="px-6 py-4">
                                                        {{ $delivered->practice->practice_date }}
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        <span class="exame_valutation"> {{ $delivered->valutation }} </span>
                                                    </td>
                                                </tr>
                                            @endif
                                        @else

                                            @if( $delivered->valutation == NULL )

                                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 row-type row-practice clickable" onclick="window.location='{{ route('view-details-delivered', ['delivered' =>  $delivered ] ) }}'">
                                                    <!--Mi serve la gestione degli per adesso farò come se i dispari sono esami e i pari esercitazioni così per capirci -->
                                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                        <span> {{ $delivered->practice->title }} </span>
                                                    </th>
                                                    <td class="px-6 py-4">
                                                        {{ $delivered->practice->practice_date }}
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        <span> Non valutata </span>
                                                    </td>
                                                </tr>
                                            @else
                                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 row-type row-practice clickable" onclick="window.location='{{ route('view-details-delivered', ['delivered' =>  $delivered ] ) }}'">
                                                    <!--Mi serve la gestione degli per adesso farò come se i dispari sono esami e i pari esercitazioni così per capirci -->
                                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                        <span class="practice_title"> {{ $delivered->practice->title }} </span>
                                                    </th>
                                                    <td class="px-6 py-4">
                                                        {{ $delivered->practice->practice_date }}
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        <span class="practice_valutation"> {{ $delivered->valutation }} </span>
                                                    </td>
                                                </tr>
                                            @endif

                                        @endif
                                        @php
                                            $i += 1;
                                        @endphp
                                    @endforeach
                                </tbody>
                            </table>

                            <canvas id="Chartexame" style="width:100%;max-width:600px"></canvas>

                            <script>

                                // Ottieni i titoli e le valutazioni dal DOM
                                var titles = document.querySelectorAll(".exame_title");
                                var valutation = document.querySelectorAll(".exame_valutation");

                                var xValues = Array.from(titles).map(title => title.textContent);
                                var yValues = Array.from(valutation).map(valuation => parseInt(valuation.textContent));

                                // Crea il grafico
                                new Chart("Chartexame", {
                                    type: "line",
                                    data: {
                                        labels: xValues,
                                        datasets: [{
                                            fill: false,
                                            lineTension: 0,
                                            backgroundColor: "rgba(255, 255, 255, 0.2)", // Sfondo bianco con opacità
                                            borderColor: "rgba(0, 128, 0, 1.0)", // Colore verde dei pallini
                                            pointBackgroundColor: "rgba(0, 128, 0, 1.0)", // Colore dei pallini
                                            data: yValues,
                                            label: 'Valutazioni' // Etichetta per il dataset
                                        }]
                                    },
                                    options: {
                                        scales: {
                                            yAxes: [{
                                                ticks: {
                                                    min: Math.min(...yValues),  //... scandisce l'intero vettore.
                                                    max: Math.max(...yValues) 
                                                }
                                            }],
                                        }
                                    }
                                    });

                            </script>

                            <canvas id="Chartpractice" style="width:100%;max-width:600px; display: none;"></canvas>

                            <script>

                                // Ottieni i titoli e le valutazioni dal DOM
                                var titles = document.querySelectorAll(".practice_title");
                                var valutation = document.querySelectorAll(".practice_valutation");

                                // Mappa i valori delle valutazioni da stringhe a numeri
                                xValues = Array.from(titles).map(title => title.textContent);
                                yValues = Array.from(valutation).map(valuation => parseInt(valuation.textContent)); // Converti le stringhe in numeri

                                // Crea il grafico
                                new Chart("Chartpractice", {
                                type: "line",
                                data: {
                                    labels: xValues,
                                    datasets: [{
                                        fill: false,
                                        lineTension: 0,
                                        backgroundColor: "rgba(255, 255, 255, 0.2)", // Sfondo bianco con opacità
                                        borderColor: "rgba(0, 128, 0, 1.0)", // Colore verde dei pallini
                                        pointBackgroundColor: "rgba(0, 128, 0, 1.0)", // Colore dei pallini
                                        data: yValues,
                                        label: 'Valutazioni' // Etichetta per il dataset
                                    }]
                                },
                                options: {
                                    scales: {
                                        yAxes: [{
                                            ticks: {
                                                min: Math.min(...yValues), // Trova il minimo dei valori numerici
                                                max: Math.max(...yValues) // Trova il massimo dei valori numerici
                                            }
                                        }],
                                    }
                                }
                                });

                            
                            </script>

                        </div>

                    @elseif(Auth::User()->roles == 'Teacher')

                        <h2>Benvenut Docente {{ Auth::User()->name }}</h2>

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
                                            Materia
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i = 0;
                                    @endphp
                                    <!--Qui serve gestire le delivereds che si riferiscono a una practices o ad un esame "cancellato" per il docente questa opezazione è molto dispendiosa in termini di risorse -->
                                    @foreach(Auth::user()->practices as $practice)
                                        @if( $i % 2 == 0)
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
                                        @php
                                            $i += 1;
                                        @endphp
                                    @endforeach
                                </tbody>
                            </table>
                         </div>
                    @else
                        <h2>Benvenuto Amministratore {{ Auth::User()->name }}</h2>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
