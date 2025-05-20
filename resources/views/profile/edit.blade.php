@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="container mx-auto px-4 py-10 max-w-2xl">
    <h1 class="text-3xl font-semibold text-gray-800 mb-8">Edit Profile</h1>

    <!-- Success Message -->
    @if (session('status') === 'profile-updated')
        <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded mb-6">
            Profile updated successfully.
        </div>
    @endif

    <!-- Profile Information -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 mb-10">
        <h2 class="text-xl font-semibold text-gray-700 dark:text-white mb-4">Profile Information</h2>

        <form action="{{ route('profile.update') }}" method="POST" class="space-y-5">
            @csrf
            @method('PATCH')

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                    class="w-full mt-1 p-2 border rounded-md shadow-sm dark:bg-gray-700 dark:text-white @error('name') border-red-500 @enderror" required>
                @error('name')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                    class="w-full mt-1 p-2 border rounded-md shadow-sm dark:bg-gray-700 dark:text-white @error('email') border-red-500 @enderror" required>
                @error('email')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
                    Save Changes
                </button>
            </div>
        </form>
    </div>

    <!-- Update Password -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 mb-10">
        <h2 class="text-xl font-semibold text-gray-700 dark:text-white mb-4">Update Password</h2>

        <form action="{{ route('password.update') }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Current Password</label>
                <input type="password" id="current_password" name="current_password"
                    class="w-full mt-1 p-2 border rounded-md shadow-sm dark:bg-gray-700 dark:text-white @error('current_password') border-red-500 @enderror" required>
                @error('current_password')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">New Password</label>
                <input type="password" id="password" name="password"
                    class="w-full mt-1 p-2 border rounded-md shadow-sm dark:bg-gray-700 dark:text-white @error('password') border-red-500 @enderror" required>
                @error('password')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Confirm New Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                    class="w-full mt-1 p-2 border rounded-md shadow-sm dark:bg-gray-700 dark:text-white" required>
            </div>

            <div class="flex justify-end">
                <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
                    Update Password
                </button>
            </div>
        </form>
    </div>

    <!-- Delete Account -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h2 class="text-xl font-semibold text-red-600 dark:text-red-400 mb-4">Delete Account</h2>

        <form action="{{ route('profile.destroy') }}" method="POST" class="space-y-5">
            @csrf
            @method('DELETE')

            <p class="text-sm text-gray-600 dark:text-gray-300">
                Once your account is deleted, all of its resources and data will be permanently deleted.
                Please enter your password to confirm you want to permanently delete your account.
            </p>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                <input type="password" id="password" name="password"
                    class="w-full mt-1 p-2 border rounded-md shadow-sm dark:bg-gray-700 dark:text-white @error('password') border-red-500 @enderror" required>
                @error('password')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit"
                    class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition">
                    Delete Account
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
