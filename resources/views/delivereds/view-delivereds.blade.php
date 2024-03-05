<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="font-semibold text-xl leading-tight">
                {{ __('Lista Consegne') }}
            </h4>
            <div>
                <a href="{{ route('dashboard') }}" class="btn btn-info">{{ __('Torna Indietro') }}</a>
                <!--Qui si potrebbe aggiungere un stampa gli studenti che hanno consegnato -->
            </div>
        </div>
        <hr style="border-top: 1px solid #0000004a width: 90%;" />
    </x-slot>

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
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">{{ __('Studente') }}</th>
                    <th scope="col">{{ __('Data') }}</th>
                    <th scope="col">{{ __('Voto') }}</th>
                    <th scope="col">{{ __('Dettagli') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($delivereds as $delivered)
                    <tr>
                        <td>
                            <button style="font-size:20px; border: 0; background-color: #f8f9fa; color: blue; cursor: pointer;" onclick="mostraGrafico({{ $delivered->user_id }})"></i> {{ $delivered->user->name }} </button>
                        </td>          
                        <div id="overlay{{ $delivered->user_id }}" class="overlay-chart"></div>

                        <!-- Modulo di aggiunta nascosto inizialmente -->
                        <div id="aggiungiModulo{{ $delivered->user_id }}" class="bg-light p-4 aggiungiModulo">
                            <h2 class="mb-4"> {{ $delivered->user->name }} </h2>
                            <canvas id="ChartStudent{{ $delivered->user_id }}" style="width:100%;max-width:600px"></canvas>

                            <script>
                                // Ottieni le valutazioni delle consegne dell'utente su cui ha cliccato il docente 
                                var deliveredsData = {!! json_encode($delivered->user->delivereds->toArray()) !!};
                                var xDate = [];
                                var yValutation = [];
                                for( var i = 0; i < deliveredsData.length; i++ ){
                                    
                                    if( deliveredsData[i].valutation !== null ){
                                        yValutation.push(deliveredsData[i].valutation);
                                        var dataOra = new Date(deliveredsData[i].created_at);
                                        xDate.push(dataOra.toISOString().split('T')[0]);
                                    }
                                }
                                
                                new Chart("ChartStudent{{ $delivered->user_id }}", {
                                        type: "line",
                                        data: {
                                            labels: xDate,
                                            datasets: [{
                                                fill: false,
                                                lineTension: 0,
                                                backgroundColor: "rgba(255, 255, 255, 0.2)", // Sfondo bianco con opacità
                                                borderColor: "rgba(0, 128, 0, 1.0)", // Colore verde dei pallini
                                                pointBackgroundColor: "rgba(0, 128, 0, 1.0)", // Colore dei pallini
                                                data: yValutation,
                                                label: 'Valutazioni' // Etichetta per il dataset
                                            }]
                                        },
                                        options: {
                                            scales: {
                                                yAxes: [{ticks: {min: 1, max: 100}}],
                                            }
                                        }
                                    });
                            </script>

                            <button id="annullaAggiunta{{ $delivered->user_id }}" class="btn btn-info ml-2" style="font-size:20px" onclick="nascondiGrafico({{ $delivered->user_id }})" >{{ __('Esci') }}</button>         
                        </div>
                        <td> {{ $delivered->practice->practice_date }} </td>
                        @if( $delivered->valutation === NULL )

                            <td> {{ __('Nessuna Valutazione') }} </td>
                        @else

                            <td> {{ $delivered->valutation }}</td>
                        @endif
                        <td><a href="{{ route('show-delivered', ['delivered' => $delivered]) }}"><i class="fas fa-search"></i></a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if( $delivered->practice->public == 0 )
            <div>
                <a class="btn btn-primary" href="{{ route('public', ['practice' => $practice]) }}">{{ __('Pubblica') }}</a>
            </div>
        @endif
    </div>

    <script>
        
        function mostraGrafico(id){
            document.getElementById('overlay' + id).style.display = 'block';
            document.getElementById('aggiungiModulo' + id).style.display = 'block';
        }
  
        function nascondiGrafico(id){
            // Nascondi l'overlay e il modulo di aggiunta quando viene cliccato il bottone "Annulla"
            document.getElementById('overlay' + id).style.display = 'none';
            document.getElementById('aggiungiModulo' + id).style.display = 'none';
        }
    </script>
</x-app-layout>
