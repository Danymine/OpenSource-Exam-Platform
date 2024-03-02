<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Correction PDF</title>
    <!-- Bootstrap CSS -->
    <style>
        /* Stili CSS per il PDF */
        .container {
            margin: 20px auto;
            max-width: 800px;
            font-family: Arial, sans-serif;
        }

        .card {
            border-radius: 10px;
            border: 1px solid #ccc;
            margin-bottom: 20px;
        }

        .card-header {
            background-color: #f3f3f3;
            padding: 10px 15px;
            border-bottom: 1px solid #ccc;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        .card-body {
            padding: 15px;
        }

        .badge {
            background-color: #007bff;
            color: #fff;
            padding: 5px 10px;
            border-radius: 5px;
        }

        .alert {
            padding: 10px 15px;
            margin-bottom: 15px;
            border-radius: 5px;
        }

        .alert-info {
            background-color: #d1ecf1;
            color: #0c5460;
            border-color: #bee5eb;
        }

        .alert-primary {
            background-color: #cce5ff;
            color: #004085;
            border-color: #b8daff;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }

        .btn {
            display: inline-block;
            padding: 8px 15px;
            font-size: 14px;
            border-radius: 5px;
            text-decoration: none;
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <!-- Contenuto principale: domande e risposte -->
                <div class="card mb-3">
                    <div class="card-header">
                        <b>Utente:</b> {{ $delivered->user->name }} {{ $delivered->user->first_name }} <br>
                        <b>Data consegna:</b> {{ $delivered->created_at->format('d/m/Y') }}
                    </div>
                </div>
                @foreach( $exercises as $exercise )
                    <div class="card mb-3">
                        <div class="card-header">
                            <b>{{ $exercise->question }}</b>
                            <span class="badge badge-primary">{{ $response[$exercise->id][0]["score_assign"] }}</span>
                        </div>
                        <div class="card-body">
                            <p class="card-text">{{ $response[$exercise->id][0]["response"] }}</p>
                            @if( $response[$exercise->id][0]["note"] != NULL )
                                <div class="alert alert-info" role="alert">
                                    <strong>Note:</strong> {{ $response[$exercise->id][0]["note"] }}
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="col-md-4">
                <!-- Sidebar: Note finali del docente e valutazione finale -->
                <div class="card">
                    <div class="card-header">
                        <h4> {{ __('Dettagli') }} </h4>
                    </div>
                    <div class="card-body">
                        <!-- Inserisci qui le note finali del docente e la valutazione finale -->
                        @if($notes != NULL)
                            <div class="alert alert-info" role="alert">
                                <strong>{{ __('Riscontro') }}:</strong> {{ $notes }}
                            </div>
                        @endif

                        <div class="alert alert-primary" role="alert">
                            <strong>{{ __('Voto') }}:</strong> {{ $valutation }}
                        </div>

                        @if( $type == "Exam")
                            @if( $valutation >= $totalScore * 0.6)
                                <div class="alert alert-primary" role="alert">
                                    <strong>{{ __('Esito') }}:</strong> {{ __('Superato') }}
                                </div>
                            @else
                                <div class="alert alert-danger" role="alert">
                                    <strong>{{ __('Esito') }}:</strong> {{ __('Non Superato') }}
                                </div>
                            @endif
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
