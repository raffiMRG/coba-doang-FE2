@extends('layouts.app')

@section('title', 'Search Results')

@section('content')
    @if ($error)
        <p class="text-red-500">{{ $error }}</p>
    @else
        @if (count($folders) === 0)
            <p class="text-gray-500">Tidak ada hasil untuk pencarian "{{ request('query') }}".</p>
        @else
            <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach ($folders as $folder)
                    <x-card title="{{ $folder['name'] }}" image="{{ $folder['thumbnail'] }}" link="/id/{{ $folder['id'] }}"
                        folderid="{{ $folder['id'] }}" isBookmarked="{{ false }}" />
                @endforeach
            </div>

            <x-pagination :page="$page" :pages="$pages" :base-url="$baseUrl" />
        @endif
    @endif
@endsection
