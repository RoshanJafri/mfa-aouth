<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create User
        </h2>
    </x-slot>

    <div class="py-6 max-w-xl mx-auto">

        <form method="POST" action="{{ route('users.update', $user) }}">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <x-input-label value="Name" />
                <x-text-input name="name" value="{{ $user->name }}" />
            </div>

            <div class="mb-4">
                <x-input-label value="Email" />
                <x-text-input name="email" value="{{ $user->email }}" />
            </div>
            <div class="mb-4">
                <x-input-label value="Role" />
                <select name="role" class="w-full mt-1 border rounded">
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <x-primary-button>Update</x-primary-button>
        </form>

    </div>
</x-app-layout>