<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Aggiungi Utente') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Il tuo form per l'aggiunta dell'utente -->
                    <form method="post" action="{{ route('aggiungi-utente') }}">
                        @csrf
                        <!-- Campi del form -->
                       
                        <label for="name">Nome:</label>
                        <input type="text" id="name" name="name" required>

                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" required>

                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" required>

                        <label for="roles">Ruolo:</label>
                        <select id="roles" name="roles" required>
                            <option value="admin">Amministratore</option>
                            <option value="Teacher">Professore</option>
                            <option value="Student">Studente</option>
                        </select>

                        <button type="submit">Aggiungi Utente</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>