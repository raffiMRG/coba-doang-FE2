@extends('layouts.app')

@section('title', 'Folder List')

@section('content')

    {{-- <h1 class="">Folder List</h1> --}}

    @if ($error)
        <p style="color: red;">{{ $error }}</p>
    @else
        {{-- <table class="text-white">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Folder Name</th>
                    <th>Action</th>
                    <th>Thumbnail</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($folders as $folder)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $folder['name'] }}</td>
                        <td><input id="default-checkbox" type="checkbox" value=""
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        </td>
                        <td>
                            <img src="{{ $folder['thumbnail'] }}" class="w-3xs">
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table> --}}

        <div id="alert-4"
            class="hidden fixed top-3 right-6 items-center p-4 mb-4 text-yellow-800 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-300"
            role="alert">
            <svg class="shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                viewBox="0 0 20 20">
                <path
                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
            </svg>
            <span class="sr-only">Info</span>
            <div class="ms-3 text-sm font-medium">
                Pilih minimal 1 sebelum melanjutkan.
            </div>
            <button type="button"
                class="ms-auto -mx-1.5 -my-1.5 bg-yellow-50 text-yellow-500 rounded-lg focus:ring-2 focus:ring-yellow-400 p-1.5 hover:bg-yellow-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-yellow-300 dark:hover:bg-gray-700"
                data-dismiss-target="#alert-4" aria-label="Close">
                <span class="sr-only">Close</span>
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
            </button>
        </div>

        <!-- Alert (hidden by default) -->
        {{-- <div id="alert-4" class="hidden flex items-center p-4 mb-4 text-yellow-800 rounded-lg bg-yellow-50" role="alert"> --}}
        {{-- <div id="alert-4" class="hidden fixed top-3 right-6 items-center p-4 mb-4 text-yellow-800 rounded-lg bg-yellow-50"
            role="alert">
            <svg class="shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path
                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
            </svg>
            <div class="ms-3 text-sm font-medium">
                Pilih minimal 1 folder sebelum melanjutkan.
            </div>
        </div> --}}

        <ul class="grid w-full gap-6 md:grid-cols-1">
            @foreach ($folders as $folder)
                <li>
                    <input type="checkbox" id="{{ $folder['id'] }}-option" value={{ $folder['id'] }} class="hidden peer"
                        required="">
                    <label for="{{ $folder['id'] }}-option"
                        class="inline-flex items-center justify-between w-full p-3 text-gray-500 bg-white border-2 border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 peer-checked:border-blue-600 dark:peer-checked:border-blue-600 hover:text-gray-600 dark:peer-checked:text-gray-300 peer-checked:text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">
                        <div class="flex items-center justify-between w-full">
                            <div class="w-12 text-sm">{{ $loop->iteration }}</div>
                            <div class="w-full text-sm">{{ $folder['name'] }}</div>
                            <img src="{{ $folder['thumbnail'] }}" class="w-20">
                        </div>
                    </label>
                </li>
            @endforeach
        </ul>

        {{-- <button type="button"
            class="fixed bottom-6 right-6 text-white bg-blue-700 hover:bg-blue-800 focus:ring-8 focus:outline-none focus:ring-blue-300 font-medium rounded-full text-sm p-4 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 shadow-lg">
            <svg class="w-8 h-8" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M1 5h12m0 0L9 1m4 4L9 9" />
            </svg>
            <span class="sr-only">Icon description</span>
        </button> --}}

        <!-- Floating Action Button -->
        <button id="sendBtn" type="button"
            class="fixed bottom-6 right-6 text-white bg-blue-700 hover:bg-blue-800 focus:ring-8 focus:outline-none focus:ring-blue-300 font-medium rounded-full text-sm p-4 text-center inline-flex items-center shadow-lg">
            <svg class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M1 5h12m0 0L9 1m4 4L9 9" />
            </svg>
        </button>

        <script>
            document.getElementById('sendBtn').addEventListener('click', async () => {
                const checked = Array.from(document.querySelectorAll('input[type="checkbox"]:checked'))
                    .map(cb => parseInt(cb.value));

                if (checked.length === 0) {
                    document.getElementById('alert-4').classList.remove('hidden');
                    return;
                }

                try {
                    console.log(checked);

                    const res = await fetch('http://192.168.1.133:8080/folders', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            id: checked
                        })
                    });

                    if (!res.ok) throw new Error('Gagal mengirim data');
                    alert('Data berhasil dikirim!');
                } catch (err) {
                    alert('Error bro: ' + err.message);
                }
            });
        </script>
    @endif

@endsection
{{-- <img src="{{ fix_thumbnail_url($folder['thumbnail']) }}" style="width:100px;"> --}}
{{-- <img src="{{ clean_thumbnail_url($folder['thumbnail']) }}" style="width:100px;"> --}}
