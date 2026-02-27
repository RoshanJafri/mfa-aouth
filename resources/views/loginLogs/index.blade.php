<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Login Activity') }}
        </h2>
    </x-slot>

    <div class="py-8" style="margin-top: 20px">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="overflow-x-auto">
                        <table class="w-full border border-gray-200 text-sm">
                            <thead class="bg-gray-100 text-left">
                                <tr>
                                    <th class="px-4 py-2 border">User</th>
                                    <th class="px-4 py-2 border">Date</th>
                                    <th class="px-4 py-2 border">IP</th>
                                    <th class="px-4 py-2 border">Device</th>
                                    <th class="px-4 py-2 border">Location</th>
                                    <th class="px-4 py-2 border">Risk</th>
                                    <th class="px-4 py-2 border">OTP</th>
                                    <th class="px-4 py-2 border">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($loginLogs as $log)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-2 border">
                                            {{ $log->user->email }}
                                        </td>
                                        <td class="px-4 py-2 border">
                                            {{ $log->created_at->format('d M Y| h:i a') }}
                                        </td>

                                        <td class="px-4 py-2 border">
                                            {{ $log->ip_address }}
                                        </td>

                                        <td class="px-4 py-2 border">
                                            {{ $log->device?->device_uuid ?? '—' }}
                                        </td>

                                        <td class="px-4 py-2 border">
                                            {{ $log->latitude }}, {{ $log->longitude }}
                                        </td>

                                        <td class="px-4 py-2 border">
                                            <span class="
                                                px-2 py-1 rounded text-xs
                                                {{ $log->risk_score >= 60 ? 'bg-red-600 text-white ' : 'bg-green-600 ' }}
                                            ">
                                                {{ $log->risk_score }}
                                            </span>
                                        </td>

                                        <td class="px-4 py-2 border">
                                            @if ($log->requires_otp)
                                                <span class="text-orange-600 font-semibold">Required</span>
                                            @else
                                                <span class="text-green-600">No</span>
                                            @endif
                                        </td>

                                        <td class="px-4 py-2 border">
                                            @if ($log->status === 'success')
                                                <span class="text-green-600 font-semibold">Success</span>
                                            @elseif ($log->status === 'pending')
                                                <span class="text-yellow-600 font-semibold">Pending</span>
                                            @else
                                                <span class="text-red-600 font-semibold">Failed</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-4 py-6 text-center text-gray-500">
                                            No login activity found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-4">
                        {{ $loginLogs->links() }}
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>