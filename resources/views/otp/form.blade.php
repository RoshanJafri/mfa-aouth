@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow p-4" style="max-width: 400px; width: 100%;">
        <h4 class="text-center mb-4">OTP Verification</h4>

        <form method="POST" action="{{ route('verify.otp') }}">
            @csrf
            
                                        <input type="hidden" name="lat" id="lat">
                                        <input type="hidden" name="lng" id="lng">
            <div class="mb-3">
                <label for="otp" class="form-label">Enter the OTP sent to your email</label>
                <input type="text" name="otp" id="otp" class="form-control" maxlength="6" placeholder="e.g. 123456" required autofocus>
            </div>

            @if (session('error'))
                <div class="alert alert-danger py-2 text-center">{{ session('error') }}</div>
            @endif

        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="alert alert-success py-2 text-center">
                {{ session('success') }}
            </div>
        @endif
            <button type="submit" class="btn btn-primary w-100">Verify OTP</button>
        </form>
    </div>
</div>
<script>
        navigator.geolocation.getCurrentPosition(function(position) {
            document.getElementById('lat').value = position.coords.latitude;
            document.getElementById('lng').value = position.coords.longitude;
        });
    </script>
@endsection
