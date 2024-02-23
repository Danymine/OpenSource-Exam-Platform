<nav class="navbar navbar-expand-lg" style="background-color: #010039;" aria-label="Main Navigation">
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
                    <a class="nav-link" href="{{ route('login') }}">Accedi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('register') }}">Registrati</a>
                </li>
            @endguest
            @auth
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                </li>
        
                @if(Auth::user()->roles == "Teacher")
                    <li class="nav-item hide">
                        <a class="nav-link" href="{{ route('showAllExercises') }}">Biblioteca</a>
                    </li>
                @endif
                <li class="nav-item hide">
                    <a class="nav-link">{{ __("Esercitazioni") }}</a>
                </li>
                <li class="nav-item hide">
                    <a class="nav-link">Esami</a>
                </li>
                <hr class="menu-divider"/>
                <li class="nav-item hide">
                    <a class="nav-link" href="{{ route('profile.edit') }}">Profilo</a>
                </li>
                <li class="nav-item hide">
                    <a class="nav-link" href="{{ route('ciao') }}">Logout</a>
                </li>
            @endauth
        </ul>
        @auth
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

            <!-- Overlay del menu a comparsa -->
            <div id="myNav" class="overlay">
                <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
                <div class="overlay-content">
                    <!-- Elementi del menu a comparsa -->
                    <ul class="nav-secret">
                        @if(Auth::user()->roles == "Teacher")
                            <li class="nav-link">
                                <a class="nav-link" href="{{ route('showAllExercises') }}">Biblioteca</a>
                            </li>
                        @endif
                        <li>
                            <a class="nav-link">Esercitazioni</a>
                        </li>
                        <li class="nav-link">
                            <a class="nav-link">Esami</a>
                        </li>
                        <hr class="menu-divider"/>
                        <li class="nav-link">
                            <a class="nav-link" href="{{ route('profile.edit') }}">Profilo</a>
                        </li>
                        <li class="nav-link">
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                            <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        @endauth
    </div>
</nav>