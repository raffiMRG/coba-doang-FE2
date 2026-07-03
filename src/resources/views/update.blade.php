@extends('layouts.app')

@section('title', 'Home')

@section('content')

    <h1 class="text-2xl font-bold text-white tracking-tight mb-6">Update Result</h1>

    @if ($error)
        <div class="p-4 rounded-lg bg-red-950/50 border border-red-900 text-red-300">
            {{ $error }}
        </div>
    @endif

    <div class="space-y-2">
        @foreach ($messages as $msg)
            @php
                $isSuccess = $msg['status'] === 'success';
                $alertClass = $isSuccess
                    ? 'bg-green-950/50 border-green-900 text-green-300'
                    : 'bg-amber-950/50 border-amber-900 text-amber-300';
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
