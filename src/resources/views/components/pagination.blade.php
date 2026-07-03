<nav aria-label="Page navigation" class="mt-10 flex justify-center">
    <ul class="flex flex-wrap items-center gap-1.5 text-sm">
        {{-- Previous --}}
        <li>
            @php
                $prevUrl = isset($_GET['query'])
                    ? $baseUrl . '&page=' . ($page - 1)
                    : $baseUrl . '?page=' . ($page - 1);
            @endphp
            <a href="{{ $page > 1 ? $prevUrl : '#' }}"
                class="flex items-center justify-center px-3 h-9 rounded-lg border border-gray-800 transition
                      {{ $page > 1 ? 'text-gray-300 bg-gray-900 hover:bg-gray-800 hover:text-white' : 'text-gray-600 bg-gray-900/50 cursor-not-allowed pointer-events-none' }}">
                Previous
            </a>
        </li>

        {{-- Page Numbers --}}
        @for ($i = 1; $i <= $pages; $i++)
            <li>
                @php
                    $pageUrl = isset($_GET['query']) ? $baseUrl . '&page=' . $i : $baseUrl . '?page=' . $i;
                @endphp
                <a href="{{ $pageUrl }}"
                    class="flex items-center justify-center w-9 h-9 rounded-lg border transition
                          {{ $i == $page
                              ? 'text-white bg-indigo-600 border-indigo-600'
                              : 'text-gray-300 bg-gray-900 border-gray-800 hover:bg-gray-800 hover:text-white' }}">
                    {{ $i }}
                </a>
            </li>
        @endfor

        {{-- Next --}}
        <li>
            @php
                $nextUrl = isset($_GET['query'])
                    ? $baseUrl . '&page=' . ($page + 1)
                    : $baseUrl . '?page=' . ($page + 1);
            @endphp
            <a href="{{ $page < $pages ? $nextUrl : '#' }}"
                class="flex items-center justify-center px-3 h-9 rounded-lg border border-gray-800 transition
                      {{ $page < $pages ? 'text-gray-300 bg-gray-900 hover:bg-gray-800 hover:text-white' : 'text-gray-600 bg-gray-900/50 cursor-not-allowed pointer-events-none' }}">
                Next
            </a>
        </li>
    </ul>
</nav>
