@extends('layouts.app')

@section('title', 'Folder List')

@section('content')

    {{-- <h1 class="">Folder List</h1> --}}

    @if ($error)
        <p style="color: red;">{{ $error }}</p>
    @else
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

        <!-- Progress Box (hidden by default) -->
        <div id="progressBox" class="fixed bottom-24 right-6 bg-white p-3 rounded shadow-md text-sm w-64 z-50">
            <h4 class="font-semibold mb-2">Progress:</h4>
            <div id="progressLogs" class="max-h-60 overflow-y-auto text-gray-700"></div>
        </div>

        <!-- Folder List with Checkboxes -->

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

        <x-pagination :page="$page" :pages="$pages" :base-url="$baseUrl" />

        <!-- Floating Action Button -->
        <button id="sendBtn" type="button"
            class="fixed bottom-6 right-6 text-white bg-blue-700 hover:bg-blue-800 focus:ring-8 focus:outline-none focus:ring-blue-300 font-medium rounded-full text-sm p-4 text-center inline-flex items-center shadow-lg">
            <svg class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M1 5h12m0 0L9 1m4 4L9 9" />
            </svg>
        </button>

        {{-- <script>
            document.getElementById('sendBtn').addEventListener('click', async () => {
                const checked = Array.from(document.querySelectorAll('input[type="checkbox"]:checked'))
                    .map(cb => parseInt(cb.value));

                if (checked.length === 0) {
                    document.getElementById('alert-4').classList.remove('hidden');
                    return;
                }

                try {
                    console.log(checked);
                    const API_URL = "{{ config('app.api_url') }}";

                    const res = await fetch(API_URL + '/folders', {
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
        </script> --}}
        <script>
            document.getElementById('sendBtn').addEventListener('click', async () => {
                const checked = Array.from(document.querySelectorAll('input[type="checkbox"]:checked'))
                    .map(cb => parseInt(cb.value));

                if (checked.length === 0) {
                    document.getElementById('alert-4').classList.remove('hidden');
                    return;
                }

                const API_URL = "{{ config('app.api_url') }}";
                console.log('API URL:', API_URL);
                // const progressBox = document.getElementById('progressBox') || createProgressBox();
                // const progressBox = createProgressBox();
                const progressBox = document.getElementById('progressBox');

                try {
                    console.log('Mengirim data:', checked);

                    // 1️⃣ Kirim perintah untuk mulai proses pemindahan
                    const res = await fetch(`${API_URL}/folders`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            Id: checked
                        })
                    });

                    if (!res.ok) throw new Error('Gagal memulai proses pemindahan');

                    const result = await res.json();
                    console.log('Hasilnya:', result);
                    const taskID = result?.Data?.task_id; // ambil taskID dari respons backend

                    console.log('Task ID:', taskID);

                    // if (!taskID) throw new Error('Task ID tidak ditemukan di respons backend');

                    if (!taskID) {
                        log('Task ID tidak ditemukan di respons backend');
                        return;
                    }
                    // 2️⃣ Mulai dengarkan SSE untuk progres dengan taskID
                    listenProgress(`${API_URL}/folders/progress/${taskID}`, progressBox);

                } catch (err) {
                    alert('Error: ' + err.message);
                }
            });

            /**
             * Fungsi untuk membuka koneksi SSE dan menampilkan log progres.
             */
            function listenProgress(url, progressBox) {
                const progressLogs = progressBox.querySelector('#progressLogs');
                progressBox.classList.remove('hidden');
                progressLogs.innerHTML = '';

                const evtSource = new EventSource(url);

                evtSource.addEventListener('progress', (e) => {
                    console.log('Progress event:', e.data);
                    progressLogs.innerHTML += `<div>📦 ${e.data}</div>`;
                    progressLogs.scrollTop = progressLogs.scrollHeight;
                });

                evtSource.addEventListener('done', (e) => {
                    progressLogs.innerHTML += `<div class="text-green-600 font-semibold mt-2">✅ ${e.data}</div>`;
                    progressLogs.scrollTop = progressLogs.scrollHeight;
                    evtSource.close();
                });

                evtSource.addEventListener('error', (e) => {
                    console.log('e', e);

                    progressLogs.innerHTML += `<div class="text-red-500 mt-2">⚠️ Terjadi kesalahan koneksi SSE.</div>`;
                    progressLogs.scrollTop = progressLogs.scrollHeight;
                    evtSource.close();
                });
            }

            /**
             * Fungsi untuk membuat UI progres jika belum ada.
             */
            function createProgressBox() {
                const box = document.createElement('div');
                box.id = 'progressBox';
                box.className = 'fixed bottom-24 right-6 bg-white p-3 rounded shadow-md text-sm w-64 hidden z-50';
                box.innerHTML = `
        <h4 class="font-semibold mb-2">Progress:</h4>
        <div id="progressLogs" class="max-h-60 overflow-y-auto text-gray-700"></div>
    `;
                document.body.appendChild(box);
                return box;
            }
        </script>


    @endif

@endsection
