<x-guest-layout>

    {{-- 🔔 Flash Warning Message --}}
    @if (session('warning'))
        <div style="background:#fff3cd;color:#856404;padding:10px;border-radius:6px;margin-bottom:15px;">
            {{ session('warning') }}
        </div>
    @endif

    {{-- ✅ Success Message (optional) --}}
    @if (session('success'))
        <div style="background:#d4edda;color:#155724;padding:10px;border-radius:6px;margin-bottom:15px;">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('otp.verify') }}">
        @csrf
        <input type="hidden" name="device_uuid" id="device_uuid">
        <input type="hidden" name="fingerprint_hash" id="fingerprint_hash">
        <input type="hidden" name="latitude" id="latitude">
        <input type="hidden" name="longitude" id="longitude">

        <div>
            <x-input-label for="otp" value="Enter OTP" />
            <x-text-input id="otp" class="block mt-1 w-full" type="text" name="otp" maxlength="6" required autofocus />
            <x-input-error :messages="$errors->get('otp')" class="mt-2" />
            <br>
            <small>
                <a href="{{ route('otp.resend') }}" style="text-decoration: underline">
                    Send OTP again.
                </a>
            </small>
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                Verify
            </x-primary-button>
        </div>
    </form>

</x-guest-layout>

<script src="{{ asset('assets/js/device-security.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('form');
        DeviceSecurity.attachToForm(form);
    });
</script>