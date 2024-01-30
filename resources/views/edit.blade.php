<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Modifica Utente') }}
        </h2>
    </x-slot>

    <style>
        /* Stile CSS per i bottoni */
        .custom-button {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border: none;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .custom-button-green {
            background-color: #4caf50; /* Colore di sfondo per entrambi i bottoni */
            color: white;
        }

        .custom-button-red {
            background-color: #f44336; /* Colore di sfondo per entrambi i bottoni */
            color: white;
        }

        .custom-button:hover {
            background-color: #45a049; /* Cambia il colore al passaggio del mouse */
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('update-user', ['id' => $user->id]) }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Nome:</label>
                    <input type="text" id="name" name="name" value="{{ $user->name }}" class="form-input w-full">
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Email:</label>
                    <input type="email" id="email" name="email" value="{{ $user->email }}" class="form-input w-full">
                </div>
                <!-- Aggiungi altri campi se necessario -->

                <div class="flex items-center justify-between">
                    <button type="submit" class="custom-button custom-button-green">Aggiorna Profilo</button>
                    <a href="{{ route('cancel-edit') }}" class="custom-button custom-button-red">Annulla Modifiche</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
