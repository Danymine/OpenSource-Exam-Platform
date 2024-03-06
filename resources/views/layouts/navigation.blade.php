<style>
    .navbar-prima .navbar-collapse {
        justify-content: flex-end; /* Allinea il contenuto della navbar verso destra */
    }

    .navbar-prima .navbar-nav {
        flex-direction: row-reverse; /* Rendi l'ordine degli elementi della navbar da destra verso sinistra */
    }

    .navbar-prima .dropdown-toggle {
        margin-right: 10px; /* Aggiungi spazio tra i dropdown a destra */
    }

    .navbar-prima .dropdown-menu {
        left: auto; /* Resetta l'allineamento del dropdown */
        right: 0; /* Allinea il dropdown verso destra */
    }

</style>

<!-- Secondary Navbar -->
<nav class="navbar navbar-expand-lg navbar-prima" style="background-color: #010039; height: 40px;" aria-label="Secondary Navigation">
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ml-auto">
        @guest
            <li class="nav-item dropdown">
                <!-- Dropdown per la selezione della lingua -->
                <div class="dropdown">
                    <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background-color: #010039; color: #CCCCCC; font-size: 0.8em; padding: 0.2em 0.5em;">
                        {{ __('Lingua') }}
                    </button>
                    <div class="dropdown-menu dropdown-menu-center text-center" aria-labelledby="dropdownMenuButton">
                        <!-- Link per selezionare la lingua -->
                        <a class="dropdown-item" href="{{ route('localization', ['locale' => 'en']) }}">{{ __('Inglese') }}</a>
                        <a class="dropdown-item" href="{{ route('localization', ['locale' => 'it']) }}">{{ __('Italiano')}}</a>
                    </div>
                </div>
            </li>
        @endguest
        @auth
            <li class="nav-item dropdown">
                <!-- Dropdown per il profilo utente -->
                <div class="dropdown">
                    <button class="btn dropdown-toggle" type="button" id="navbarDropdownProfile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background-color: #010039; color: #CCCCCC; font-size: 0.8em; padding: 0.2em 0.5em;">
                        <span class="profile-name">{{ Auth::user()->name }} {{ Auth::user()->surname }}</span>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownProfile">
                        <a class="dropdown-item" href="{{ route('profile.edit') }}">{{ __('Profilo') }}</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                        <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                    </div>
                </div>
            </li>
            <li class="nav-item dropdown">
                <!-- Dropdown per la selezione della lingua -->
                <div class="dropdown">
                    <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background-color: #010039; color: #CCCCCC; font-size: 0.8em; padding: 0.2em 0.5em;">
                        {{ __('Lingua') }}
                    </button>
                    <div class="dropdown-menu dropdown-menu-center text-center" aria-labelledby="dropdownMenuButton">
                        <!-- Link per selezionare la lingua -->
                        <a class="dropdown-item" href="{{ route('localization', ['locale' => 'en']) }}">{{ __('Inglese') }}</a>
                        <a class="dropdown-item" href="{{ route('localization', ['locale' => 'it']) }}">{{ __('Italiano')}}</a>
                    </div>
                </div>
            </li>
            <li class="nav-item dropdown">
                <!-- Dropdown per le notifiche -->
                <div class="dropdown">
                    <button class="btn dropdown-toggle" type="button" id="notificationDropdownBtn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background-color: #010039; color: #CCCCCC; font-size: 0.8em; padding: 0.2em 0.5em;">
                        <i class="fas fa-bell" style="margin-right: 5px;"></i> <!-- Icona delle notifiche -->
                    </button>
                    <div class="dropdown-menu" style="width: 400px; position: absolute; top: 40px; right: 0;" aria-labelledby="notificationDropdownBtn">
                        <!-- Contenuto del menu a tendina -->
                        <h3 class="dropdown-title" style="font-size: 1.2em; color: #333; text-align: center; padding: 10px 0;">{{ __('Le tue notifiche') }}</h3>
                        <hr class="dropdown-divider">
                        @foreach(Auth::user()->assistanceRequest->sortByDesc('created_at') as $request)
                            <a class="dropdown-item" href="{{ route('view-request', ['assistance' => $request]) }}">
                                <div class="d-flex justify-content-between">
                                    <span>{{ $request->subject }}</span>
                                    <span>{{ $request->created_at->format('d/m/Y') }}</span>
                                </div>
                            </a>
                        @endforeach
                        @if(Auth::user()->roles != "Admin")
                            <hr class="dropdown-divider">
                            <a class="dropdown-item" href="{{ route('view-create') }}">
                                <i class="fas fa-question-circle"></i> {{ __('Richiedi assistenza') }}
                            </a>
                        @endif
                    </div>
                </div>
            </li>
        @endauth
        </ul>
    </div>
</nav>

<!-- Main Navbar -->
<nav class="navbar navbar-expand-lg navbar-seconda" style="background-color: #010039;" aria-label="Main Navigation">
    <a class="navbar-brand" href="{{ route('ciao') }}"><img src="/system/logo.jpg" alt="Logo Piattaforma" style="width: 2em; height: 2em;"></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon">
            <!-- Utilizziamo un'icona da Font Awesome -->
            <span class="fas fa-bars"></span>
        </span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="{{ route('ciao') }}">Home</a>
            </li>
            @guest
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">{{ __('Accedi') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('register') }}">{{ __('Registrati') }}</a>
                </li>

            @endguest
            @auth
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                </li>
        
                @if(Auth::user()->roles == "Teacher")
                    <li class="nav-item hide">
                        <a class="nav-link" href="{{ route('showAllExercises') }}">{{ __('Biblioteca') }}</a>
                    </li>
                    <li class="nav-item hide">
                        <a class="nav-link" href="{{ route('practices.index') }}">{{ __("Esercitazioni") }}</a>
                    </li>
                    <li class="nav-item hide">
                        <a class="nav-link" href="{{ route('exam.index') }}">{{ __('Esami') }}</a>
                    </li>
                @elseif( Auth::user()->roles == 'Admin')
                    <li class="nav-item hide">
                        <a class="nav-link" href="{{ route('show-add-user-form') }}">{{ __('Aggiungi Utente') }}</a>
                    </li>
                    <li class="nav-item hide">
                        <a class="nav-link" href="{{ route('user-list') }}">{{ __('Gestisci Utenti') }}</a>
                    </li>
                @endif
            @endauth

        </ul>
        @auth
            @if(Auth::user()->roles != "Student")
                <!-- Icona per aprire il menu a comparsa -->
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link closebtn" href="javascript:void(0)" onclick="openNav()" id="menu">
                            <svg
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                                >
                                <path
                                    d="M2 6C2 5.44772 2.44772 5 3 5H21C21.5523 5 22 5.44772 22 6C22 6.55228 21.5523 7 21 7H3C2.44772 7 2 6.55228 2 6Z"
                                    fill="#ffffff"
                                />
                                <path
                                    d="M2 12.0322C2 11.4799 2.44772 11.0322 3 11.0322H21C21.5523 11.0322 22 11.4799 22 12.0322C22 12.5845 21.5523 13.0322 21 13.0322H3C2.44772 13.0322 2 12.5845 2 12.0322Z"
                                    fill="#ffffff"
                                />
                                <path
                                    d="M3 17.0645C2.44772 17.0645 2 17.5122 2 18.0645C2 18.6167 2.44772 19.0645 3 19.0645H21C21.5523 19.0645 22 18.6167 22 18.0645C22 17.5122 21.5523 17.0645 21 17.0645H3Z"
                                    fill="#ffffff"
                                />
                            </svg>
                        </a>
                    </li>
                </ul>
            @endif

            <!-- Overlay del menu a comparsa -->
            <div id="myNav" class="overlay">
                <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
                <div class="overlay-content">
                    <!-- Elementi del menu a comparsa -->
                    <ul class="nav-secret">
                        @if(Auth::user()->roles == "Teacher")
                            <li class="nav-link">
                                <a class="nav-link" href="{{ route('showAllExercises') }}">{{ __('Biblioteca') }}</a>
                            </li>
                            <li>
                                <a class="nav-link" href="{{ route('practices.index') }}">{{ __('Esercitazioni') }}</a>
                            </li>
                            <li class="nav-link">
                                <a class="nav-link" href="{{ route('exam.index') }}">{{ __('Esami') }}</a>
                            </li>

                        @elseif( Auth::user()->roles == "Admin" )
                            <li class="nav-link">
                                <a class="nav-link" href="{{ route('show-add-user-form') }}">{{ __('Aggiungi Utente') }}</a>
                            </li>
                            <li class="nav-link">
                                <a class="nav-link" href="{{ route('user-list') }}">{{ __('Gestisci Utenti') }}</a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        @endauth
    </div>
</nav>
