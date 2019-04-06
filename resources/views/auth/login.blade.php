@extends('layouts.app')

@section('content')
    <div class="flex justify-between w-full mt-12">
        <div class="w-1/2 mt-4">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <input id="email" type="email" name="email" placeholder="E-mail" value="{{ old('email') }}" autocomplete="off" required autofocus class="rounded-sm px-2 h-10 w-full mb-2">
                <input id="password" type="password" name="password" placeholder="Password" required class="rounded-sm px-2 h-10 w-full mb-2">
                <div class="flex items-center justify-between">
                    <button type="submit" class="px-3 h-10 rounded-sm bg-green-gradient text-white font-medium mr-1">
                        Log in
                    </button>
                    <a href="/register" class="text-grey-dark ml-4">
                        Need an account?
                    </a>
                </div>
            </form>
        </div>
        @include('partials.make-it-rain')
    </div>
@endsection
