<nav class="sticky top-0 z-50 bg-gray-950/80 backdrop-blur border-b border-gray-800">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
        <a href="/home" class="flex items-center gap-2 shrink-0">
            <svg class="w-7 h-7 text-indigo-500" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M4 4.5A2.5 2.5 0 0 1 6.5 2H19a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1H6.5a1 1 0 0 0 0 2H20"
                    stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M4 4.5V17a2.5 2.5 0 0 0 2.5 2.5H20" stroke="currentColor" stroke-width="1.8"
                    stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <span class="self-center text-xl font-bold tracking-tight text-white whitespace-nowrap">Manga
                Vault</span>
        </a>

        <div class="flex md:order-2 items-center gap-2">
            <div class="relative hidden md:block">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                    </svg>
                    <span class="sr-only">Search icon</span>
                </div>
                <input type="text" id="search-navbar"
                    class="block w-56 p-2 ps-10 text-sm text-gray-100 border border-gray-700 rounded-lg bg-gray-900 placeholder-gray-500 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
                    placeholder="Search manga...">
            </div>
            <button data-collapse-toggle="navbar-search" type="button"
                class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-400 rounded-lg md:hidden hover:bg-gray-800 hover:text-white focus:outline-none focus:ring-2 focus:ring-gray-700"
                aria-controls="navbar-search" aria-expanded="false">
                <span class="sr-only">Open main menu</span>
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 17 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M1 1h15M1 7h15M1 13h15" />
                </svg>
            </button>
        </div>

        <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-search">
            <div class="relative mt-3 md:hidden flex items-center space-x-2">
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                        </svg>
                    </div>
                    <input type="text" id="search-navbar-mobile"
                        class="block w-full p-2 ps-10 text-sm text-gray-100 border border-gray-700 rounded-lg bg-gray-900 placeholder-gray-500 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
                        placeholder="Search manga..." />
                </div>

                <button type="button" id="searchButtonMobile"
                    class="text-gray-400 hover:bg-gray-800 hover:text-white focus:outline-none focus:ring-2 focus:ring-gray-700 rounded-lg text-sm p-2.5">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                    </svg>
                    <span class="sr-only">Search</span>
                </button>
            </div>

            @php
                $navLink = fn(string $pattern) => request()->is($pattern)
                    ? 'text-white bg-indigo-600'
                    : 'text-gray-400 hover:text-white hover:bg-gray-800';
            @endphp

            <ul
                class="flex flex-col p-4 md:p-0 mt-4 font-medium md:space-x-1 md:flex-row md:mt-0 md:border-0">
                <li>
                    <a href="/home"
                        class="block py-2 px-3 rounded-lg transition {{ $navLink('home') }}">Home</a>
                </li>
                <li>
                    <a href="/status"
                        class="block py-2 px-3 rounded-lg transition {{ $navLink('status') }}">Status</a>
                </li>
                <li>
                    <a href="/bookmark"
                        class="block py-2 px-3 rounded-lg transition {{ $navLink('bookmark') }}">Bookmark</a>
                </li>
                <li>
                    <a href="/update"
                        class="block py-2 px-3 rounded-lg transition {{ $navLink('update') }}">Update</a>
                </li>
                <li>
                    <a href="/backup"
                        class="block py-2 px-3 rounded-lg transition {{ $navLink('backup') }}">Backup</a>
                </li>
                <li>
                    <a href="/extract"
                        class="block py-2 px-3 rounded-lg transition {{ $navLink('extract') }}">Extract</a>
                </li>
                <li>
                    <a href="/translate"
                        class="block py-2 px-3 rounded-lg transition {{ $navLink('translate') }}">Translate</a>
                </li>
                <li>
                    <form method="POST" action="/logout">
                        @csrf
                        <button type="submit"
                            class="block w-full text-left py-2 px-3 rounded-lg text-gray-400 hover:text-white hover:bg-gray-800 transition">Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>
<script>
    document.getElementById("searchButtonMobile").addEventListener("click", function() {
        const query = document.getElementById("search-navbar-mobile").value.trim();
        if (query) {
            window.location.href = `/search?query=${encodeURIComponent(query)}`;
        }
    });

    document.getElementById("search-navbar").addEventListener("keydown", function(e) {
        if (e.key === "Enter" && this.value.trim()) {
            window.location.href = `/search?query=${encodeURIComponent(this.value.trim())}`;
        }
    });
</script>
