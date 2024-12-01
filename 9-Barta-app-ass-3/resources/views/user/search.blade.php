@extends('layouts.app')

@section('content')
    <!-- Cover Container -->
    @if ($users->isEmpty())
        <p>No users found for "{{ $query }}"</p>
    @else
        <h1 class="font-bold md:text-2xl">Search results: {{ $query }}</h1>
        @foreach ($users as $user)
            <section
                class="bg-white border-2 p-8 border-gray-800 rounded-xl min-h-[400px] space-y-8 flex items-center flex-col justify-center">
                <!-- Profile Info -->
                <div class="flex gap-4 justify-center flex-col text-center items-center">
                    <!-- Avatar -->
                    <div class="relative">
                        <img class="w-32 h-32 rounded-full border-2 border-gray-800" src="{{ $user->profile->avatar_url }}"
                            alt="{{ $user->name }}" />
                        <span
                            class="bottom-2 right-4 absolute w-3.5 h-3.5 bg-green-400 border-2 border-white dark:border-gray-800 rounded-full"></span>
                    </div>
                    <!-- /Avatar -->

                    <!-- User Meta -->
                    <div>
                        <a href="{{ route('profile.show', ['username' => $user->username]) }}"
                            class="hover:underline font-semibold line-clamp-1">
                            <h1 class="font-bold md:text-2xl">{{ $user->name }}</h1>
                        </a>
                        <p class="text-gray-700">{{ $user->profile->bio ?? 'No bio available' }}</p>
                    </div>
                    <!-- / User Meta -->
                </div>
                <!-- /Profile Info -->

                <!-- Profile Stats -->
                <div class="flex flex-row gap-16 justify-center text-center items-center">
                    <!-- Total Posts Count -->
                    <div class="flex flex-col justify-center items-center">
                        <h4 class="sm:text-xl font-bold">3</h4>
                        <p class="text-gray-600">Posts</p>
                    </div>

                    <!-- Total Comments Count -->
                    <div class="flex flex-col justify-center items-center">
                        <h4 class="sm:text-xl font-bold">14</h4>
                        <p class="text-gray-600">Comments</p>
                    </div>
                </div>
                <!-- /Profile Stats -->
            </section>
        @endforeach
    @endif
@endsection
