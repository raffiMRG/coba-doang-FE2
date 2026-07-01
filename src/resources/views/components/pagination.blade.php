<nav aria-label="Page navigation" class="mt-6 flex justify-center">
    <ul class="flex flex-wrap gap-2 text-sm">
        {{-- Previous --}}
        <li>
            @if (isset($_GET['query']))
                <a
                    href="{{ $page > 1 ? $baseUrl . '&page=' . ($page - 1) : '#' }}"class="flex items-center justify-center px-3 h-8 ms-0 leading-tight border rounded-s-lg
                      {{ $page > 1 ? 'text-gray-500 bg-white hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white' : 'bg-gray-200 text-gray-400 cursor-not-allowed' }}">
                @else
                    <a
                        href="{{ $page > 1 ? $baseUrl . '?page=' . ($page - 1) : '#' }}"class="flex items-center justify-center px-3 h-8 ms-0 leading-tight border rounded-s-lg
                      {{ $page > 1 ? 'text-gray-500 bg-white hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white' : 'bg-gray-200 text-gray-400 cursor-not-allowed' }}">
            @endif
            Previous
            </a>
        </li>

        {{-- Page Numbers --}}
        @for ($i = 1; $i <= $pages; $i++)
            <li>
                @if (isset($_GET['query']))
                    <a href="{{ $baseUrl . '&page=' . $i }}"
                        class="flex items-center justify-center px-3 h-8 leading-tight border 
                          {{ $i == $page
                              ? 'text-blue-600 border-gray-300 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white'
                              : 'text-gray-500 bg-white border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white' }}">
                        {{ $i }}
                    </a>
                @else
                    <a href="{{ $baseUrl . '?page=' . $i }}"
                        class="flex items-center justify-center px-3 h-8 leading-tight border 
                          {{ $i == $page
                              ? 'text-blue-600 border-gray-300 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white'
                              : 'text-gray-500 bg-white border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white' }}">
                        {{ $i }}
                    </a>
                @endif
            </li>
        @endfor

        {{-- Next --}}
        <li>
            @if (isset($_GET['query']))
                <a href="{{ $page < $pages ? $baseUrl . '&page=' . ($page + 1) : '#' }}"
                    class="flex items-center justify-center px-3 h-8 leading-tight border rounded-e-lg 
                      {{ $page < $pages ? 'text-gray-500 bg-white hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white' : 'bg-gray-200 text-gray-400 cursor-not-allowed' }}">
                @else
                    <a href="{{ $page < $pages ? $baseUrl . '?page=' . ($page + 1) : '#' }}"
                        class="flex items-center justify-center px-3 h-8 leading-tight border rounded-e-lg 
                      {{ $page < $pages ? 'text-gray-500 bg-white hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white' : 'bg-gray-200 text-gray-400 cursor-not-allowed' }}">
            @endif
            Next
            </a>
        </li>
    </ul>
</nav>
