@extends('layouts.app')
@section('content')
    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-12">
                <h2>Device Check</h2>
                <form method="POST" action="{{ route('device.ouathcheck') }}">
                    @csrf
                    <input type="hidden" name="lat" id="lat">
                    <input type="hidden" name="lng" id="lng">
                    <input type="hidden" name="device_fingerprint" id="device_fingerprint" value=''>
                    <label>
                        Check if device is trusted and choose to either add to your list of trusted device on this account.
                    </label>
                    <br>
                    <br>
                    <button type="submit" class="btn btn-primary">Proceed</button>
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/scripts/fp.js') }}"></script>
    <script>
        navigator.geolocation.getCurrentPosition(function(position) {
            document.getElementById('lat').value = position.coords.latitude;
            document.getElementById('lng').value = position.coords.longitude;
        });
    </script>
@endsection
