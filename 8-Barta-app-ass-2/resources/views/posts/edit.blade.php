@extends('layouts.app')

@section('content')
    <section id="newsfeed" class="space-y-6">
        <article class="bg-white border-2 border-black rounded-lg shadow mx-auto max-w-none px-4 py-5 sm:px-6 mb-5">
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
                            <a href="{{ route('profile.show', ['username' => $user->username]) }}"
                                class="hover:underline font-semibold line-clamp-1">
                                {{ $user->name }}
                            </a>

                            <a href="{{ route('profile.show', ['username' => $user->username]) }}"
                                class="hover:underline text-sm text-gray-500 line-clamp-1">
                                {{ '@' . $user->username }}
                            </a>
                        </div>
                        <!-- /User Info -->
                    </div>

                </div>
            </header>

            <!-- Create Post Card Top -->
            <form action="{{ route('posts.update', ['post' => $post->id]) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mt-2 mb-5">
                    <div class="flex items-start /space-x-3/">
                        <!-- Content -->
                        <div class="text-gray-700 font-normal w-full">
                            <textarea class="block w-full p-2 pt-2 text-gray-900 rounded-lg border outline-none focus:ring-0 focus:ring-offset-0"
                                name="content" rows="5">{{ $post->content }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Create Post Card Bottom -->
                <div>
                    <!-- Card Bottom Action Buttons -->
                    <div class="flex items-center justify-end">
                        <div class="flex space-x-6">
                            <!-- Post Button -->
                            <a href="{{ url()->previous() }}">
                                <button type="submit"
                                    class="-m-2 flex gap-2 text-xs items-center rounded-full px-4 py-2 font-semibold bg-red-800 text-white">
                                    Cancel
                                </button>
                            </a>

                            <button type="submit"
                                class="-m-2 flex gap-2 text-xs items-center rounded-full px-4 py-2 font-semibold bg-gray-800 hover:bg-black text-white">
                                Update
                            </button>

                            <!-- /Post Button -->
                        </div>
                    </div>
                    <!-- /Card Bottom Action Buttons -->
                </div>
            </form>
            <!-- /Create Post Card Bottom -->
        </article>
    </section>
@endsection
