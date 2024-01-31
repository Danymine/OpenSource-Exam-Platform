<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vista Cosegne</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #f8f9fa;
        }

        .overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                z-index: 999;
            }

        .aggiungiModulo {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            border-radius: 10px;
            max-width: 600px; /* Imposta la larghezza massima del riquadro */
            width: 100%;
        }

    </style>
</head>
<body>

    <div class="container mt-4">
        <table class="table">
            <thead>
                <tr>
                    <th>Utente</th>
                    <th>Data Pratica</th>
                    <th>Valutazione</th>
                    <th>Correggi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($delivereds as $delivered)
                <tr>
                    <td><button style="font-size:20px; border: 0; background-color: #f8f9fa; color: blue;" onclick="mostraGrafico({{ $delivered->user_id }})"></i> {{ $delivered->user->name }} </button></td>          
                    <div id="overlay{{ $delivered->user_id }}" class="overlay"></div>

                    <!-- Modulo di aggiunta nascosto inizialmente -->
                    <div id="aggiungiModulo{{ $delivered->user_id }}" class="bg-light p-4 aggiungiModulo">
                        <h2 class="mb-4"> {{ $delivered->user->name }} </h2>
                        <canvas id="ChartStudent{{ $delivered->user_id }}" style="width:100%;max-width:600px"></canvas>

                        <script>
                            // Ottieni le valutazioni delle consegne dell'utente su cui ha cliccato il docente 
                            var deliveredsData = {!! json_encode($delivered->user->delivereds->toArray()) !!};

                            // Ottieni il nome dei test considerazione: Faccio vedere solo esami o anche esercitazioni?
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
                                            yAxes: [{ticks: {min: 18, max:30}}],
                                        }
                                    }
                                });

                        </script>

                        <button id="annullaAggiunta{{ $delivered->user_id }}" class="btn btn-info ml-2" style="font-size:20px" onclick="nascondiGrafico({{ $delivered->user_id }})" >Esci</button>         
                    </div>
                    <td>{{ $delivered->practice->practice_date }}</td>
                    @if( $delivered->valutation == NULL )
                
                        <td> Nessuna Valutazione </td>
                    @else

                        <td>{{ $delivered->valutation }}</td>
                    @endif
                    <td><a href="{{ route('view-details-delivered', ['delivered' => $delivered]) }}"><i class="fas fa-search"></i></a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div>

            <button class="btn btn-primary">Pubblica</button>
        </div>
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
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <!--Bootstrap-->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
