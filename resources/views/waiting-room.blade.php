<x-app-layout>
    <x-slot name="header">
        <h4 class="text-2xl font-bold text-black mb-4">{{ $practice->title }}</h4>
        <hr style="border-top: 1px solid #0000004a; width: 90%;" />
    </x-slot>

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
        @if(Auth::user()->roles == "Teacher" && $practice->user_id == Auth::user()->id)
            <h6>{{ __('Salve :name, sei nella gestione della waiting room per il test :title condividi con gli utenti la seguente chiave per partecipare. :key', ['name' => Auth::user()->name, 'title' => $practice->title, 'key' => $practice->key]) }}</b></h6>
        
            @if( $practice->allowed == 0 )
                <!-- Non è stato ancora avviata -->
                <table class="table" id="students-table">
                    <thead>
                        <tr>
                            <th>{{ __('Nome') }}</th>
                            <th>{{ __('Cognome') }}</th>
                            <th>{{ __('Stato') }} </th>
                            <th>{{ __('Espelli') }}</th>
                        </tr>
                    </thead>
                    <tbody id="students-table-body">
                    </tbody>
                </table>
                <a href="{{ route('cancel-start', ['practice' => $practice]) }}" class="btn btn-info">{{ __('Annulla') }}</a>
                <a href="#" id="startTestModalBtn" class="btn btn-primary" data-toggle="modal" data-target="#startTestModal">{{ __('Avvia') }}</a>
            @else
                <table class="table" id="students-table">
                    <thead>
                        <tr>
                            <th>{{ __('Nome') }}</th>
                            <th>{{ __('Cognome') }}</th>
                            <th>{{ __('Stato') }} </th>
                            <th>{{ __('Espelli') }}</th>
                            <th>{{ __('Approva') }}</th>
                        </tr>
                    </thead>
                    <tbody id="students-table-body">
                    </tbody>
                </table>
                <a href="{{ route('terminate-test', ['practice' => $practice]) }}" class="btn btn-danger">{{ __('Termina') }}</a>
            @endif
        @else
            <div class="waiting-container">
                <h4 class="text-center mb-4">{{ __('Rimani in attesa') }}</h4>
                <div class="loader"></div>
                <p class="text-center mt-4" style="color: black;">{{ __('Perfavore aspetti che il test inizi.') }}</p>
            </div>
        @endif
    </div>

    <!-- Modal -->
    <div class="modal fade" id="startTestModal" tabindex="-1" role="dialog" aria-labelledby="startTestModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="startTestModalLabel">{{ __('Durata della Prova') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="startTestForm" method="POST" action="{{ route('start-test', ['practice' => $practice]) }}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="setDuration">{{ __('Vuoi impostare la durata della prova?') }}</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="setDurationOption" id="setDurationYes" value="yes">
                                <label class="form-check-label" for="setDurationYes">
                                    {{ __('Sì') }}
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="setDurationOption" id="setDurationNo" value="no" checked>
                                <label class="form-check-label" for="setDurationNo">
                                    {{ __('No') }}
                                </label>
                            </div>
                        </div>
                        <div id="durationField" style="display: none;">
                            <div class="form-group">
                                <label for="time">{{ __('Durata (in minuti)') }}</label>
                                <input type="number" class="form-control" id="time" name="time">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">{{ __('Annulla') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Avvia') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if( Auth::user()->roles == "Teacher" )
        <script>
            route = "{!! route('fetch-partecipants', ['practice' => $practice]) !!}";
            esp="{{ __('Espelli') }}";
            nsp="{{ __('Nessuno studente presente') }}";
            ina="{{ __('In Attesa') }}";
            svl="{{ __('Svolgendo') }}";
            apr="{{ __('Approva') }}";
            cgt="{{ __('Consegnato') }}";
        </script>
        <script src="/js/waitingRoomTeacher.js"></script>
    @else
        <script>
            route = "{!! route('status', ['practice' => $practice]) !!}";
            routeNext = "{!! route('view-test', ['key' => $practice->key]) !!}";
            routeDashboard = "{!! route('dashboard') !!}";
        </script>
        <script src="/js/waitingRoomStudent.js"></script>
    @endif

</x-app-layout>

<script>
    // Mostra o nasconde il campo di inserimento della durata a seconda dell'opzione selezionata
    document.addEventListener("DOMContentLoaded", function() {
        var setDurationOption = document.getElementsByName("setDurationOption");
        var durationField = document.getElementById("durationField");
        
        for (var i = 0; i < setDurationOption.length; i++) {
            setDurationOption[i].addEventListener("change", function() {
                if (this.value === "yes") {
                    durationField.style.display = "block";
                } else {
                    durationField.style.display = "none";
                }
            });
        }
    });
</script>
