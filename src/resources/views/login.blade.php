<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <title>Login - Manga Vault</title>
</head>

<body class="bg-gray-950 text-gray-100 antialiased">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="w-full max-w-sm">
            <div class="flex flex-col items-center mb-6">
                <svg class="w-9 h-9 text-indigo-500 mb-2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4 4.5A2.5 2.5 0 0 1 6.5 2H19a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1H6.5a1 1 0 0 0 0 2H20"
                        stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M4 4.5V17a2.5 2.5 0 0 0 2.5 2.5H20" stroke="currentColor" stroke-width="1.8"
                        stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <h1 class="text-xl font-bold text-white tracking-tight">Manga Vault</h1>
            </div>

            <div class="w-full p-6 bg-gray-900 rounded-xl ring-1 ring-white/10 shadow-xl shadow-black/30">
                @if ($error)
                    <div class="mb-4 p-3 text-sm rounded-lg bg-red-950/50 border border-red-900 text-red-300">
                        {{ $error }}
                    </div>
                @endif

                <form method="POST" action="/login">
                    @csrf
                    <div class="mb-4">
                        <label for="username" class="block mb-2 text-sm font-medium text-gray-300">Username</label>
                        <input type="text" name="username" id="username" required autofocus
                            class="block w-full p-2.5 text-sm text-gray-100 border border-gray-700 rounded-lg bg-gray-800 placeholder-gray-500 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                    </div>
                    <div class="mb-4">
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-300">Password</label>
                        <input type="password" name="password" id="password" required
                            class="block w-full p-2.5 text-sm text-gray-100 border border-gray-700 rounded-lg bg-gray-800 placeholder-gray-500 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                    </div>
                    <div class="mb-6 flex items-center">
                        <input type="checkbox" name="remember" id="remember" value="1"
                            class="w-4 h-4 rounded border-gray-700 bg-gray-800 text-indigo-600 focus:ring-indigo-500 focus:ring-offset-gray-900">
                        <label for="remember" class="ms-2 text-sm text-gray-300">Remember me</label>
                    </div>
                    <button type="submit"
                        class="w-full text-white bg-indigo-600 hover:bg-indigo-500 focus:ring-4 focus:ring-indigo-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center transition">
                        Login
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
