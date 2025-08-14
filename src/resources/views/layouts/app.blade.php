<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    @vite('resources/css/app.css')

    <title>@yield('title', 'manga')</title>
</head>

<body class="bg-gray-50 dark:bg-gray-800">
    {{-- @include('components.header') --}}
    <x-navbar></x-navbar>

    <main class="min-h-screen">
        @yield('content')
    </main>

    @include('components.footer')
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</body>

</html>
