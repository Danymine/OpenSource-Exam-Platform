<x-app-layout>
<div class="container custom-container mt-4 p-4" style="background-color: #010039;">
    <h2 class="text-white text-center mb-4"> Verify Email </h2>
    <div class="mb-4 text-sm text-white">
        <p> {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }} </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-success text-white">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="mt-4 d-flex justify-content-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn btn-primary">
                {{ __('Resend Verification Email') }}
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-link text-white">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</div>


</x-app-layout>
