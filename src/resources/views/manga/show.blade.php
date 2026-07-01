<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- <title>{{ $manga['name'] }}</title> --}}
    <title>Title 1 </title>
    @vite('resources/css/app.css')
</head>

<body>
    <x-navbar></x-navbar>
    <div class="manga-container">
        {{-- <h1>{{ $manga['name'] }}</h1>
        <img src="{{ $manga['thumbnail'] }}" alt="Thumbnail"> --}}
        {{-- <h1>{{ $manga['name'] }}</h1>

        <h2>Pages : {{ count($manga['page']) }}</h2>
        <h2>#{{ $manga['id'] }}</h2> --}}
        <div class="mb-8 px-3 py-3">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ $manga['name'] }}</h1>
            <div class="flex gap-6">
                <h2 class="text-lg text-gray-600">
                    <span class="font-semibold">Pages:</span>
                    <span class="text-blue-600 font-bold">{{ count($manga['page']) }}</span>
                </h2>
                <h2 class="text-lg text-gray-600">
                    <span class="font-semibold">ID:</span>
                    <span class="text-blue-600 font-bold">#{{ $manga['id'] }}</span>
                </h2>
            </div>
        </div>
        @foreach ($manga['page'] as $page)
            <img src="{{ rtrim(dirname($manga['thumbnail']), '/') . '/' . $page }}" alt="Page {{ $loop->iteration }}">
            {{-- <img src="/img/1.webp" alt="Page {{ $loop->iteration }}"> --}}
        @endforeach
    </div>
    @include('components.footer')
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</body>

</html>
