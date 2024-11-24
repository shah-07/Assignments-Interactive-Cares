@extends('layouts.auth')

@section('content')
    <form class="space-y-6" action="{{ route('register') }}" method="POST">
        @csrf

        @if ($errors->any())
            <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Full Name</label>
            <div class="mt-2">
                <input id="name" name="name" type="text" autocomplete="name" placeholder="Alp Arslan"
                    value="{{ old('name') }}"
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
            </div>
            @error('name')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Username -->
        <div>
            <label for="username" class="block text-sm font-medium leading-6 text-gray-900">Username</label>
            <div class="mt-2">
                <input id="username" name="username" type="text" autocomplete="username" placeholder="alparslan1029"
                    value="{{ old('username') }}"
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
            </div>
            @error('username')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email address</label>
            <div class="mt-2">
                <input id="email" name="email" type="email" autocomplete="email" placeholder="alp.arslan@mail.com"
                    value="{{ old('email') }}"
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
            </div>
            @error('email')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Password</label>
            <div class="mt-2">
                <input id="password" name="password" type="password" autocomplete="current-password" placeholder="••••••••"
                    value="{{ old('password') }}"
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
            </div>
            @error('password')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <button type="submit"
                class="flex w-full justify-center rounded-md bg-black px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-black focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black">
                Register
            </button>
        </div>
    </form>

    <p class="mt-10 text-center text-sm text-gray-500">
        Already a member?
        <a href="{{ route('login') }}" class="font-semibold leading-6 text-black hover:text-black">Sign In</a>
    </p>
@endsection


@section('heading', 'Create a new account')
