<x-form-section submit="updateProfileInformation">
    <x-slot name="title">
        {{ __('Profile Information') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Update your account\'s profile information and email address.') }}
    </x-slot>

    <x-slot name="form">


        <!-- Name -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="first_name" value="{{ __('First Name') }}" />
            <x-input id="first_name" type="text" class="mt-1 block w-full" wire:model="state.first_name" required autocomplete="first_name" />
            <x-input-error for="first_name" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-label for="last_name" value="{{ __('Last Name') }}" />
            <x-input id="last_name" type="text" class="mt-1 block w-full" wire:model="state.last_name" required autocomplete="last_name" />
            <x-input-error for="last_name" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-label for="email" value="{{ __('Email') }}" />
            <x-input id="email" type="email" class="mt-1 block w-full" wire:model="state.email" required autocomplete="username" />
            <x-input-error for="email" class="mt-2" />

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()) && ! $this->user->hasVerifiedEmail())
                <p class="text-sm mt-2">
                    {{ __('Your email address is unverified.') }}

                    <button type="button" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" wire:click.prevent="sendEmailVerification">
                        {{ __('Click here to re-send the verification email.') }}
                    </button>
                </p>

                @if ($this->verificationLinkSent)
                    <p class="mt-2 font-medium text-sm text-green-600">
                        {{ __('A new verification link has been sent to your email address.') }}
                    </p>
                @endif
            @endif
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-label for="dob" value="{{ __('Date Of Birth') }}" />
            <x-input id="dob" type="date" disabled readonly class="mt-1 block w-full" wire:model="state.dob" autocomplete="dob"/>
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-label for="company_name" value="{{ __('Company Name') }}" />
            <x-input id="company_name" type="text" class="mt-1 block w-full" wire:model="state.company_name" required autocomplete="company_name" />
            <x-input-error for="company_name" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-label for="address" value="{{ __('Address') }}" />
            <x-input id="address" type="text" class="mt-1 block w-full" wire:model="state.address" required autocomplete="address" />
            <x-input-error for="address" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-label for="country" value="{{ __('Country') }}" />
            <x-country-selector wire:model="state.country" required autocomplete="country"/>
            <x-input-error for="country" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-label for="city" value="{{ __('City') }}" />
            <x-input id="city" type="text" class="mt-1 block w-full" wire:model="state.city" required autocomplete="city" />
            <x-input-error for="city" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-label for="zip_code" value="{{ __('Zip Code') }}" />
            <x-input id="zip_code" type="text" class="mt-1 block w-full" wire:model="state.zip_code" required autocomplete="zip_code" />
            <x-input-error for="zip_code" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="me-3" on="saved">
            {{ __('Saved.') }}
        </x-action-message>

        <x-button wire:loading.attr="disabled">
            {{ __('Save') }}
        </x-button>
    </x-slot>
</x-form-section>
