@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-10">
    @if(session('info'))
        <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-6">
            {{ session('info') }}
        </div>
    @endif

    <div class="text-center">
        <h1 class="text-2xl font-bold mb-4">Welcome!</h1>
        <p class="mb-6">To continue, please log in or create an account.</p>
        <div class="flex justify-center space-x-4">
            <a href="{{ route('login') }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Log In</a>
            <a href="{{ route('register') }}" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">Register</a>
        </div>
    </div>
</div>
@endsection