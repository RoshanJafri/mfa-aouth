<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dual Gate - Device Trust</title>
</head>

<body>

    <div class="container mx-auto p-4">
        <h1 class="text-xl font-bold mb-4">Trust This Device?</h1>
        <p class="mb-4">
            Trusted devices skip OTP on future logins.
            You can skip, but your device will still be recorded.
        </p>

        <form id="trust-device-form">
            <input type="hidden" name="trusted" id="trusted" value="1">

            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">
                Yes, trust this device
            </button>

            <button type="button" id="skip-button" class="ml-4 px-4 py-2 bg-gray-500 text-white rounded">
                Skip
            </button>
        </form>
    </div>

    <!-- Load module -->
    <script src="{{ asset('assets/js/device-security.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const form = document.getElementById('trust-device-form');
            const skipBtn = document.getElementById('skip-button');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const deviceRegisterUrl = "{{ url('/devices/register') }}";

            async function submitDevice(trustValue) {

                // Collect fingerprint + uuid + geo from module
                const securityData = await DeviceSecurity.collect();

                const payload = {
                    ...securityData,
                    trusted: trustValue
                };

                try {
                    const response = await fetch(deviceRegisterUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify(payload)
                    });

                    if (response.ok) {
                        window.location.href = "{{ route('dashboard') }}";
                    } else {
                        const errorText = await response.text();
                        console.error('Device registration failed:', errorText);
                        alert('Error saving device.');
                    }

                } catch (err) {
                    console.error('Fetch error:', err);
                    alert('Connection error.');
                }
            }

            // Yes (trusted = 1)
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                submitDevice(1);
            });

            // Skip (trusted = 0)
            skipBtn.addEventListener('click', function () {
                submitDevice(0);
            });

        });
    </script>

</body>

</html>