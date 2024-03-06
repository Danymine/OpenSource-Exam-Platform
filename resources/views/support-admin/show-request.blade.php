
<x-app-layout>

    <x-slot name="header">
        <div class="row">
            <div class="col-6">
                <h4 class="text-2xl font-bold text-black mb-4">{{ $assistence->user->name }}  {{ $assistence->user->first_name }}</h4>
            </div>
            <div class="col-6 text-right">
                
                <a href="{{ route('dashboard') }}" class="btn btn-info">{{ __('Torna Indietro') }}</a>
                @if( Auth::user()->roles == 'Admin' )
                    <a href="{{ route('close-request', ['assistance' => $assistence]) }}" class="btn btn-danger">{{ __('Chiudi Discussione') }}</a>
                @endif
            </div>
        </div>
        <hr style="border-top: 1px solid #0000004a width: 90%;" />
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
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">{{ $assistence->subject }}</h5>
                <span class="ml-auto"><i><small>{{ $assistence->user->name }} {{ $assistence->user->first_name}}<span class="badge badge-primary">{{ $assistence->user->roles }}</span></small></i></span>
            </div>

            <div class="card-body">
                <p class="card-text text-black">{{ $assistence->description }}</p>
            </div>
            <div class="card-footer">
                <p class="card-text text-black">{{ $assistence->created_at->format('d/m/Y') }}</p>
            </div>
        </div>

        @foreach( $responses as $response )
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">{{ $assistence->subject }}</h5>
                    <span class="ml-auto"><i><small>{{ $response->user->name }} {{ $response->user->first_name}}
                        @if( $response->user->roles == "Admin")
                            <span class="badge badge-danger">{{ $response->user->roles }}</span></small></i></span>
                        @else
                            <span class="badge badge-primary">{{ $response->user->roles }}</span></small></i></span>
                        @endif
                </div>
                <div class="card-body">
                    <p class="card-text text-black">{{ $response->response }}</p>
                </div>
                <div class="card-footer">
                    <p class="card-text text-black">{{ $response->created_at }}</p>
                </div>
            </div>
        @endforeach

        @if( $assistence->status === 0 )
            <div class="card">

                <div class="card-body">
                    <h5 class="card-title">{{ __('Rispondi') }}</h5>
                    <form action="{{ route('store-response', ['AssistanceRequest' => $assistence ]) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="text">{{ __('Testo') }}</label>
                            <textarea class="card-text form-control" id="text" name="response" rows="4"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary"> {{ __('Rispondi') }}</button>
                    </form>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>