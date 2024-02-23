
<x-app-layout>

    <x-slot name="header">
        <h4>
            {{ __('Dashboard') }}
        </h4>
        <hr stile="border-top: 1px solid #000000; width: 90%;" />
    </x-slot>

    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <div class="container">
                    <div class="row">
                        <!-- Primo elemento: bottoni Esami ed Esercitazioni -->
                        <div class="col">
                            <div class="btn-group" role="group" aria-label="Bottoni Esami ed Esercitazioni">
                                <button type="button" class="btn btn-success rounded  mr-2" onclick="showExams()">Esami</button>
                                <button type="button" class="btn btn-success rounded " onclick="showPractices()">Esercitazioni</button>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <!-- Secondo elemento: tabella -->
                        <div class="col">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Nome</th>
                                        <th scope="col">Data</th>
                                        <th scope="col">Alunni mancanti</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach( Auth::user()->practices()->withTrashed()->get() as $practice )

                                        @foreach( $practice->delivereds as $delivered )

                                            @if( $delivered->valutation == NULL )

                                                    @if ( $practice->type  == "Exam" )

                                                    <tr class="exame">
                                                        <td>
                                                            <a href="{{ route('view-delivered', ['practice' => $practice]) }}">
                                                                {{ $practice->title }}
                                                            </a>
                                                        </td>
                                                        <td>{{ $practice->practice_date }}</td>
                                                        <td>
                                                            {{ count($practice->delivereds->filter(function ($delivered) {
                                                                return $delivered->valutation === NULL;
                                                            })) }}
                                                        </td>
                                                    </tr>

                                                    @else
                                                        
                                                        <tr class="practice" style="display: none">
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


</x-app-layout>
<script>
    events = {!! json_encode(Auth::user()->practices()->get()->map(function ($practice) {
        return [
            'title' => $practice->title,
            'start' => $practice->practice_date, // Assumendo che $practice->practice_date sia nel formato corretto
        ];
    })) !!};    
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: events
    });

    calendar.render();
    });

</script>
<script>

    function showExams(){

        practices = document.getElementsByClassName("practice");;
        for (var i = 0; i < practices.length; i++) {
            practices[i].style.display = 'none';
        }
        exame = document.getElementsByClassName("exame");;
        for (var i = 0; i < exame.length; i++) {
            exame[i].style.display = 'table-row';
        }
        
    }
    
    function showPractices(){

        exams = document.getElementsByClassName("exame");;
        for (var i = 0; i < exams.length; i++) {
            exams[i].style.display = 'none';
        }
        practices = document.getElementsByClassName("practice");;
        for (var i = 0; i < practices.length; i++) {
            practices[i].style.display = 'table-row';
        }

    }    
</script>