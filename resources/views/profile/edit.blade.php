<x-app-layout>

    <div>
        <div class="py-4">

            <div>
                @include('profile.partials.update-profile-information-form')
            </div>



            <div>
                @include('profile.partials.update-password-form')
            </div>


            <div>
                @include('profile.partials.delete-user-form')

            </div>
        </div>
    </div>
</x-app-layout>