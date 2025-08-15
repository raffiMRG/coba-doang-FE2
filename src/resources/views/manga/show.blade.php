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
        <h1>Title 1</h1>

        <h2>Pages</h2>
        @foreach ($manga['page'] as $page)
            <img src="{{ rtrim(dirname($manga['thumbnail']), '/') . '/' . $page }}" alt="Page {{ $loop->iteration }}">
        @endforeach
    </div>
    @include('components.footer')
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</body>

</html>
