<x-app-layout>
<div class="container custom-container mt-4 p-4" style="background-color: #010039;">
    <h2 class="text-white text-center mb-4"> {{ __("Verifica l'Email") }} </h2>
    <div class="mb-4 text-sm text-white">
        <p> {{ __("Grazie per esserti registrato! Prima di iniziare, potresti confermare il tuo indirizzo email cliccando sul link che ti abbiamo appena inviato via email? Se non hai ricevuto l'email, saremo lieti di inviartene un'altra.") }} </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-success text-white">
            {{ __("Un nuovo link di verifica è stato inviato all'indirizzo email fornito durante la registrazione.") }}
        </div>
    @endif

    <div class="mt-4 d-flex justify-content-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn btn-primary">
                {{ __('Rinvia Email di Verifica') }}
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-link text-white">
                {{ __('Logout') }}
            </button>
        </form>
    </div>
</div>


</x-app-layout>
