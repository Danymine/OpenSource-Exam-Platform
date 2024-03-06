
<x-app-layout>

    <x-slot name="header">
       <h4>{{ __('Assistenza') }}</h4>
        <hr style="border-top: 1px solid #0000004a width: 90%;" />
    </x-slot>


    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">

                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <form method="POST" action="{{ route('storeAssistanceRequest') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label">{{ __('Oggetto') }}:</label>
                                <input type="text" class="form-control" id="name" name="subject" placeholder="{{ __('Classifica il Problema')}}" required>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">{{ __('Richiesta') }}:</label>
                                <textarea class="form-control" id="description" name="description" placeholder="{{ __('Descrizione')}}" required></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">{{ __('Invia Richiesta') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
