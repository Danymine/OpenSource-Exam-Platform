<x-guest-layout>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>
        
        <!-- First Name -->
        <div class="mt-4">
            <x-input-label for="firstname" :value="__('First Name')" />
            <x-text-input id="firstname" class="block mt-1 w-full" type="text" name="firstname" :value="old('firstname')" required autofocus autocomplete="firstname" />
            <x-input-error :messages="$errors->get('firstname')" class="mt-2" />
        </div>

         <!-- Date -->
         <div class="mt-4">
            <x-input-label for="datebirth" :value="__('Data Birth')" />
            <x-text-input id="datebirth" class="block mt-1 w-full" type="date" name="date_birth" :value="old('date_birth')" autofocus autocomplete="datebirth" />
            <x-input-error :messages="$errors->get('date_birth')" class="mt-2" />
            <span id="feedback-date-validate"></span>
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
            <span id="feedback-email-validate"></span>
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
            <button type="button" onmousedown="mostraPassword('password')" onmouseup="nascondiPassword('password')" onmouseleave="nascondiPassword('password')" style="color:white"><i class="fa fa-eye-slash"></i></button>
            <span id="feedback-password-validate"></span>
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            <button type="button" onmousedown="mostraPassword('password_confirmation')" onmouseup="nascondiPassword('password_confirmation')" onmouseleave="nascondiPassword('password_confirmation')" style="color:white"><i class="fa fa-eye-slash"></i></button>
            <span id="feedback-confirmation-validate"></span>
        </div>

        <!-- Choice Student or Professor -->
        <div class="mt-4">
            <x-input-label for="choice1" value="Student" />
            
            <x-text-input id="choice1"
                            type="radio"
                            name="role" value="Student"/>

            <x-input-label for="choice2" value="Teacher" />
            <x-text-input id="choice2"
                type="radio"
                name="role" value="Teacher"/>

            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
<script src="/js/validateRegister.js"></script>
