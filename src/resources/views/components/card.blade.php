@props(['title', 'image', 'link', 'folderid', 'isBookmarked'])

<div
    class="relative w-full sm:max-w-sm bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">

    <!-- ⭐ Tombol Bookmark -->
    <button class="absolute top-2 right-2 transition" onclick="toggleBookmark(this, {{ $folderid }})" title="Bookmark"
        data-bookmarked="{{ $isBookmarked ? 'true' : 'false' }}">

        @if ($isBookmarked)
            {{-- ⭐ Bookmark Aktif (kuning) --}}
            <svg class="w-8 h-8" viewBox="0 -0.5 33 33" xmlns="http://www.w3.org/2000/svg">
                <g fill="#ffdd00">
                    <polygon
                        points="27.865 31.83 17.615 26.209 7.462 32.009 9.553 20.362 0.99 12.335 12.532 10.758 17.394 0 22.436 10.672 34 12.047 25.574 20.22">
                    </polygon>
                </g>
            </svg>
        @else
            {{-- ☆ Bookmark Nonaktif (abu-abu) --}}
            <svg class="w-8 h-8" viewBox="0 -0.5 33 33" xmlns="http://www.w3.org/2000/svg">
                <g fill="#c2c2c2">
                    <polygon
                        points="27.865 31.83 17.615 26.209 7.462 32.009 9.553 20.362 0.99 12.335 12.532 10.758 17.394 0 22.436 10.672 34 12.047 25.574 20.22">
                    </polygon>
                </g>
            </svg>
        @endif
    </button>

    <a href="{{ $link }}">
        <img class="rounded-t-lg" src="{{ $image }}" alt="{{ $title }}" />
        {{-- <img class="rounded-t-lg" src="img/1.webp" alt="{{ $title }}" /> --}}

    </a>
    <div class="p-5">
        <a href="{{ $link }}">
            <p class="mb-2 text-sm font-bold tracking-tight text-gray-900 dark:text-white">
                {{ $title }}
            </p>
        </a>
    </div>
</div>
