<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Users
        </h2>
    </x-slot>

    <div class="py-8" style="margin-top: 20px">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">


            <div class="mb-4 flex justify-end">
                <a href="{{ route('users.create') }}"
                   class="px-4 py-2 border bg-indigo-600 text-white rounded">
                    Create User
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if(session('success'))
                        <div class="mb-4 text-green-600">
                            {{ session('success') }}
                        </div>
                    @endif

                    
                        <table class="w-full border border-gray-200 text-sm">
                            <thead class="bg-gray-100 text-left">
                                <tr>
                                <th class="px-4 py-2 border text-left">ID</th>
                                <th class="px-4 py-2 border text-left">Name</th>
                                <th class="px-4 py-2 border text-left">Email</th>
                                <th class="px-4 py-2 border text-left">Role</th>
                                <th class="px-4 py-2 border text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td class="px-4 py-2 border">{{ $user->id }}</td>
                                    <td class="px-4 py-2 border">{{ $user->name }}</td>
                                    <td class="px-4 py-2 border">{{ $user->email }}</td>
                                    <td class="px-4 py-2 border capitalize">
                                        {{ $user->role }}
                                    </td>
                                    <td class="px-4 py-2 border space-x-2">

                                        <a href="{{ route('users.edit', $user) }}"
                                           class="text-indigo-600 hover:underline">
                                            Edit
                                        </a>

                                        <form action="{{ route('users.destroy', $user) }}"
                                              method="POST"
                                              class="inline">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    onclick="return confirm('Delete this user?')"
                                                    class="text-red-600 hover:underline">
                                                Delete
                                            </button>
                                        </form>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>