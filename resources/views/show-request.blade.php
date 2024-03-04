
<x-app-layout>

    <x-slot name="header">
        <div class="row">
            <div class="col-6">
                <h4 class="text-2xl font-bold text-black mb-4">{{ $assistence->user->name }}  {{ $assistence->user->first_name }}</h4>
            </div>
            <div class="col-6 text-right">
                
                <a href="{{ route('dashboard') }}" class="btn btn-info">{{ __('Torna Indietro') }}</a>
            </div>
        </div>
        <hr style="border-top: 1px solid #0000004a width: 90%;" />
    </x-slot>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">{{ $assistence->subject }}</h5>
            </div>
            <div class="card-body">
                <p class="card-text text-black">{{ $assistence->description }}</p>
            </div>
            <div class="card-footer">
                <p class="card-text text-black">{{ $assistence->created_at->format('d/m/Y') }}</p>
            </div>
        </div>

        <div class="card mt-4">

            <div class="card-body">
                <h5 class="card-title">{{ __('Rispondi') }}</h5>
                <textarea class="card-text form-control" id="w3review" name="w3review" rows="4"></textarea>
            </div>
        </div>
    </div>
</x-app-layout>