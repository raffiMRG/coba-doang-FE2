<div
    class="w-full sm:max-w-sm bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
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
