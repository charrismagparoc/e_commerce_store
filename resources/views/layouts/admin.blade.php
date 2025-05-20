
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'L.M.C') }} Admin - @yield('title', 'Dashboard')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased min-h-screen bg-gray-100">
    <div class="flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-800 text-white h-screen fixed">
    <div class="px-4 py-5">
        <h1 class="text-2xl font-bold">L.M.C Admin</h1>

        <!-- Admin Avatar and Name -->
        <div class="flex items-center mt-4">
            @if (auth()->user()->avatar)
                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Avatar" class="rounded-full w-10 h-10">
            @else
                <img src="https://via.placeholder.com/40" alt="Default Avatar" class="rounded-full w-10 h-10">
            @endif
            <span class="ml-3 text-sm font-medium">{{ auth()->user()->name }}</span>
        </div>
    </div>

    <nav class="mt-5">
        <ul class="space-y-2">
            <li>
    <a href="{{ route('admin.profile.edit') }}" class="block px-4 py-2 hover:bg-gray-700 {{ request()->routeIs('admin.profile.edit') ? 'bg-gray-700' : '' }}">
        Profile
    </a>
</li>

            <li>
                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 hover:bg-gray-700 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700' : '' }}">
                    Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('admin.products.index') }}" class="block px-4 py-2 hover:bg-gray-700 {{ request()->routeIs('admin.products.*') ? 'bg-gray-700' : '' }}">
                    Products
                </a>
            </li>
            <li>
                <a href="{{ route('admin.categories.index') }}" class="block px-4 py-2 hover:bg-gray-700 {{ request()->routeIs('admin.categories.*') ? 'bg-gray-700' : '' }}">
                    Categories
                </a>
            </li>
            <li>
                <a href="{{ route('admin.orders.index') }}" class="block px-4 py-2 hover:bg-gray-700 {{ request()->routeIs('admin.orders.*') ? 'bg-gray-700' : '' }}">
                    Orders
                </a>
            </li>
            <li class="mt-8 border-t border-gray-700 pt-4">
                <a href="{{ route('home') }}" class="block px-4 py-2 hover:bg-gray-700">
                    Back to Shop
                </a>
            </li>
        </ul>
    </nav>
</aside>

        <!-- Main Content -->
        <main class="flex-1 ml-64 p-8">
            <!-- Flash Messages -->
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold">@yield('header', 'Dashboard')</h1>
            </div>

            <!-- Content -->
            @yield('content')
        </main>
    </div>
</body>
</html>
```