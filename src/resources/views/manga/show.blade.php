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
        {{-- <img src="http://192.168.1.133:8080/sementara/%28100%29%20%5BShiawase%20Kyouwakoku%20%28Shiawase%20no%20Katachi%29%5D%20Oyako%20Soukan%202%20Buta-san%20no%20Seishi%20de%20Haranda%20Watashi/1.jpg"
            alt="Page 1">
        <img src="http://192.168.1.133:8080/sementara/%28100%29%20%5BShiawase%20Kyouwakoku%20%28Shiawase%20no%20Katachi%29%5D%20Oyako%20Soukan%202%20Buta-san%20no%20Seishi%20de%20Haranda%20Watashi/1.jpg"
            alt="Page 2">
        <img src="http://192.168.1.133:8080/sementara/%28100%29%20%5BShiawase%20Kyouwakoku%20%28Shiawase%20no%20Katachi%29%5D%20Oyako%20Soukan%202%20Buta-san%20no%20Seishi%20de%20Haranda%20Watashi/1.jpg"
            alt="Page 3"> --}}
    </div>
    @include('components.footer')
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</body>

</html>
