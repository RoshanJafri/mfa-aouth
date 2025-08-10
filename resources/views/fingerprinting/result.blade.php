@extends('layouts.app')
@section('content')
    @if ($trusted)
        <p>This device is recognized.</p>
        <a href="{{ route('dashboard') }}">Continue to Dashboard</a>
    @else
        <p>This is a new device. Save it?</p>
        <form method="POST" action="{{ route('device.trust') }}">
            @csrf
            <input type="hidden" name="device_fingerprint" value="{{ $device_data }}">
            <label>
                <input type="checkbox" name="save_device" value="1"> Yes, trust this device
            </label>
            <button type="submit">Continue</button>
        </form>
    @endif
    @endsection
