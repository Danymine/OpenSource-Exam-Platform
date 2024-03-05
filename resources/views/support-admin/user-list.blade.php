<x-app-layout>
    <x-slot name="header">
        <h4 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Lista Utenti') }}
        </h4>
        <hr style="border-top: 1px solid #0000004a width: 90%;" />
    </x-slot>

    <div class="container">

        @if (session('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <div class="container">
        <form action="{{ route('search-user') }}" method="POST" class="row align-items-center">
            @csrf
            <div class="col-auto">
                <div class="input-group">
                    <input type="email" id="email" name="email" class="form-control mr-2 rounded" placeholder="{{ __('Inserisci l`indirizzo email') }}" required value="{{ old('email') }}">
                    <button type="submit" class="btn btn-primary">{{ __('Cerca') }}</button>
                </div>
            </div>
        </form>

        @if( isset($user) )
            <script>
                trans = "{{ __('Sei sicuro di voler eliminare questo utente?') }}";
            </script>
            <table class="table mt-3">
                <thead>
                    <tr>
                        <th>{{ __('Nome') }}</th>
                        <th>{{ __('Email') }}</th>
                        <th>{{ __('Data di Nascita') }}</th>
                        <th>{{ __('Ruolo') }}</th>
                        <th></th> <!-- Colonna per il pulsante Dettagli -->
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="align-middle" >{{ $user->name }} {{ $user->first_name }}</td>
                        <td class="align-middle" >{{ $user->email }}</td>
                        @if( $user->date_birth != NULL )

                            <td class="align-middle" >{{ $user->date_birth }}</td>
                        @else
                            <td class="align-middle" >{{ __('Non Presente') }}</td>
                        @endif
                        <td class="align-middle" >{{ $user->roles }}</td>
                        <td class="align-middle">
                            <div class="d-flex align-items-center">
                                <a href="#" class="btn btn-warning mr-2" data-toggle="modal" data-target="#editModal">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('delete-user', ['user' => $user]) }}" method="POST" onsubmit="return confirm(trans)">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        @endif
    </div>

    @if( isset($user) )
        <!-- Modale di edit -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">{{ __('Modifica Utente') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('update-user', ['user' => $user]) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <!-- Input per i campi dell'utente -->
                            <div class="form-group">
                                <label for="name">{{ __('Nome') }}:</label>
                                <input type="text" id="name" name="name" class="form-control" value="{{ $user->name }}">
                            </div>
                            <div class="form-group">
                                <label for="first_name">{{ __('Cognome') }}:</label>
                                <input type="text" id="first_name" name="first_name" class="form-control" value="{{ $user->first_name }}">
                            </div>
                            <div class="form-group">
                                <label for="date_birth">{{ __('Data di Nascita') }}:</label>
                                <input type="date" id="date_birth" name="date_birth" class="form-control" value="{{ $user->date_birth }}">
                            </div>
                            <div class="form-group">
                                <label for="edit_email">{{ __('Email') }}:</label>
                                <input type="email" id="edit_email" name="email" class="form-control" value="{{ $user->email }}">
                            </div>
                            <div class="form-group">
                                <label for="roles">{{ __('Ruolo') }}:</label>
                                <select id="roles" name="roles" class="form-control p-0">
                                    <option value="admin" {{ $user->roles == 'admin' ? 'selected' : '' }}>{{ __('Amministratore') }}</option>
                                    <option value="Teacher" {{ $user->roles == 'Teacher' ? 'selected' : '' }}>{{ __('Professore') }}</option>
                                    <option value="Student" {{ $user->roles == 'Student' ? 'selected' : '' }}>{{ __('Studente') }}</option>
                                </select>
                            </div>
                            <!-- Pulsante di invio per il form di modifica -->
                            <button type="submit" class="btn btn-primary">{{ __('Salva') }}</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">{{ __('Chiudi') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

</x-app-layout>
