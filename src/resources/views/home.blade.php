@extends('layouts.app')

@section('title', 'Home')

@section('content')
    @if ($error)
        <p class="text-red-500">{{ $error }}</p>
    @else
        <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach ($folders as $folder)
                <x-card title="{{ $folder['name'] }}" image="{{ $folder['thumbnail'] }}" link="/id/{{ $folder['id'] }}" />
            @endforeach
        </div>
    @endif

@endsection
