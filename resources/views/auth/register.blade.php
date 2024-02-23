<x-app-layout>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card" style="background-color: #010039; color: white; border: 1px solid #010039;">
                <div class="card-body" style="background-color: #010039; color: white; border: 1px solid #010039;">
                    <h1 class="card-title text-center mb-4">{{ __('Register') }}</h1>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">{{ __('Name') }}</label>
                                <input id="name" class="form-control" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>
                            <div class="col-md-6">
                                <label for="firstname" class="form-label">{{ __('First Name') }}</label>
                                <input id="firstname" class="form-control" type="text" name="firstname" value="{{ old('firstname') }}" required autocomplete="firstname">
                                <x-input-error :messages="$errors->get('firstname')" class="mt-2" />
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="datebirth" class="form-label">{{ __('Date Birth') }}</label>
                                <input id="datebirth" class="form-control" type="date" name="date_birth" value="{{ old('date_birth') }}" required autocomplete="datebirth">
                                <x-input-error :messages="$errors->get('date_birth')" class="mt-2" />
                                <span id="feedback-date-validate"></span>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">{{ __('Email') }}</label>
                                <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autocomplete="username">
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                <span id="feedback-email-validate"></span>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label">{{ __('Password') }}</label>
                                <div class="input-group">
                                    <input id="password" class="form-control" type="password" name="password" required autocomplete="new-password">
                                    <button type="button" class="btn btn-outline-secondary" onmousedown="mostraPassword('password')" onmouseup="nascondiPassword('password')" onmouseleave="nascondiPassword('password')">
                                        <span class="fa fa-eye-slash"></span>
                                    </button>
                                </div>
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                <span id="feedback-password-validate"></span>
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
                                <div class="input-group">
                                    <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password">
                                    <button type="button" class="btn btn-outline-secondary" onmousedown="mostraPassword('password_confirmation')" onmouseup="nascondiPassword('password_confirmation')" onmouseleave="nascondiPassword('password_confirmation')">
                                        <span class="fa fa-eye-slash"></span>
                                    </button>
                                </div>
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                                <span id="feedback-confirmation-validate"></span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <fieldset aria-labelledby="role-group-label">
                                <legend id="role-group-label" style="font-size: 16px">{{ __('Role') }}</legend>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="role" value="Student" id="choice1">
                                    <label class="form-check-label text-white" for="choice1">{{ __('Student') }}</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="role" value="Teacher" id="choice2">
                                    <label class="form-check-label text-white" for="choice2">{{ __('Teacher') }}</label>
                                </div>
                            </fieldset>
                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">{{ __('Register') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>



</x-app-layout>
<script src="/js/validateRegister.js"></script>
