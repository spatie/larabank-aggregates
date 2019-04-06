<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Bungee" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="bg-black pb-4">
    <div class="max-w-lg mx-auto">
        <header class="mx-4 my-4 flex items-center justify-between">
            <h1 class="logotype pr-1">
                💸 LARABANK
            </h1>
            @auth
                <ul class="flex">
                    <li>
                        <a
                            href="{{ route('logout') }}"
                            class="text-grey-dark"
                            onclick="event.preventDefault();document.getElementById('logout-form').submit();"
                        >
                            Log out
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                </ul>
            @endguest
        </header>
    </div>
    <main class="max-w-lg mx-auto px-4">
        @include('flash::message')
        @yield('content')
    </main>
</body>
</html>
