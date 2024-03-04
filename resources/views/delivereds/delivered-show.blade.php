<x-app-layout>
    <x-slot name="header">
        @if( Auth::user()->roles == "Teacher")
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="font-semibold text-xl leading-tight">
                    {{ $delivered->user->name }} {{ $delivered->user->first_name }}
                </h4>
                <div>
                    @if( $delivered->practice->public == 0 )
                        <a href="{{ route('view-details-delivered', ['delivered' => $delivered]) }}" class="btn btn-primary">{{ __('Correggi') }}</a>
                    @endif
                    <a href="{{ route('view-delivered', ['practice' => $delivered->practice]) }}" class="btn btn-info">{{ __('Torna Indietro') }}</a>
                    <a href="{{ route('download-details-delivered', ['delivered' => $delivered]) }}" class="btn btn-sm btn-warning" title="{{ __('Stampa consegna') }}" style="height: 38px; width: 40px; text-align: center; padding: 0;">
                        <i class="fas fa-print" style="line-height: 38px;"></i>
                    </a>     
                </div>
            </div>
        @else
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="font-semibold text-xl leading-tight">
                    {{ $delivered->practice->title }} {{ $delivered->created_at->format('d/m/Y') }}
                </h4>
                <div>
                    <a href="{{ route('dashboard') }}" class="btn btn-info">{{ __('Torna Indietro') }}</a>
                    <a href="{{ route('download-details-delivered', ['delivered' => $delivered]) }}" class="btn btn-sm btn-warning" title="{{ __('Stampa consegna') }}" style="height: 38px; width: 40px; text-align: center; padding: 0;">
                        <i class="fas fa-print" style="line-height: 38px;"></i>
                    </a>
                    <!--Sarebbe utile forse stampare anche la correzione-->
                </div>
            </div>
             
        @endif
        <hr style="border-top: 1px solid #0000004a width: 90%;" />
    </x-slot>

    @if( ($delivered->practice->public == 1 || (Auth::user()->roles == "Teacher" && $delivered->valutation != NULL)) )
        <!-- La prova è stata sicuramente corretta -->
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <!-- Contenuto principale: domande e risposte -->
                    @foreach( $exercises as $exercise )
                        <div class="card mb-3" style="border-radius: 10px;">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <b>{{ $exercise->question }}</b>
                                <span class="badge bg-primary" style="color: white;">{{ $response[$exercise->id][0]["score_assign"] }}</span>
                            </div>
                            <div class="card-body">
                                <p class="card-text text-black">{{ $response[$exercise->id][0]["response"] }}</p>
                                <div>
                                    @if( $response[$exercise->id][0]["note"] != NULL )
                                        <div class="alert alert-info" role="alert">
                                            <strong>Note:</strong> {{ $response[$exercise->id][0]["note"] }}
                                        </div>
                                    @endif
                                </div>
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
                            @if($delivered->note != NULL)
                                <div class="alert alert-info mb-3" role="alert">
                                    <strong>{{ __('Riscontro') }}:</strong> {{ $delivered->note }}
                                </div>
                            @endif

                            <!-- Valutazione finale -->
                            <div class="alert alert-primary" role="alert">
                                <strong>{{ __('Voto') }}:</strong> {{ $delivered->valutation }}
                            </div>
                          

                            @if( $delivered->practice->type == "Exam")
                                @if( $delivered->valutation >= $delivered->practice->total_score * 0.6)
                                    <div class="alert alert-primary" role="alert">
                                        <strong>{{ __('Esito') }}:</strong> {{ __('Superato') }}
                                    </div>
                                @else
                                    <div class="alert alert-danger" role="alert">
                                        <strong>{{ __('Esito') }}:</strong> {{ __('Non Superato') }}
                                    </div>
                                @endif
                            @endif


                            <!-- Link per scaricare la correzione, se disponibile -->
                            @if($delivered->path != NULL)
                                <a href="#" class="btn btn-sm btn-success">Scarica correzione</a>
                            @else
                                <a href="{{ route('download-delivered-with-correct', ['delivered' => $delivered]) }}"  class="btn btn-sm btn-warning" title="{{ __('Stampa consegna corretta') }}" style="height: 38px; width: 40px; text-align: center; padding: 0;">
                                    <i class="fas fa-print" style="line-height: 38px;"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
      
        <div class="container">
            @foreach( $exercises as $exercise )
                <div class="card mb-3" style="border-radius: 10px;">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <b>{{ $exercise->question }}</b>
                        <span class="badge bg-primary" style="color: white;">{{ $exercise->score }}</span>
                    </div>
                    <div class="card-body">
                        <p class="card-text text-black"> {{ $response[$exercise->id][0]["response"] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
        
    @endif

</x-app-layout>