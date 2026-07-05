@props(['title', 'image', 'link', 'folderid', 'isBookmarked', 'isTranslated'])

<div class="group relative rounded-xl overflow-hidden bg-gray-900 ring-1 ring-white/10 shadow-lg shadow-black/30 transition duration-200 hover:-translate-y-1 hover:ring-indigo-500/60 hover:shadow-indigo-950/50">

    <div class="absolute top-2 right-2 z-10 flex items-center gap-2">
        @if ($isTranslated)
            <button class="flex items-center justify-center w-9 h-9 rounded-full bg-gray-950/60 backdrop-blur transition hover:bg-gray-950/80"
                title="Translate" data-translated="true">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12.913 17H20.087M12.913 17L11 21M12.913 17L15.7783 11.009C16.0092 10.5263 16.1246 10.2849 16.2826 10.2086C16.4199 10.1423 16.5801 10.1423 16.7174 10.2086C16.8754 10.2849 16.9908 10.5263 17.2217 11.009L20.087 17M20.087 17L22 21M2 5H8M8 5H11.5M8 5V3M11.5 5H14M11.5 5C11.0039 7.95729 9.85259 10.6362 8.16555 12.8844M10 14C9.38747 13.7248 8.76265 13.3421 8.16555 12.8844M8.16555 12.8844C6.81302 11.8478 5.60276 10.4266 5 9M8.16555 12.8844C6.56086 15.0229 4.47143 16.7718 2 18"
                        stroke="#9ca3af" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>
        @endif

        <button class="flex items-center justify-center w-9 h-9 rounded-full bg-gray-950/60 backdrop-blur transition hover:bg-gray-950/80"
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
    </div>

    <a href="{{ $link }}" class="block">
        <div class="aspect-3/4 w-full overflow-hidden bg-gray-800">
            <img src="{{ $image }}" alt="{{ $title }}" class="w-full h-full object-cover transition duration-300 group-hover:scale-105" loading="lazy" />
        </div>
        <div class="absolute inset-x-0 bottom-0 bg-linear-to-t from-gray-950 via-gray-950/80 to-transparent px-3 pt-8 pb-3">
            <p class="text-sm font-semibold text-white leading-snug line-clamp-2">
                {{ $title }}
            </p>
        </div>
    </a>
</div>
