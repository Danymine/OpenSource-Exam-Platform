<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Lista Utenti') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h2>Ricerca Utente per Email</h2>
                    <form action="{{ route('search-user') }}" method="GET">
                        <input type="text" name="email" placeholder="Inserisci l'email dell'utente">
                        <button type="submit">Cerca</button>
                    </form>

                    @if(request()->has('email'))
                        @if(count($users) > 0)
                            <h2>Lista Utenti</h2>

                            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">ID</th>
                                        <th scope="col" class="px-6 py-3">Nome</th>
                                        <th scope="col" class="px-6 py-3">Email</th>
                                        <th scope="col" class="px-6 py-3">Ruolo</th>
                                        <th scope="col" class="px-6 py-3">Modifica</th>
                                        <th scope="col" class="px-6 py-3">Elimina</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td class="px-6 py-4">{{ $user->id }}</td>
                                            <td class="px-6 py-4">{{ $user->name }}</td>
                                            <td class="px-6 py-4">{{ $user->email }}</td>
                                            <td class="px-6 py-4">{{ $user->roles }}</td>
                                            <td class="px-6 py-4">
                                                <a href="{{ route('edit-user-form', ['id' => $user->id]) }}" class="text-blue-500">Modifica</a>
                                                <td class="px-6 py-4">
                                                <form method="post" action="{{ route('delete-user', ['id' => $user->id]) }}">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" onclick="return confirm('Sei sicuro di voler eliminare questo utente?')">Elimina</button>
                                                </form>
                                            </td>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p>Nessun utente ha questa mail</p>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
