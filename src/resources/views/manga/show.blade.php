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
        <div class="w-full sm:w-[80vw] mx-auto px-4 py-3 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-4">
            <h1 id="mangaTitle" class="min-w-0 sm:flex-1 truncate text-sm font-semibold text-white">{{ $manga['name'] }}</h1>
            <div class="flex items-center gap-1.5 shrink-0">
                <span class="text-xs text-gray-500 mr-1">{{ count($manga['page']) }} pages</span>

                @php
                    $translateBadges = [
                        'none' => ['Belum diminta', 'bg-gray-800 text-gray-400 border border-gray-700'],
                        'pending' => ['Menunggu diterjemahkan', 'bg-yellow-950/50 text-yellow-300 border border-yellow-900'],
                        'processing' => ['Menunggu diterjemahkan', 'bg-yellow-950/50 text-yellow-300 border border-yellow-900'],
                        'completed' => ['Sudah diterjemahkan', 'bg-green-950/50 text-green-300 border border-green-900'],
                        'failed' => ['Gagal diterjemahkan', 'bg-red-950/50 text-red-300 border border-red-900'],
                    ];
                    [$translateBadgeText, $translateBadgeClass] = $translateBadges[$manga['translation_status'] ?? 'none'] ?? $translateBadges['none'];
                @endphp
                <span id="translateBadge" class="hidden sm:inline-block px-3 py-1 rounded-full text-xs font-medium whitespace-nowrap {{ $translateBadgeClass }}">
                    {{ $translateBadgeText }}
                </span>

                <button id="requestTranslateBtn" type="button" title="Request Translate"
                    class="p-1.5 rounded-lg text-gray-400 hover:text-white hover:bg-gray-800 transition">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12.913 17H20.087M12.913 17L11 21M12.913 17L15.7783 11.009C16.0092 10.5263 16.1246 10.2849 16.2826 10.2086C16.4199 10.1423 16.5801 10.1423 16.7174 10.2086C16.8754 10.2849 16.9908 10.5263 17.2217 11.009L20.087 17M20.087 17L22 21M2 5H8M8 5H11.5M8 5V3M11.5 5H14M11.5 5C11.0039 7.95729 9.85259 10.6362 8.16555 12.8844M10 14C9.38747 13.7248 8.76265 13.3421 8.16555 12.8844M8.16555 12.8844C6.81302 11.8478 5.60276 10.4266 5 9M8.16555 12.8844C6.56086 15.0229 4.47143 16.7718 2 18"
                            stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>

                <button id="reportBugBtn" type="button" title="Report Bug"
                    class="p-1.5 rounded-lg text-gray-400 hover:text-red-400 hover:bg-gray-800 transition">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 9v3.5M12 16h.01M5 8l1.5-2M19 8l-1.5-2M9 3.5c.6-.6 1.7-1 3-1s2.4.4 3 1M5.5 12H3M21 12h-2.5M5.5 16.5 4 18M18.5 16.5 20 18M8 9h8a3 3 0 0 1 3 3v2a5 5 0 0 1-5 5h-4a5 5 0 0 1-5-5v-2a3 3 0 0 1 3-3Z"
                            stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>

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
            <div class="aspect-[2/3] w-full overflow-hidden bg-gray-950">
                <x-thumbnail
                    :src="$manga['thumbnail'] ? rtrim(dirname($manga['thumbnail']), '/') . '/' . $page : null"
                    :alt="'Page ' . $loop->iteration"
                    class="w-full h-full object-contain"
                    loading="lazy" />
            </div>
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

    <!-- Report Bug Modal -->
    <div id="reportBugModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
        <div class="bg-gray-900 rounded-xl ring-1 ring-white/10 w-full max-w-sm p-5">
            <h2 class="text-lg font-semibold text-white mb-2">Report Bug</h2>
            <p class="text-sm text-gray-400 mb-4">Ceritakan bug atau gambar yang rusak pada manga ini (mis. nomor halaman, gambar blank/pecah, dll).</p>
            <textarea id="reportBugInput" rows="4" placeholder="Deskripsi bug..."
                class="w-full mb-4 p-2.5 text-sm text-gray-100 border border-gray-700 rounded-lg bg-gray-800 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none resize-none"></textarea>
            <div class="flex justify-end gap-3">
                <button type="button" id="reportBugCancelBtn"
                    class="text-gray-300 bg-gray-800 hover:bg-gray-700 font-medium rounded-lg text-sm px-4 py-2 transition">Batal</button>
                <button type="button" id="reportBugConfirmBtn"
                    class="text-white bg-indigo-600 hover:bg-indigo-500 font-medium rounded-lg text-sm px-4 py-2 transition">Kirim</button>
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

        const translateBadgeStyles = {
            none: ['Belum diminta', 'bg-gray-800 text-gray-400 border border-gray-700'],
            pending: ['Menunggu diterjemahkan', 'bg-yellow-950/50 text-yellow-300 border border-yellow-900'],
            processing: ['Menunggu diterjemahkan', 'bg-yellow-950/50 text-yellow-300 border border-yellow-900'],
            completed: ['Sudah diterjemahkan', 'bg-green-950/50 text-green-300 border border-green-900'],
            failed: ['Gagal diterjemahkan', 'bg-red-950/50 text-red-300 border border-red-900'],
        };

        function setTranslateBadge(status) {
            const [text, classes] = translateBadgeStyles[status] || translateBadgeStyles.none;
            const badge = document.getElementById('translateBadge');
            badge.textContent = text;
            badge.className = `px-3 py-1 rounded-full text-xs font-medium ${classes}`;
        }

        document.getElementById('requestTranslateBtn').addEventListener('click', async () => {
            try {
                const res = await fetch(`/translate/${mangaId}/request`, {
                    method: 'POST',
                    headers: { 'X-XSRF-TOKEN': getXsrfToken() },
                });
                const result = await res.json();
                if (!res.ok) throw new Error(result.Message || 'Gagal meminta translate');

                setTranslateBadge(result.Data.status);
            } catch (err) {
                alert('Error: ' + err.message);
            }
        });

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

        const reportBugModal = document.getElementById('reportBugModal');
        const reportBugInput = document.getElementById('reportBugInput');
        document.getElementById('reportBugBtn').addEventListener('click', () => reportBugModal.classList.remove('hidden'));
        document.getElementById('reportBugCancelBtn').addEventListener('click', () => reportBugModal.classList.add('hidden'));
        document.getElementById('reportBugConfirmBtn').addEventListener('click', async () => {
            const description = reportBugInput.value.trim();
            if (!description) return;

            try {
                const res = await fetch('/bug-reports', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-XSRF-TOKEN': getXsrfToken()
                    },
                    body: JSON.stringify({ folder_id: mangaId, description })
                });
                const result = await res.json();
                if (!res.ok) throw new Error(result.Message || 'Gagal mengirim laporan');

                reportBugInput.value = '';
                reportBugModal.classList.add('hidden');
                alert('Laporan terkirim, terima kasih!');
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
