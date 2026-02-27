<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create User
        </h2>
    </x-slot>

    <div class="py-6 max-w-xl mx-auto">

        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf

            <div class="mb-4">
                <x-input-label value="Name" />
                <x-text-input name="name" class="w-full mt-1" required />
            </div>

            <div class="mb-4">
                <x-input-label value="Email" />
                <x-text-input name="email" type="email" class="w-full mt-1" required />
            </div>

            <div class="mb-4">
                <x-input-label value="Password" />
                <x-text-input name="password" type="password" class="w-full mt-1" required />
            </div>

            <div class="mb-4">
                <x-input-label value="Role" />
                <select name="role" class="w-full mt-1 border rounded">
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <x-primary-button>Create</x-primary-button>
        </form>

    </div>
</x-app-layout>