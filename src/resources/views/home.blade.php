@extends('layouts.app')

@section('title', 'Home')

@section('content')
    @if ($error)
        <p class="text-red-500">{{ $error }}</p>
    @else
        <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach ($folders as $folder)
                <x-card title="{{ $folder['name'] }}" image="{{ $folder['thumbnail'] }}" link="/id/{{ $folder['id'] }}" />
            @endforeach
        </div>

        {{-- Pagination Start --}}
        {{-- @if ($pagination && $pagination['pages'] > 1)
            <nav aria-label="Page navigation example" class="mt-6 justify-center">
                <ul class="inline-flex -space-x-px text-sm">

                    <li>
                        <a href="{{ $pagination['page'] > 1 ? route('home', ['page' => $pagination['page'] - 1]) : '#' }}"
                            class="flex items-center justify-center px-3 h-8 ms-0 leading-tight 
                        text-gray-500 bg-white border border-e-0 border-gray-300 rounded-s-lg 
                        hover:bg-gray-100 hover:text-gray-700 
                        dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 
                        dark:hover:bg-gray-700 dark:hover:text-white">
                            Previous
                        </a>
                    </li>


                    @for ($i = 1; $i <= $pagination['pages']; $i++)
                        <li>
                            <a href="{{ route('home', ['page' => $i]) }}"
                                class="flex items-center justify-center px-3 h-8 leading-tight 
                        {{ $i == $pagination['page']
                            ? 'text-blue-600 border border-gray-300 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white'
                            : 'text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white' }}">
                                {{ $i }}
                            </a>
                        </li>
                    @endfor


                    <li>
                        <a href="{{ $pagination['page'] < $pagination['pages'] ? route('home', ['page' => $pagination['page'] + 1]) : '#' }}"
                            class="flex items-center justify-center px-3 h-8 leading-tight 
                          text-gray-500 bg-white border border-gray-300 rounded-e-lg 
                          hover:bg-gray-100 hover:text-gray-700 
                          dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 
                          dark:hover:bg-gray-700 dark:hover:text-white">
                            Next
                        </a>
                    </li>
                </ul>
            </nav>
        @endif --}}
        {{-- Pagination End --}}

        <x-pagination :page="$page" :pages="$pages" :base-url="$baseUrl" />

    @endif

@endsection
