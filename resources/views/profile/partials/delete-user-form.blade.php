<section>

    <div class="container-xl px-4">
        <!-- Account page navigation-->
        <div class="row">
            <div class="col-xl-4">
            </div>
            <div class="col-xl-8">
                <!-- Account details card-->
                <div class="card mb-4">
                    <div class="card-header">{{ __('Cancella Account') }}</div>
                        <div class="card-body">
                                
                            <button class="btn btn-danger" id="delete-account-btn" >{{ __('Elimina Account') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('delete_account', ['user' => Auth::user()]) }}" method="post" id="delete"></form>

</section>


<script>
    var translations = {
    'it': {
       'conferma_eliminazione': "Sicuro di voler eliminare l'account?"
    },
    'en': {
        'conferma_eliminazione': "Are you sure you want to delete your account?"
    }
    };
    // Ottieni il riferimento al bottone "Delete Account"
    const deleteBtn = document.getElementById('delete-account-btn');
    var currentURL = window.location.href;
    var languageIndex = currentURL.indexOf('/en/');

    // Se la lingua è presente nell'URL
    if (languageIndex !== -1) {

        language = 'en';
    } else {

        // Se la lingua non è 'en', impostala su 'it'
        language = 'it';
    }

    // Aggiungi un gestore di eventi per il click sul bottone
    deleteBtn.addEventListener('click', function() {
        // Mostra il popup di conferma
        const isConfirmed = confirm(translations[language]['conferma_eliminazione']);

        // Se l'utente conferma, esegui l'azione
        if (isConfirmed) {
            
            const deleteForm = document.getElementById('delete');
            deleteForm.submit();
        }
    });
</script>
