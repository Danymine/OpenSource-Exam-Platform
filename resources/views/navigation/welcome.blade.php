<x-app-layout>

    <div class="container-fluid d-flex p-0" style="background-color: #010039;">
        <div class="row m-0 w-100"> <!-- Utilizza la classe w-100 per fare in modo che la riga occupi l'intera larghezza del contenitore -->
            <div class="col-md-6 p-4 mb-3"> <!-- Utilizza la classe col-md-7 per occupare il 60% dello schermo su dispositivi di dimensioni medie e superiori -->
                <h1 class="text-white">{{ __('Scopri subito ExamSync') }}</h1>
                <p>
                    {{ __("Benvenuti alla Piattaforma per la Gestione di Esercitazioni/Esami, il vostro compagno digitale per la creazione, gestione e valutazione di esercizi e test educativi in modo efficiente e intuitivo. Sviluppata con l'obiettivo di semplificare il processo di preparazione e somministrazione di esercitazioni ed esami, la nostra piattaforma offre una vasta gamma di funzionalità progettate per soddisfare le esigenze di docenti, formatori e istituti educativi di ogni livello.") }}
                </p>
                <a type="button" id="iscriviti" class="btn rounded text-white" href="{{ route('register') }}">{{ __('Iscriviti Subito') }}</a>
            </div>
            <div class="col-md-6 mb-3 d-flex align-items-center justify-content-center"> <!-- Aggiungi le classi d-flex, align-items-center e justify-content-center per centrare l'immagine verticalmente e orizzontalmente -->
                <img src="/system/SfondoHome.jpeg" class="img-fluid" alt="{{ __('Compito in classe') }}">
            </div>
        </div>
    </div>

    <!-- Schede -->
    <div class="container mt-5">
        <h2 class="text-center mb-4">{{ __('Perchè scegliere ExamSync?') }}</h2>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card text-center"  style="background-color: #010039; border-radius: 30px;">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                        <lord-icon
                            src="https://cdn.lordicon.com/egmlnyku.json"
                            trigger="hover"
                            colors="primary:#FFFFFF,secondary:#FFFFFF,tertiary:#66ee78,quaternary:#e86830"
                            style="width:150px;height:150px">
                        </lord-icon>
                        <h4 class="card-title mt-3 text-white">{{ __('Assistenza Garantita') }}</h4>
                        <p class="card-text">{{ __('Assistenza tecnica garantita per ogni problema o necessità ogni giorno dalle 8:00 fino alle 18:00') }}/p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card text-center"  style="background-color: #010039;  border-radius: 30px;">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                        <lord-icon
                            src="https://cdn.lordicon.com/xjronrda.json"
                            trigger="hover"
                            style="width:150px;height:150px">
                        </lord-icon>
                        <h4 class="card-title mt-3 text-white">{{ __('Semplice Utilizzo') }}</h4>
                        <p class="card-text">{{ __('La nostra piattaforma ha ottenuto ottimi risultati con i propri utenti certificandosi tripla A') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card text-center"  style="background-color: #010039;  border-radius: 30px;">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                        <lord-icon
                            src="https://cdn.lordicon.com/khheayfj.json"
                            trigger="hover"
                            colors="primary:#ffffff,secondary:#ffffff"
                            style="width:150px;height:150px">
                        </lord-icon>
                        <h4 class="card-title mt-3 text-white">{{ __('Sicurezza dei Dati') }}</h4>
                        <p class="card-text">{{ __('La vostra sicurezza e qualla dei suoi studenti è garantita da meccanismi crittografici.') }} </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>

