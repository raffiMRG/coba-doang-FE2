@extends('layouts.app')

@section('title', 'Home')

@section('content')
    @if ($error)
        <p class="text-red-500">{{ $error }}</p>
    @else
        <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach ($folders as $folder)
                {{-- <p>{{ var_dump($folder) }}</p> --}}
                <x-card title="{{ $folder['name'] }}" image="{{ $folder['thumbnail'] }}" link="/id/{{ $folder['id'] }}"
                    folderid="{{ $folder['id'] }}" isBookmarked="{{ $folder['is_bookmarked'] }}" />
            @endforeach
        </div>

        <x-pagination :page="$page" :pages="$pages" :base-url="$baseUrl" />
        <script>
            async function toggleBookmark(btn, folderId) {
                const isBookmarked = btn.dataset.bookmarked === 'true';

                try {
                    // const response = await fetch('http://localhost:8181/bookmarks', {
                    const response = await fetch(`{{ rtrim(config('app.api_url'), '/') }}/bookmarks`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            folder_id: folderId
                        })
                    });

                    if (response.ok) {
                        // Toggle status
                        btn.dataset.bookmarked = (!isBookmarked).toString();

                        // Ganti ikon SVG
                        btn.innerHTML = !isBookmarked ?
                            `<svg class="w-8 h-8" viewBox="0 -0.5 33 33" xmlns="http://www.w3.org/2000/svg">
                        <g fill="#ffdd00">
                            <polygon points="27.865 31.83 17.615 26.209 7.462 32.009 9.553 20.362 
                            0.99 12.335 12.532 10.758 17.394 0 22.436 10.672 34 12.047 25.574 20.22"></polygon>
                        </g>
                       </svg>` :
                            `<svg class="w-8 h-8" viewBox="0 -0.5 33 33" xmlns="http://www.w3.org/2000/svg">
                        <g fill="#c2c2c2">
                            <polygon points="27.865 31.83 17.615 26.209 7.462 32.009 9.553 20.362 
                            0.99 12.335 12.532 10.758 17.394 0 22.436 10.672 34 12.047 25.574 20.22"></polygon>
                        </g>
                       </svg>`;
                    } else {
                        console.error('Bookmark request failed');
                    }
                } catch (err) {
                    console.error('Network error:', err);
                }
            }
        </script>
    @endif
@endsection
