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

            <!-- Create Post Card Top -->
            @if ($errors->any())
                <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('posts.update', ['post' => $post->id]) }}" method="POST" enctype="multipart/form-data"
                class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Textarea for Content -->
                <div class="mt-2 mb-5">
                    <label for="content" class="block text-gray-700 font-semibold mb-2">Edit Content</label>
                    <textarea id="content" name="content" rows="5"
                        class="block w-full p-3 text-gray-900 rounded-lg border border-gray-300 outline-none focus:ring-2 focus:ring-gray-600"
                        placeholder="Enter your updated content here...">{{ old('content', $post->content) }}</textarea>
                </div>

                <!-- Picture Preview and Upload -->
                <div class="mt-5">
                    <label for="image" class="block text-gray-700 font-semibold mb-2">Update Picture</label>

                    <!-- Existing Picture -->
                    @if ($post->image)
                        <div class="mb-4">
                            <img src="{{ asset('storage/' . $post->image) }}" alt="Current Picture"
                                class="w-48 h-48 rounded-lg object-cover border">
                            <div class="mt-2">
                                <input type="checkbox" name="delete_picture" id="delete_picture" class="mr-2">
                                <label for="delete_picture" class="text-sm text-red-600 hover:text-red-800">Delete Current
                                    Picture</label>
                            </div>
                        </div>
                    @endif

                    <!-- Upload Picture Button -->
                    <div>
                        <input type="file" name="picture" id="picture" class="hidden" />

                        <label for="picture"
                            class="-m-2 flex gap-2 text-xs items-center rounded-full p-2 text-gray-600 hover:text-gray-800 cursor-pointer">
                            <span class="sr-only">Picture</span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                            </svg>
                            <span>Add Picture</span>
                        </label>
                    </div>
                    <!-- /Upload Picture Button -->
                </div>
                <!-- Action Buttons -->
                <div class="flex items-center justify-end space-x-4 mt-3">
                    <!-- Cancel Button -->
                    <a href="{{ route('profile.show', auth()->user()->username) }}"
                        class="flex items-center rounded-full px-4 py-2 text-xs font-semibold text-gray-800 bg-gray-200 hover:bg-gray-300">
                        Cancel
                    </a>

                    <!-- Update Button -->
                    <button type="submit"
                        class="flex items-center rounded-full px-4 py-2 text-xs font-semibold text-white bg-gray-800 hover:bg-black">
                        Update
                    </button>
                </div>
            </form>


            <!-- /Create Post Card Bottom -->
        </article>
    </section>
@endsection
