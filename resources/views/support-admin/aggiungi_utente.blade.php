<x-app-layout>
    <x-slot name="header">
        <h4 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Aggiungi Utente') }}
        </h4>
        <hr class="border-top border-gray-400 my-4 w-90" />
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
        <!-- Il tuo form per l'aggiunta dell'utente -->
        <form method="post" action="{{ route('aggiungi-utente') }}" class="space-y-4">
            @csrf
            <!-- Campi del form -->
            <div class="form-group">
                <label for="name">{{ __('Nome') }}:</label>
                <input type="text" id="name" name="name" class="form-control" placeholder="{{ __('Inserisci il nome') }}" required value="{{ old('name') }}">
            </div>

            <div class="form-group">
                <label for="name">{{ __('Cognome') }}:</label>
                <input type="text" id="name" name="first_name" class="form-control" placeholder="{{ __('Inserisci il cognome') }}" required value="{{ old('first_name') }}">
            </div>

            <div class="form-group">
                <label for="email">{{ __('Email') }}:</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="{{ __('Inserisci l`indirizzo email') }}" required value="{{ old('email') }}">
            </div>

            <div class="form-group">
                <label for="password">{{ __('Password') }}:</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="{{ __('Inserisci la password') }}" required>
            </div>

            <div class="form-group">
                <label for="roles">{{ __('Ruolo') }}:</label>
                <select id="roles" name="roles" class="form-control p-2" required>
                    <option value="Admin">{{ __('Amministratore') }}</option>
                    <option value="Teacher">{{ __('Insegnante') }}</option>
                    <option value="Student">{{ __('Studente') }}</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">{{ __('Aggiungi Utente') }}</button>
        </form>            
    </div>
</x-app-layout>
