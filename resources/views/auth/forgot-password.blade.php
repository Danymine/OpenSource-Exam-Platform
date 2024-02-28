<x-app-layout>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card" style="background-color: #010039; color: white; border: 1px solid #010039;">
                    <div class="card-body" style="background-color: #010039; color: white; border: 1px solid #010039;">
                        <h1 class="card-title text-center mb-4">{{ __('Recupera Password') }}</h1>
                        <div class="mb-4 text-sm text-gray-600">
                            {{ __('Dimenticata la password? Nessun problema. Fornisci il tuo indirizzo email e ti invieremo un link per il ripristino della password, che ti permetterà di sceglierne una nuova.') }}
                        </div>

                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf

                            <!-- Email Address -->
                            <div class="mb-3">
                                <label for="email" class="form-label">{{ __('Email') }}</label>
                                <input id="email" class="form-control" type="email" name="email" :value="old('email')" required autofocus>
                                <x-input-error :messages="$errors->get('email')" class="mt-2" style="color: red;" />
                            </div>

                            <div class="d-flex justify-content-end mb-3">
                                <button type="submit" class="btn btn-primary">{{ __('Recupera') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
