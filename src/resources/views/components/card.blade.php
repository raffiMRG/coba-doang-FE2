@props(['title', 'image', 'link', 'folderid', 'isBookmarked'])

<div class="group relative rounded-xl overflow-hidden bg-gray-900 ring-1 ring-white/10 shadow-lg shadow-black/30 transition duration-200 hover:-translate-y-1 hover:ring-indigo-500/60 hover:shadow-indigo-950/50">

    <button class="absolute top-2 right-2 z-10 flex items-center justify-center w-9 h-9 rounded-full bg-gray-950/60 backdrop-blur transition hover:bg-gray-950/80"
        onclick="toggleBookmark(this, {{ $folderid }})" title="Bookmark" data-bookmarked="{{ $isBookmarked ? 'true' : 'false' }}">
        @if ($isBookmarked)
            <svg class="w-5 h-5" viewBox="0 -0.5 33 33" xmlns="http://www.w3.org/2000/svg">
                <g fill="#facc15">
                    <polygon
                        points="27.865 31.83 17.615 26.209 7.462 32.009 9.553 20.362 0.99 12.335 12.532 10.758 17.394 0 22.436 10.672 34 12.047 25.574 20.22">
                    </polygon>
                </g>
            </svg>
        @else
            <svg class="w-5 h-5" viewBox="0 -0.5 33 33" xmlns="http://www.w3.org/2000/svg">
                <g fill="#9ca3af">
                    <polygon
                        points="27.865 31.83 17.615 26.209 7.462 32.009 9.553 20.362 0.99 12.335 12.532 10.758 17.394 0 22.436 10.672 34 12.047 25.574 20.22">
                    </polygon>
                </g>
            </svg>
        @endif
    </button>

    <a href="{{ $link }}" class="block">
        <div class="aspect-3/4 w-full overflow-hidden bg-gray-800">
            {{-- <img src="{{ $image }}" alt="{{ $title }}" class="w-full h-full object-cover transition duration-300 group-hover:scale-105" loading="lazy" /> --}}
            <img src="https://i.imgur.com/TNOs1Xx.png" alt="{{ $title }}" class="w-full h-full object-cover transition duration-300 group-hover:scale-105" loading="lazy" />
        </div>
        <div class="absolute inset-x-0 bottom-0 bg-linear-to-t from-gray-950 via-gray-950/80 to-transparent px-3 pt-8 pb-3">
            <p class="text-sm font-semibold text-white leading-snug line-clamp-2">
                {{ $title }}
            </p>
        </div>
    </a>
</div>
