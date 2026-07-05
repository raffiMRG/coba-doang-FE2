@extends('layouts.app')

@section('title', 'Search Results')

@section('content')
    <h1 class="text-2xl font-bold text-white tracking-tight mb-6">
        Search results for <span class="text-indigo-400">"{{ request('query') }}"</span>
    </h1>

    @if ($error)
        <div class="flex items-center gap-3 p-4 rounded-lg bg-red-950/50 border border-red-900 text-red-300">
            {{ $error }}
        </div>
    @else
        @if (count($folders) === 0)
            <p class="text-gray-500 text-center py-16">Tidak ada hasil untuk pencarian "{{ request('query') }}".</p>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                @foreach ($folders as $folder)
                    <x-card title="{{ $folder['name'] }}" image="{{ $folder['thumbnail'] }}" link="/id/{{ $folder['id'] }}"
                        folderid="{{ $folder['id'] }}" isBookmarked="{{ $folder['is_bookmarked'] }}"
                        isTranslated="{{ $folder['is_translated'] }}" />
                @endforeach
            </div>

            <x-pagination :page="$page" :pages="$pages" :base-url="$baseUrl" />
            <script>
                async function toggleBookmark(btn, folderId) {
                    const isBookmarked = btn.dataset.bookmarked === 'true';

                    try {
                        // Lewat Laravel (bukan langsung ke backend Go), karena
                        // browser tidak punya akses ke access_token di session.
                        const response = await fetch('/bookmarks/toggle', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-XSRF-TOKEN': decodeURIComponent(document.cookie.match(/XSRF-TOKEN=([^;]+)/)?.[1] || '')
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
    @endif
@endsection
