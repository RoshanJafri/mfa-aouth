@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-12">
                <h2>Device Check ✅</h2>

                <p>Your device has already been marked as trusted by you</p>
                <table style="border-collapse: collapse; width: 100%; max-width: 600px;">
                    <tbody>
                        @foreach ($fingerprint as $key => $value)
                            <tr style="border-bottom: 1px solid #ddd;">
                                <th style="text-align: left; padding: 8px; background: #f5f5f5; width: 150px;">
                                    {{ ucfirst($key) }}</th>
                                <td style="padding: 8px;">{{ $value }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <br>
                <a href="{{route('dashboard')}}" class="btn btn-primary">Proceed to Admin Dashboard</a>
                <br>
            </div>
        </div>
    </div>
@endsection
