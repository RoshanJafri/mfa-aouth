<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Devices') }}
        </h2>
    </x-slot>

    <div class="py-8" style="margin-top: 20px">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="overflow-x-auto">
                        <table class="min-w-max w-full border border-gray-200 text-sm">
                            <thead class="bg-gray-100 text-left whitespace-nowrap">
                                <tr>
                                    <th class="px-4 py-2 border">User</th>
                                    <th class="px-4 py-2 border">Device UUID</th>
                                    <th class="px-4 py-2 border">Trusted</th>
                                    <th class="px-4 py-2 border">Last Used</th>
                                    <th class="px-4 py-2 border">Location</th>
                                    <th class="px-4 py-2 border">Created At</th>
                                </tr>
                            </thead>

                            <tbody class="whitespace-nowrap">
                                @forelse ($devices as $device)
                                                            <tr class="hover:bg-gray-50">
                                                                <td class="px-4 py-2 border">
                                                                    {{ $device->user->email }}
                                                                </td>

                                                                <td class="px-4 py-2 border font-mono text-xs">
                                                                    {{ $device->device_uuid }}
                                                                </td>

                                                                <td class="px-4 py-2 border">
                                                                    <span class="px-2 py-1 rounded text-xs 
                                                                                    {{ !$device->trusted
                                    ? 'bg-red-600 text-white'
                                    : 'bg-green-100 text-green-700' }}">
                                                                        {{ $device->trusted ? 'Yes' : 'No' }}
                                                                    </span>
                                                                </td>

                                                                <td class="px-4 py-2 border">
                                                                    {{ $device->last_used_at->format('d M Y | h:i a') }}
                                                                </td>

                                                                <td class="px-4 py-2 border">
                                                                    {{ $device->latitude }}, {{ $device->longitude }}
                                                                </td>

                                                                <td class="px-4 py-2 border">
                                                                    {{ $device->created_at->format('d M Y | h:i a') }}
                                                                </td>
                                                            </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-4 py-6 text-center text-gray-500">
                                            No devices found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-4">
                        {{ $devices->links() }}
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>