<section>

    <form method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')
        <div class="container-xl px-4">
            <!-- Account page navigation-->
            <div class="row">
                <div class="col-xl-4">
                </div>
                <div class="col-xl-8">
                    <!-- Account details card-->
                    <div class="card mb-4">
                        <div class="card-header">{{ __('Update Password') }}</div>
                            <div class="card-body">
                                    <!-- Form Row-->
                                    <div class="mb-3">
                                        <x-input-label class="small mb-1" for="update_password_current_password" :value="__('Current Password')" />
                                        <x-text-input id="update_password_current_password" name="current_password" type="password" class="form-control" autocomplete="current-password" />
                                        <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" id="error"/>
                                    </div>
                                    <div class="row gx-3 mb-3">
                                        <!-- Form Group (first name)-->
                                        <div class="col-md-6">
                                            <x-input-label class="small mb-1" for="update_password_password" :value="__('New Password')" />
                                            <x-text-input id="update_password_password" name="password" type="password" class="form-control" autocomplete="new-password" />
                                            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" id="errorcurr"/>
                                        </div>
                                        <!-- Form Group (last name)-->
                                        <div class="col-md-6">
                                            <x-input-label class="small mb-1" for="update_password_password_confirmation" :value="__('Confirm Password')" />
                                            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-control" autocomplete="new-password" />
                                            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                                        </div>
                                    </div>
                                <!-- Save changes button-->
                                @if (session('status') === 'password-updated')
                                    <p
                                        x-data="{ show: true }"
                                        x-show="show"
                                        x-transition
                                        x-init="setTimeout(() => show = false, 2000)"
                                        class="text-sm text-gray-600 dark:text-gray-400"
                                    >{{ __('Saved.') }}</p>
                                @endif
                                <button class="btn btn-primary" type="submit">Save changes</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

</section>