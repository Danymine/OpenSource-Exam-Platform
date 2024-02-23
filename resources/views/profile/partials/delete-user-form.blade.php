<section>

    <div class="container-xl px-4">
        <!-- Account page navigation-->
        <div class="row">
            <div class="col-xl-4">
            </div>
            <div class="col-xl-8">
                <!-- Account details card-->
                <div class="card mb-4">
                    <div class="card-header">{{ __('Delete Account') }}</div>
                        <div class="card-body">
                                
                            <button class="btn btn-danger" id="delete-account-btn" >{{ __('Delete Account') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('delete_account', ['user' => Auth::user()]) }}" method="post" id="delete"></form>

</section>


<script>
    // Ottieni il riferimento al bottone "Delete Account"
    const deleteBtn = document.getElementById('delete-account-btn');

    // Aggiungi un gestore di eventi per il click sul bottone
    deleteBtn.addEventListener('click', function() {
        // Mostra il popup di conferma
        const isConfirmed = confirm('Are you sure you want to delete your account?');

        // Se l'utente conferma, esegui l'azione
        if (isConfirmed) {
            
            const deleteForm = document.getElementById('delete');
            deleteForm.submit();
        }
    });
</script>
