<x-app-layout>
    <x-slot name="header">
        <h4 class="text-2xl font-bold text-black mb-4">{{ $practice->title }}</h4>
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
        @if(Auth::user()->roles == "Teacher" && $practice->user_id == Auth::user()->id)
            <h6>{{ __('Salve :name, sei nella gestione della waiting room per il test :title condividi con gli utenti la seguente chiave per partecipare. :key', ['name' => Auth::user()->name, 'title' => $practice->title, 'key' => $practice->key]) }}</b></h6>
        
            @if( $practice->allowed == 0 )
                <!--Non è stato ancora avviata -->
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
                <a href="{{ route('start-test', ['practice' => $practice]) }}" class="btn btn-primary">{{ __('Avvia') }}</a>
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

                <a href="{{ route('finish-test', ['practice' => $practice]) }}" class="btn btn-danger">{{ __('Termina') }}</a>
            @endif
        @else

            <div class="waiting-container">
                <h4 class="text-center mb-4">{{ __('Rimani in attesa') }}</h4>
                <div class="loader"></div>
                <p class="text-center mt-4" style="color: black;">{{ __('Perfavore aspetti che il test inizi.') }}</p>
            </div>
        @endif
    </div>
    @if( Auth::user()->roles == "Teacher" )
        <script>
            route = "{!! route('fetch-partecipants', ['practice' => $practice]) !!}";
            esp="{{ __('Espelli') }}";
            nsp="{{ __('Nessuno studente presente') }}";
            ina="{{ __('In Attesa') }}";
            svl="{{ __('Svolgendo') }}";
            apr="{{ __( 'Approva' )}}";
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
