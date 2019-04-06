@extends('layouts.app')

@section('content')
    <div class="flex justify-between w-full mt-12">
        <div class="w-1/2 mt-4">
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <input id="name" type="text" name="name" placeholder="Name" value="{{ old('name') }}" required autofocus class="rounded-sm px-2 h-10 w-full mb-2">
                <input id="email" type="email" name="email" placeholder="E-mail" value="{{ old('email') }}" required class="rounded-sm px-2 h-10 w-full mb-2">
                <input id="password" type="password" name="password" placeholder="Password" required class="rounded-sm px-2 h-10 w-full mb-2">
                <input id="password" type="password" name="password_confirmation" placeholder="Repeat password" required class="rounded-sm px-2 h-10 w-full mb-2">
                <div class="flex items-center justify-between">
                    <button type="submit" class="px-3 h-10 rounded-sm bg-green-gradient text-white font-medium mr-1">
                        Register
                    </button>
                    <a href="/login" class="text-grey-dark ml-4">
                        Already have an account?
                    </a>
                </div>
            </form>
        </div>
        @include('partials.make-it-rain')
    </div>
</div>
@endsection
