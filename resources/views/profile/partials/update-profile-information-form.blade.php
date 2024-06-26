<section>
    <header class="text-center">
        <h2 class="text-lg font-medium">
            {{ __('Profilo') }}
        </h2>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')
        <div class="container-xl px-4 mt-4">
            <!-- Account page navigation-->
            <hr class="mt-0 mb-4">
            <div class="row">
                <div class="col-xl-4">
                    <!-- Profile picture card-->
                    <div class="card mb-4 mb-xl-0">
                        <div class="card-header">
                            {{ __('Immagine del Profilo') }}
                        </div>
                        <div class="card-body text-center">
                            <!-- Profile picture image-->
                            @if( Auth::user()->img_profile == NULL )

                                <img id="avatar-img" class="img-account-profile rounded-circle mb-2" src="/system/avatar_standard.jpg" alt="{{ __('Immagine del Profilo') }}" style="width: 200px; height: 200px; cursor: pointer;">
                            @else

                                <img id="avatar-img" src="{{ Auth::user()->img_profile }}" class="img-account-profile rounded-circle mb-2" alt="{{ __('Immagine del Profilo') }}"  style="width: 200px; height: 200px; cursor: pointer;" >
                            @endif
                            <!-- Profile picture help block-->
                            <x-text-input id="icon_profile" name="icon_profile" type="file" class="mt-1 block w-full" accept="image/*" style="display: none;"/>
                            <x-input-error class="mt-2" :messages="$errors->get('icon_profile')" />
                            <div class="small font-italic text-muted mb-4">{{ __('JPG o PNG e che non sia più grande di 5MB') }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-8">
                    <!-- Account details card-->
                    <div class="card mb-4">
                        <div class="card-header">{{ __("Informazioni dell'account") }}</div>
                            <div class="card-body">
                                    <!-- Form Row-->
                                    <div class="row gx-3 mb-3">
                                        <!-- Form Group (first name)-->
                                        <div class="col-md-6">
                                            <x-input-label class="small mb-1" for="name" :value="__('Nome')" />
                                            <x-text-input id="name" name="name" type="text" class="mt-1 form-control" :value="old('name', $user->name)" autofocus required autocomplete="name" />
                                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                                        </div>
                                        <!-- Form Group (last name)-->
                                        <div class="col-md-6">
                                            <x-input-label class="small mb-1" for="first_name" :value="__('Cognome')" />
                                            <x-text-input id="first_name" name="first_name" type="text" class="mt-1 form-control" :value="old('first_name', $user->first_name)" required autocomplete="first_name" />
                                            <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
                                        </div>
                                    </div>
                                    <!-- Form Row-->
                                    <div class="row gx-3 mb-3">
                                        <!-- Form Group (birthday)-->
                                        <div class="col-md-6">
                                            <x-input-label class="small mb-1" for="date_birth " :value="__('Data di Nascita')" />
                                            <x-text-input id="date_birth" name="date_birth" type="date" class="mt-1 form-control" :value="old('date_birth ', $user->date_birth )" required autocomplete="username" />
                                            <x-input-error class="mt-2" :messages="$errors->get('date_birth')" id="error"/>
                                        </div>

                                        <div class="col-md-6">
                                            <x-input-label class="small mb-1" for="email" :value="__('Email')" />
                                            <x-text-input id="email" name="email" type="email" class="mt-1 form-control" :value="old('email', $user->email)" required autocomplete="username" />
                                            <x-input-error class="mt-2" :messages="$errors->get('email')" id="error"/>
                                        </div>

                                    </div>

                                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                            <div>
                                                <p class="text-sm mt-2" style="color: black">
                                                    {{ __('Il tuo indirizzo email non è verificato') }}

                                                    <button form="send-verification" class="btn btn-warning">
                                                        {{ __("Fare clic qui per inviare nuovamente l'e-mail di verifica") }}
                                                    </button>
                                                </p>

                                                @if (session('status') === 'verification-link-sent')
                                                    <p class="mt-2 font-medium text-sm" style="color: black">
                                                        {{ __('Un nuovo link di verifica è stato inviato al tuo indirizzo email') }}
                                                    </p>
                                                @endif
                                            </div>
                                    @endif
                                <!-- Save changes button-->
                                <button class="btn btn-primary" type="submit" id="save">{{ __('Salva le modifiche') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

</section>

<script>
    document.getElementById('avatar-img').addEventListener('click', function() {
        document.getElementById('icon_profile').click();
    });
</script>

