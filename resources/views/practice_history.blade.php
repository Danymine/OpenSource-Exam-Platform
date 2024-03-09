<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="font-semibold text-xl leading-tight">
                {{ __('Storico') }}
            </h4>
            <div>
                <a href="{{ route('dashboard') }}" class="btn btn-info">{{ __('Torna Indietro') }}</a>
            </div>
        </div>
        <hr style="border-top: 1px solid #0000004a width: 90%;" />
    </x-slot>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>{{ __('Titolo') }}</th>
                            <th>{{ __('Data') }}</th>
                            <th>{{ __('Materia') }}</th>
                            <th>{{ __('Dettagli') }}</th>
                            <th>{{ __('Statistica') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($practices as $practice)
                            <tr>
                                <td>{{ $practice->title }}</td>
                                <td>{{ $practice->practice_date }}</td>
                                <td>{{ $practice->subject }}</td>
                                <td>
                                    <a href="{{ route('view-delivered', ['practice' => $practice]) }}" class="btn btn-info"><i class="fas fa-search"></i></a>
                                </td>
                                <td>
                                    <a href="{{ route('stats', ['practice' => $practice]) }}" class="btn btn-info"><i class="fas fa-chart-bar"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</x-app-layout>