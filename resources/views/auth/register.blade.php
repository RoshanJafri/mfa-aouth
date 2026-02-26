<x-guest-layout>

    {{-- Flash messages (optional but recommended) --}}
    @if (session('warning'))
        <div style="background:#fff3cd;color:#856404;padding:10px;border-radius:6px;margin-bottom:15px;">
            {{ session('warning') }}
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        {{-- 🔐 Hidden Device Security Fields --}}
        <input type="hidden" name="device_uuid" id="device_uuid">
        <input type="hidden" name="fingerprint_hash" id="fingerprint_hash">
        <input type="hidden" name="latitude" id="latitude">
        <input type="hidden" name="longitude" id="longitude">

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name"
                          class="block mt-1 w-full"
                          type="text"
                          name="name"
                          :value="old('name')"
                          required
                          autofocus
                          autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email"
                          class="block mt-1 w-full"
                          type="email"
                          name="email"
                          :value="old('email')"
                          required
                          autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password"
                          class="block mt-1 w-full"
                          type="password"
                          name="password"
                          required
                          autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation"
                          class="block mt-1 w-full"
                          type="password"
                          name="password_confirmation"
                          required
                          autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
               href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>

    </form>
</x-guest-layout>

{{-- Device Security Script --}}
<script src="{{ asset('assets/js/device-security.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    if (form) {
        DeviceSecurity.attachToForm(form);
    }
});
</script>