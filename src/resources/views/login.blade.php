<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <title>Login - manga</title>
</head>

<body class="bg-gray-50 dark:bg-gray-800">
    <div class="flex items-center justify-center min-h-screen">
        <div class="w-full max-w-sm p-6 bg-white rounded-lg shadow dark:bg-gray-900">
            <h1 class="mb-6 text-xl font-semibold text-center text-gray-900 dark:text-white">Login</h1>

            @if ($error)
                <div class="mb-4 p-3 text-sm text-red-800 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-900">
                    {{ $error }}
                </div>
            @endif

            <form method="POST" action="/login">
                @csrf
                <div class="mb-4">
                    <label for="username" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Username</label>
                    <input type="text" name="username" id="username" required autofocus
                        class="block w-full p-2.5 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div class="mb-6">
                    <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                    <input type="password" name="password" id="password" required
                        class="block w-full p-2.5 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <button type="submit"
                    class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Login
                </button>
            </form>
        </div>
    </div>
</body>

</html>
