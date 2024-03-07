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
                                
                            <button class="btn btn-danger" id="delete-account-btn">{{ __('Elimina Account') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('delete_account', ['user' => Auth::user()]) }}" method="post" id="delete">@csrf</form>

</section>


<script>
    str= "{{ __('Eliminazione') }}"
    // Ottieni il riferimento al bottone "Delete Account"
    const deleteBtn = document.getElementById('delete-account-btn');
    

    // Aggiungi un gestore di eventi per il click sul bottone
    deleteBtn.addEventListener('click', function() {
        // Mostra il popup di conferma
        const isConfirmed = confirm(str);

        // Se l'utente conferma, esegui l'azione
        if (isConfirmed) {
            
            const deleteForm = document.getElementById('delete');
            deleteForm.submit();
        }
    });
</script>
