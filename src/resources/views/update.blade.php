@extends('layouts.app')

@section('title', 'Home')

@section('content')

    <h1 class="text-2xl font-bold mb-4">Update Result</h1>

    @if ($error)
        <div class="p-4 text-red-800 border border-red-300 bg-red-50 rounded-lg">
            {{ $error }}
        </div>
    @endif

    <div class="space-y-2">
        @foreach ($messages as $msg)
            @php
                $isSuccess = $msg['status'] === 'success';
                $alertClass = $isSuccess
                    ? 'text-green-800 border-green-300 bg-green-50 dark:bg-gray-800 dark:text-green-400 dark:border-green-800'
                    : 'text-yellow-800 border-yellow-300 bg-yellow-50 dark:bg-gray-800 dark:text-yellow-300 dark:border-yellow-800';
            @endphp

            <div class="flex items-center p-4 mb-4 text-sm border rounded-lg {{ $alertClass }}" role="alert">
                <svg class="shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                    fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3
                                 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1
                                 1 1v4h1a1 1 0 0 1 0 2Z" />
                </svg>
                <div>
                    <span class="font-medium">{{ $isSuccess ? 'Success' : 'Warning' }}:</span>
                    {{ $msg['folder_name'] }}
                    @if (isset($msg['error']))
                        - {{ $msg['error'] }}
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@endsection
