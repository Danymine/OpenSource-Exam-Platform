<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Vista Consegnate') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                    <div class="mt-8 text-2xl">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Utente</th>
                                    <th>Data Pratica</th>
                                    <th>Valutazione</th>
                                    <th>Dettagli</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($delivereds as $delivered)
                                <tr>
                                    <td>
                                        <button style="font-size:20px; border: 0; background-color: #f8f9fa; color: blue;" onclick="mostraGrafico({{ $delivered->user_id }})">
                                            {{ $delivered->user->name }}
                                        </button>
                                    </td>
                                    <td>{{ $delivered->practice->practice_date }}</td>
                                    @if( $delivered->valutation == NULL )
                                    <td> Nessuna Valutazione </td>
                                    @else
                                    <td>{{ $delivered->valutation }}</td>
                                    @endif
                                    <td>
                                        <a href="{{ route('view-details-delivered', ['delivered' => $delivered]) }}">
                                            <i class="fas fa-search"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                                <tr>
                                <td colspan="4">
                                    @if ( $delivered->practice->public == 0 )
                                    <a href="{{ route('public', ['practice' => $delivered->practice]) }}" class="btn btn-primary">Pubblica</a>
                                    @else
                                    <a href="{{ route('stats', ['practice' => $delivered->practice]) }}" class="btn btn-primary">Statistiche</a>
                                    @endif
                                </td>
                                </tr>
                            </tbody>
                        </table>
                        @if($errors->any())
                        <h4 style="color: black">{{$errors->first()}}</h4>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function mostraGrafico(id) {
            document.getElementById('overlay' + id).style.display = 'block';
            document.getElementById('aggiungiModulo' + id).style.display = 'block';
        }

        function nascondiGrafico(id) {
            document.getElementById('overlay' + id).style.display = 'none';
            document.getElementById('aggiungiModulo' + id).style.display = 'none';
        }
    </script>
</x-app-layout>
