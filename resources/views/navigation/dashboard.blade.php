
<x-app-layout>

    <x-slot name="header">
        @if( Auth::user()->roles == "Student")
            <div class="d-flex justify-content-between align-items-center">
                <h4>{{ __('Dashboard') }}</h4>
                <form action="{{ route('pratices.join') }}" method="POST" class="form-inline">
                    @csrf
                    <input type="text" id="key" name="key" class="form-control mr-3 equal-height" placeholder="{{ __('Inserisci la chiave') }}">
                    <button type="submit" class="btn btn-primary equal-height" id="join">{{ __('Partecipa') }}</button>
                </form>
            </div>
            <hr style="border-top: 1px solid #0000004a width: 90%;" />
        @else
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="font-semibold text-xl leading-tight">
                    {{ __('Dashboard') }}
                </h4>
                @if( Auth::user()->roles == "Teacher" )
                    <div class="dropdown">
                        <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ __('Storico') }}
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="{{ route('exame-passed') }}">{{ __('Storico Esami') }}</a>
                            <a class="dropdown-item" href="{{ route('practice-passed') }}">{{ __('Storico Esercitazioni') }}</a>
                        </div>
                    </div>
                @endif
            </div>
            <hr style="border-top: 1px solid #0000004a width: 90%;" />
        @endif
    </x-slot>


    @if( Auth::user()->roles == "Teacher" )
        
        <div class="container">
            @if($errors->any())
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
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
                                <table class="table table-hover">
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

                                                @if( $delivered->valutation == NULL )

                                                        @if ( $practice->type  == "Exam" )

                                                            <tr class="exame" onclick="window.location='{{ route('view-delivered', ['practice' =>  $practice ] ) }}'" style="cursor:pointer;">
                                                                <td>{{ $practice->title }}</td>
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
                                                            
                                                            <tr class="practice" onclick="window.location='{{ route('view-delivered', ['practice' =>  $practice ] ) }}'" style="cursor:pointer; display: none;">
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
        
        <script>
            events = {!! json_encode(Auth::user()->practices()->get()->map(function ($practice) {
                return [
                    'title' => $practice->title,
                    'start' => $practice->practice_date, // Assumendo che $practice->practice_date sia nel formato corretto
                    'url' => route('waiting-room', ['key' => $practice->key]) // Aggiungi l'URL con la chiave corretta
                ];
            })) !!};
        </script>
        <script src="/js/TeacherDashboard.js"></script>

    @elseif( Auth::user()->roles == "Student" )
        
        <div class="container">
            @if($errors->any())
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    {{ session('success') }}
                </div>
            @endif
        </div>
        <div class="container">
            <h4>{{ __('Ciao, :name', ['name' => Auth::user()->name]) }}</h4>
                <div class="row">
                    <div class="col">
                        <button id="esamiButton" class="btn btn-primary" onclick="showExamsStudent()">{{ __('Esami') }}</button>
                        <button id="esercitazioniButton" class="btn btn-primary" onclick="showPracticesStudent()">{{ __('Esercitazioni') }}</button>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col">
                        <div class="table-responsive">
                            <table class="table table-hover">
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

                                            <tr class="clickable row-type row-practice" onclick="window.location='{{ route('show-delivered', ['delivered' =>  $delivered ] ) }}'" style="cursor:pointer; display: none;">
                                                <td class="practice_title">{{  $practice->title }}</td>
                                                <td class="practice_date">{{ $practice->practice_date }}</td>
                                                <td class="practice_valutation">
                                                    {{ $delivered->valutation !== NULL && $practice->public == 1 ? $delivered->valutation : __('Non Valutata') }}
                                                </td>
                                            </tr>
                                        @else
                                            <tr class="clickable row-type row-exame" onclick="window.location='{{ route('show-delivered', ['delivered' =>  $delivered ] ) }}'" style="cursor:pointer;">
                                                <td class="exame_title" >{{ $practice->title }}</td>
                                                <td class="exame_date">{{ $practice->practice_date }}</td>
                                                <td class="exame_valutation">
                                                    @if($delivered->valutation !== NULL && $practice->public == 1 && $delivered->valutation >= $practice->total_score * 0.6)

                                                        {{ $delivered->valutation }}
                                                    @else
                                                        {{ $delivered->valutation !== NULL && $practice->public == 1 ? __('Insufficiente') : __('Non Valutata') }}
                                                    @endif
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!--Grafico degli Esami -->
                <div class="row mt-3">
                    <div class="col">
                        <canvas id="Chartexame" style="max-width: 600px;"></canvas>
                    </div>
                </div>

                <!--Grafico delle Esercitazioni -->
                <div class="row mt-3">
                    <div class="col">
                        <canvas id="Chartpractice" style="max-width: 600px; display: none;"></canvas>
                    </div>
                </div>
            </div>
            
            <script>
                var translations = "{{ __('Valutazioni') }}";
                var exame = "{{ __('Esami') }}";
                var practice = "{{ __('Esercitazioni') }}";
            </script>
            <script src="/js/StudentDashboard.js"></script>
            
        </div>
        
    @else
        
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

        <!--Amministratore -->
        <div class="container">
            <h2>{{ __('Benvenuto') }}, {{ Auth::User()->name }}</h2>
                  
            <table class="table">
                <thead>
                    <tr>
                        <th>{{ __('Utente') }}</th>
                        <th>{{ __('Oggetto') }}</th>
                        <th>{{ __('Data') }}</th>
                        <th></th> <!-- Colonna per il pulsante Dettagli -->
                    </tr>
                </thead>
                <tbody>
                    @foreach($Assistances as $assistance)
                        <tr>
                            <td>{{ $assistance->user->name }} {{ $assistance->user->first_name }}</td>
                            <td>{{ $assistance->subject }}</td>
                            <td>{{ $assistance->created_at->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('view-request', ['assistance' => $assistance]) }}" class="btn btn-info"><i class="fas fa-search"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $Assistances->links() }}
        </div>
    @endif            
</x-app-layout>