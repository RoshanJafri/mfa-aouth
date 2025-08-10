@extends('layouts.app')
@section('content')


<div class="container mt-5"><h2>Device Check</h2>
    <div class="row">
        <form method="POST" action="{{ route('device.trust') }}">
    @csrf
    <input type="hidden" name="device_fingerprint" id="device_fingerprint" value=''>
    <label>
        <input type="checkbox" name="save_device" value="1" checked>
        Trust this device for future logins
    </label>
    <br>
    <button type="submit">Confirm</button>
</form>

    </div>
</div>
    <script src="{{ asset('assets/scripts/fp.js') }}"></script>
@endsection
