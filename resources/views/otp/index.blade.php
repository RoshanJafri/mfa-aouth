<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('OTPs') }}
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
                                    <th class="px-4 py-2 border">Created At</th>
                                    <th class="px-4 py-2 border">OTP Hash</th>
                                    <th class="px-4 py-2 border">Used At</th>
                                    <th class="px-4 py-2 border">Attempts</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($otps as $otp)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-2 border">
                                            {{ $otp->user->email }}
                                        </td>
                                        <td class="px-4 py-2 border">
                                            {{ $otp->created_at->format('d M Y| h:i a') }}
                                        </td>

                                        <td class="px-4 py-2 border">
                                            {{ $otp->otp_hash }}
                                        </td>

                                        <td class="px-4 py-2 border">
                                            {{ $otp->used_at->format('d M Y| h:i a') }}
                                        </td>

                                        <td class="px-4 py-2 border">
                                            {{ $otp->attempts }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-4 py-6 text-center text-gray-500">
                                            No OTPSs  found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-4">
                        {{ $otps->links() }}
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>