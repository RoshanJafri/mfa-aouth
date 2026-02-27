<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            My Devices
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <h3 class="text-lg font-semibold mb-6">
                    Devices You've Logged In With
                </h3>

                <div class="space-y-4">

                    @forelse($devices as $device)
                        <div class="border rounded-lg p-4 flex justify-between items-center hover:bg-gray-50">

                            <div>
                                <div class="font-semibold text-gray-800">
                                    {{ $device->readableName() }}
                                </div>

                                <div class="text-sm text-gray-500 mt-1">
                                    Last used {{ $device->last_used_at?->diffForHumans() }}
                                </div>

                                <div class="text-sm text-gray-500">
                                    Location: {{ $device->latitude }}, {{ $device->longitude }}
                                    <br>
                                    @php
                                        $location = $device->getCityCountry($device->latitude, $device->longitude);
                                    @endphp
                                    {{ $location['city'] }}, {{ $location['country'] }}
                                </div>

                                @if($device->trusted)
                                    <span class="inline-block mt-2 text-xs bg-green-100 text-green-700 px-2 py-1 rounded">
                                        Trusted Device
                                    </span>
                                @else
                                    <span class="inline-block mt-2 text-xs bg-yellow-100 text-yellow-700 px-2 py-1 rounded">
                                        Not Trusted
                                    </span>
                                @endif
                            </div>

                            <div>
                                @if($device->trusted)
                                    <form method="POST" action="{{ route('devices.untrust', $device) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button class="text-red-600 hover:underline text-sm">
                                            Remove Trust
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('devices.trust', $device) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button class="text-indigo-600 hover:underline text-sm">
                                            Trust This Device
                                        </button>
                                    </form>
                                @endif
                            </div>

                        </div>
                    @empty
                        <p class="text-gray-500">
                            No devices found.
                        </p>
                    @endforelse

                </div>

            </div>

        </div>
    </div>
</x-app-layout>