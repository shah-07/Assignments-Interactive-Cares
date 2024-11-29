@extends('layouts.app')

@section('content')
    @if (auth()->check())
        @include('posts.create')
    @endif
    <section id="newsfeed" class="space-y-6">
        @foreach ($posts as $post)
            <article class="bg-white border-2 border-black rounded-lg shadow mx-auto max-w-none px-4 py-5 sm:px-6">
                <!-- Barta Card Top -->
                <header>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <!-- User Avatar -->
                            <!--                <div class="flex-shrink-0">-->
                            <!--                  <img-->
                            <!--                    class="h-10 w-10 rounded-full object-cover"-->
                            <!--                    src="https://avatars.githubusercontent.com/u/831997"-->
                            <!--                    alt="Tony Stark" />-->
                            <!--                </div>-->
                            <!-- /User Avatar -->

                            <!-- User Info -->
                            <div class="text-gray-900 flex flex-col min-w-0 flex-1">
                                <a href="{{ route('profile.show', $post->user->username) }}"
                                    class="hover:underline font-semibold line-clamp-1">
                                    {{ $post->user->name }}
                                </a>

                                <a href="{{ route('profile.show', $post->user->username) }}"
                                    class="hover:underline text-sm text-gray-500 line-clamp-1">
                                    {{ '@' . $post->user->username }}
                                </a>
                            </div>
                            <!-- /User Info -->
                        </div>
                    </div>
                </header>

                <!-- Content -->
                <a href="{{ route('posts.show', ['post' => $post->id]) }}">
                    <div class="py-4 text-gray-700 font-normal">
                        <p>
                            {{ $post->content }} <!-- Post content -->
                        </p>
                    </div>
                </a>


                <!-- Date Created & View Stat -->
                <div class="flex items-center gap-2 text-gray-500 text-xs my-2">
                    <span class="">{{ $post->formatted_date }}</span>
                    <span class="">•</span>
                    <span>450 views</span> <!-- Assuming you have views column -->
                </div>
            </article>
        @endforeach
    </section>
@endsection
