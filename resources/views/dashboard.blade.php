@extends('layouts.app')
@section('content')
    <div class="container">
        <h1>Users</h1>
        <table border="1" cellpadding="8" cellspacing="0" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Last Login At</th>
                    <th>Last Login IP</th>
                    <th>Last login location</th>
                    <th>Trusted Devices</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr class="{{auth()->user()->id == $user->id?'bg-success':''}}">
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->last_login_at ? $user->last_login_at->format('Y-m-d H:i:s') : 'Never' }}</td>
                        <td>{{ $user->last_login_ip ?? 'N/A' }}</td>
                        <td>{{ $user->geolocation_history ?? 'N/A' }}</td>
                        <td>
                            @php
                                $devices = is_array($user->trusted_devices)
                                    ? $user->trusted_devices
                                    : json_decode($user->trusted_devices ?? '[]', true);
                            @endphp

                            @if (count($devices) === 0)
                                <em>No trusted devices</em>

                                @if (auth()->user()->id == $user->id)
                                    <a href="{{ url('device-check') }}" class="btn btn-primary">Trust this device</a>
                                @endif
                            @else
                                <table border="1" cellpadding="4" cellspacing="0"
                                    style="border-collapse: collapse; width: 100%;">
                                    <thead>
                                        <tr style="background-color: #f0f0f0;">
                                            <th>ID</th>
                                            <th>User Agent</th>
                                            <th>Platform</th>
                                            <th>Language</th>
                                            <th>Screen</th>
                                            <th>Timezone Offset</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($devices as $device)
                                            <tr>
                                                <td>{{ $device['id'] ?? 'N/A' }}</td>
                                                <td
                                                    style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                    {{ $device['userAgent'] ?? 'N/A' }}</td>
                                                <td>{{ $device['platform'] ?? 'N/A' }}</td>
                                                <td>{{ $device['language'] ?? 'N/A' }}</td>
                                                <td>{{ ($device['screenWidth'] ?? 'N/A') . ' x ' . ($device['screenHeight'] ?? 'N/A') }}
                                                </td>
                                                <td>{{ $device['timezoneOffset'] ?? 'N/A' }}</td>
                                                <td>
                                                    @if (auth()->user()->id == $user->id)
                                                        <form action="{{ url('remove-device') }}" method="POST"
                                                            onsubmit="return confirm('Remove this trusted device?');">
                                                            @csrf
                                                            <input type="hidden" name="id"
                                                                value="{{ $device['id'] }}">
                                                            <button type="submit">Remove</button>
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
@endsection
