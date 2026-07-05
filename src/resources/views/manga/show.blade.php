<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $manga['name'] }}</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-950 text-gray-100 antialiased">
    <x-navbar></x-navbar>

    <div class="bg-gray-950/90 backdrop-blur border-b border-gray-800">
        <div class="max-w-3xl mx-auto px-4 py-3 flex items-center justify-between gap-4">
            <a href="javascript:history.back()" class="flex items-center gap-1 text-gray-400 hover:text-white transition text-sm shrink-0">
                <svg class="w-4 h-4" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12.5 15 7.5 10l5-5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                Back
            </a>
            <h1 id="mangaTitle" class="text-sm font-semibold text-white truncate">{{ $manga['name'] }}</h1>
            <div class="flex items-center gap-2 shrink-0">
                <span class="text-xs text-gray-500">{{ count($manga['page']) }} pages</span>
                <button id="editBtn" type="button" title="Edit"
                    class="p-1.5 rounded-lg text-gray-400 hover:text-white hover:bg-gray-800 transition">
                    <svg class="w-4 h-4" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M13.5 3.5 16.5 6.5M12 4.5 3 13.5V17h3.5L15.5 8 12 4.5Z" stroke="currentColor"
                            stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
                <button id="deleteBtn" type="button" title="Delete"
                    class="p-1.5 rounded-lg text-gray-400 hover:text-red-400 hover:bg-gray-800 transition">
                    <svg class="w-4 h-4" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 6h12M8 6V4.5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1V6m-7 0 .7 9.1a1.5 1.5 0 0 0 1.5 1.4h5.6a1.5 1.5 0 0 0 1.5-1.4L15 6"
                            stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div class="max-w-3xl mx-auto flex flex-col">
        @foreach ($manga['page'] as $page)
            {{-- <img src="{{ same_origin_url(rtrim(dirname($manga['thumbnail']), '/') . '/' . $page) }}" alt="Page {{ $loop->iteration }}" class="w-full h-auto" loading="lazy"> --}}
            <img src="https://i.imgur.com/TNOs1Xx.png" alt="Page {{ $loop->iteration }}" class="w-full h-auto" loading="lazy">
        @endforeach
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
        <div class="bg-gray-900 rounded-xl ring-1 ring-white/10 w-full max-w-sm p-5">
            <h2 class="text-lg font-semibold text-white mb-4">Edit Nama</h2>
            <input type="text" id="editNameInput" value="{{ $manga['name'] }}"
                class="w-full mb-4 p-2.5 text-sm text-gray-100 border border-gray-700 rounded-lg bg-gray-800 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
            <label class="flex items-center gap-2 mb-5 text-sm text-gray-300">
                <input type="checkbox" id="editApplyDisk"
                    class="rounded bg-gray-800 border-gray-700 text-indigo-600 focus:ring-indigo-500">
                Terapkan juga pada file asli
            </label>
            <div class="flex justify-end gap-3">
                <button type="button" id="editCancelBtn"
                    class="text-gray-300 bg-gray-800 hover:bg-gray-700 font-medium rounded-lg text-sm px-4 py-2 transition">Batal</button>
                <button type="button" id="editConfirmBtn"
                    class="text-white bg-indigo-600 hover:bg-indigo-500 font-medium rounded-lg text-sm px-4 py-2 transition">Simpan</button>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="deleteModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
        <div class="bg-gray-900 rounded-xl ring-1 ring-white/10 w-full max-w-sm p-5">
            <h2 class="text-lg font-semibold text-white mb-2">Hapus Manga?</h2>
            <p class="text-sm text-gray-400 mb-4">Tindakan ini akan menghapus data manga ini dari katalog.</p>
            <label class="flex items-center gap-2 mb-5 text-sm text-gray-300">
                <input type="checkbox" id="deleteApplyDisk"
                    class="rounded bg-gray-800 border-gray-700 text-red-600 focus:ring-red-500">
                Terapkan juga pada file asli (hapus permanen dari disk)
            </label>
            <div class="flex justify-end gap-3">
                <button type="button" id="deleteCancelBtn"
                    class="text-gray-300 bg-gray-800 hover:bg-gray-700 font-medium rounded-lg text-sm px-4 py-2 transition">Batal</button>
                <button type="button" id="deleteConfirmBtn"
                    class="text-white bg-red-600 hover:bg-red-500 font-medium rounded-lg text-sm px-4 py-2 transition">Hapus</button>
            </div>
        </div>
    </div>

    @include('components.footer')
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    <script>
        const mangaId = {{ $manga['id'] }};

        function getXsrfToken() {
            return decodeURIComponent(document.cookie.match(/XSRF-TOKEN=([^;]+)/)?.[1] || '');
        }

        const editModal = document.getElementById('editModal');
        document.getElementById('editBtn').addEventListener('click', () => editModal.classList.remove('hidden'));
        document.getElementById('editCancelBtn').addEventListener('click', () => editModal.classList.add('hidden'));
        document.getElementById('editConfirmBtn').addEventListener('click', async () => {
            const newName = document.getElementById('editNameInput').value.trim();
            const applyToDisk = document.getElementById('editApplyDisk').checked;
            if (!newName) return;

            try {
                const res = await fetch(`/id/${mangaId}`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-XSRF-TOKEN': getXsrfToken()
                    },
                    body: JSON.stringify({ new_name: newName, apply_to_disk: applyToDisk })
                });
                const result = await res.json();
                if (!res.ok) throw new Error(result.Message || 'Gagal mengubah nama');

                document.getElementById('mangaTitle').textContent = result.Data.name;
                document.title = result.Data.name;
                editModal.classList.add('hidden');
            } catch (err) {
                alert('Error: ' + err.message);
            }
        });

        const deleteModal = document.getElementById('deleteModal');
        document.getElementById('deleteBtn').addEventListener('click', () => deleteModal.classList.remove('hidden'));
        document.getElementById('deleteCancelBtn').addEventListener('click', () => deleteModal.classList.add('hidden'));
        document.getElementById('deleteConfirmBtn').addEventListener('click', async () => {
            const applyToDisk = document.getElementById('deleteApplyDisk').checked;

            try {
                const res = await fetch(`/id/${mangaId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-XSRF-TOKEN': getXsrfToken()
                    },
                    body: JSON.stringify({ apply_to_disk: applyToDisk })
                });
                const result = await res.json();
                if (!res.ok) throw new Error(result.Message || 'Gagal menghapus');

                window.location.href = '/home';
            } catch (err) {
                alert('Error: ' + err.message);
            }
        });
    </script>
</body>

</html>
