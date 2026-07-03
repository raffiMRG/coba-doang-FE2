<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $manga['name'] }}</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-950 text-gray-100 antialiased">
    <x-navbar></x-navbar>

    <div class="bg-gray-950/90 backdrop-blur border-b border-gray-800">
        <div class="max-w-3xl mx-auto px-4 py-3 flex items-center justify-between gap-4">
            <a href="javascript:history.back()" class="flex items-center gap-1 text-gray-400 hover:text-white transition text-sm shrink-0">
                <svg class="w-4 h-4" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12.5 15 7.5 10l5-5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                Back
            </a>
            <h1 class="text-sm font-semibold text-white truncate">{{ $manga['name'] }}</h1>
            <span class="text-xs text-gray-500 shrink-0">{{ count($manga['page']) }} pages</span>
        </div>
    </div>

    <div class="max-w-3xl mx-auto flex flex-col">
        @foreach ($manga['page'] as $page)
            {{-- <img src="{{ same_origin_url(rtrim(dirname($manga['thumbnail']), '/') . '/' . $page) }}" alt="Page {{ $loop->iteration }}" class="w-full h-auto" loading="lazy"> --}}
            <img src="https://i.imgur.com/TNOs1Xx.png" alt="Page {{ $loop->iteration }}" class="w-full h-auto" loading="lazy">
        @endforeach
    </div>

    @include('components.footer')
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</body>

</html>
