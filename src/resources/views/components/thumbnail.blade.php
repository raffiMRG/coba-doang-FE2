@props(['src', 'alt' => '', 'class' => 'w-full h-full object-cover', 'loading' => 'lazy'])

@php
    $placeholder = 'https://i.imgur.com/TNOs1Xx.png';
    $real = $src ? same_origin_url($src) : null;
@endphp

@if (!app()->environment('production') && $real)
    <div class="relative">
        <img src="{{ $real }}" alt="{{ $alt }}" class="{{ $class }}" loading="{{ $loading }}" />
        <img src="{{ $placeholder }}" alt="{{ $alt }}" class="absolute inset-0 w-full h-full object-cover opacity-100" loading="{{ $loading }}" />
    </div>
@else
    <img src="{{ $real ?? $placeholder }}" alt="{{ $alt }}" class="{{ $class }}" loading="{{ $loading }}" />
@endif
