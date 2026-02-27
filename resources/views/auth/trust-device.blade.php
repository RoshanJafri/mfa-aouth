<x-guest-layout>

    <div class="text-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">
            {{ __('Trust This Device?') }}
        </h1>

        <p class="mt-2 text-sm text-gray-600">
            Trusted devices skip OTP on future logins.
            You can skip this step, but the device will still be recorded.
        </p>
    </div>

    <form method="POST" action="{{ url('/devices/register') }}">
        @csrf

        <!-- Hidden device fields -->
        <input type="hidden" name="device_uuid">
        <input type="hidden" name="fingerprint_hash">
        <input type="hidden" name="latitude">
        <input type="hidden" name="longitude">
        <input type="hidden" name="trusted" id="trusted" value="1">

        <div class="flex items-center justify-between mt-6">

            <!-- Skip -->
            <button type="button"
                id="skip-button"
                class="underline text-sm text-gray-600 hover:text-gray-900">
                {{ __('Skip for now') }}
            </button>

            <!-- Trust -->
            <x-primary-button>
                {{ __('Yes, Trust This Device') }}
            </x-primary-button>

        </div>
    </form>

<script src="{{ asset('assets/js/device-security.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.querySelector('form');
    const skipBtn = document.getElementById('skip-button');
    const trustedInput = document.getElementById('trusted');

    if (form) {
        DeviceSecurity.attachToForm(form);
    }

    skipBtn.addEventListener('click', function () {
        trustedInput.value = 0;
        form.submit();
    });

});
</script>
</x-guest-layout>
