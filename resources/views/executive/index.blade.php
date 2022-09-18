@extends('layouts.deshboard')
@section('content')
    <H1>Welcome : Executive</H1>
    {{-- <a href="{{ route('executive.users') }}">User</a> /
    <a href="{{ route('executive.roles') }}">Role</a> /
    <a href="{{ route('executive.permissions') }}">Permission</a> / --}}

    @if (Route::has('login'))
        <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
            @auth
                {{-- <a href="{{ url('/home') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">Home</a> --}}
                {{-- <a href={{ Auth::user()->name }} class="text-sm text-gray-700 dark:text-gray-500 underline">Home</a> --}}
            @else
                <a href="{{ route('login') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">Log in</a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}"
                        class="ml-4 text-sm text-gray-700 dark:text-gray-500 underline">Register</a>
                @endif
            @endauth
        </div>
    @endif

    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
        <a class="dropdown-item" href="{{ route('logout') }}"
            onclick="event.preventDefault();
                                                                                                                                                             document.getElementById('logout-form').submit();">
            {{ __('Logout') }}
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>
@endsection
