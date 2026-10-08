@extends('layouts.app')

@section('title', 'Folder List')

@section('content')

    <h1 class="text-2xl font-bold text-white tracking-tight mb-6">Status</h1>

    @if ($error)
        <div class="flex items-center gap-3 p-4 rounded-lg bg-red-950/50 border border-red-900 text-red-300">
            {{ $error }}
        </div>
    @else
        <div id="alert-4"
            class="hidden fixed top-3 right-6 items-center p-4 mb-4 rounded-lg bg-amber-950/90 backdrop-blur border border-amber-900 text-amber-300 shadow-lg z-50"
            role="alert">
            <svg class="shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                viewBox="0 0 20 20">
                <path
                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
            </svg>
            <span class="sr-only">Info</span>
            <div id="alertText" class="ms-3 text-sm font-medium">
                Pilih minimal 1 sebelum melanjutkan.
            </div>
            <button type="button"
                class="ms-auto -mx-1.5 -my-1.5 bg-amber-950 text-amber-400 rounded-lg focus:ring-2 focus:ring-amber-700 p-1.5 hover:bg-amber-900 inline-flex items-center justify-center h-8 w-8"
                data-dismiss-target="#alert-4" aria-label="Close">
                <span class="sr-only">Close</span>
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
            </button>
        </div>

        {{-- Global progress box: shown on every device while anything is
        queued or processing (fed by the /status/events SSE stream). --}}
        <div id="progressBox"
            class="hidden fixed bottom-24 right-6 bg-gray-900 border border-gray-800 p-4 rounded-xl shadow-xl text-sm w-72 z-50">
            <div class="flex items-center justify-between mb-2 gap-2">
                <h4 id="progressTitle" class="font-semibold text-white truncate">Memproses folder</h4>
                <span id="progressPercent" class="text-indigo-400 font-semibold text-xs">0%</span>
            </div>
            <div class="w-full h-2 bg-gray-800 rounded-full overflow-hidden">
                <div id="progressBar" class="h-full bg-indigo-500 transition-all duration-300 ease-out"
                    style="width: 0%"></div>
            </div>
            <div id="progressStatus" class="mt-2 text-xs text-gray-400">Memulai...</div>
        </div>

        {{-- Everything inside #folderList is re-fetched from this same URL and
        reconciled in place by refill() below, so the card markup lives only
        here (server-rendered), never duplicated in JS. --}}
        <div id="folderList">
            @if (count($folders) === 0)
                <p class="text-gray-500 text-center py-16">Tidak ada folder untuk dipindahkan.</p>
            @else
                <ul id="folderGrid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                    @foreach ($folders as $folder)
                        <li data-id="{{ $folder['id'] }}">
                            <label
                                class="group relative block rounded-xl overflow-hidden bg-gray-900 ring-1 ring-white/10 cursor-pointer transition hover:-translate-y-1 hover:ring-indigo-500/60 has-checked:ring-2 has-checked:ring-indigo-500 has-disabled:cursor-default has-disabled:hover:translate-y-0">
                                <input type="checkbox" id="{{ $folder['id'] }}-option" value="{{ $folder['id'] }}"
                                    class="sr-only folder-checkbox">
                                <span
                                    class="status-badge hidden absolute top-2 left-2 z-10 px-2 py-0.5 rounded-md text-[10px] font-medium"></span>
                                <div class="aspect-3/4 w-full overflow-hidden bg-gray-800">
                                    <x-thumbnail :src="$folder['thumbnail']" :alt="$folder['name']"
                                        class="w-full h-full object-cover transition duration-300 group-hover:scale-105" />
                                </div>
                                <div
                                    class="absolute inset-x-0 bottom-0 bg-linear-to-t from-gray-950 via-gray-950/80 to-transparent px-3 pt-8 pb-3">
                                    <p class="text-sm font-semibold text-white leading-snug line-clamp-2">
                                        {{ $folder['name'] }}
                                    </p>
                                </div>
                                <div class="card-progress hidden absolute inset-x-0 bottom-0 h-1 bg-gray-800">
                                    <div class="h-full bg-indigo-500 transition-[width] duration-300" style="width: 0%">
                                    </div>
                                </div>
                                <div
                                    class="absolute top-2 right-2 w-6 h-6 rounded-full border-2 border-white/70 bg-gray-950/40 backdrop-blur flex items-center justify-center transition group-has-checked:bg-indigo-600 group-has-checked:border-indigo-500 group-has-disabled:hidden">
                                    <svg class="w-4 h-4 text-white opacity-0 transition group-has-checked:opacity-100"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M16.7 5.3a1 1 0 0 1 0 1.4l-8 8a1 1 0 0 1-1.4 0l-4-4a1 1 0 1 1 1.4-1.4L8 12.6l7.3-7.3a1 1 0 0 1 1.4 0Z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </label>
                        </li>
                    @endforeach
                </ul>

                <div id="paginationWrap">
                    <x-pagination :page="$page" :pages="$pages" :base-url="$baseUrl" />
                </div>
            @endif
        </div>

        <!-- Floating Action Button -->
        <button id="sendBtn" type="button"
            class="fixed bottom-6 right-6 text-white bg-indigo-600 hover:bg-indigo-500 focus:ring-4 focus:outline-none focus:ring-indigo-800 font-medium rounded-full text-sm p-4 text-center inline-flex items-center shadow-lg shadow-indigo-950/50 transition">
            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M1 5h12m0 0L9 1m4 4L9 9" />
            </svg>
        </button>

        <script>
            // Realtime /status (see zunks/feat/realtime-list-update.md):
            // - one global SSE stream (/status/events, proxied by nginx to the
            //   Go backend) tells every open tab which folders are queued /
            //   processing / done; it starts with a snapshot on every
            //   (re)connect, so a reload just picks up where things are.
            // - a finished folder's card animates out, the rest slide into the
            //   gap (FLIP), and refill() pulls the next ones up from the
            //   following page by re-fetching this same page's HTML.

            const BADGES = {
                queued: ['Antri', 'bg-gray-800/90 text-gray-300'],
                processing: ['Diproses', 'bg-indigo-950/90 text-indigo-300'],
                failed: ['Gagal', 'bg-red-950/90 text-red-300'],
            };
            const reduceMotion = matchMedia('(prefers-reduced-motion: reduce)').matches;
            // Browsers pause animations in hidden tabs (screen off, other tab),
            // so an awaited animation there would stall every update until the
            // tab is shown again — skip animating when nobody is watching.
            const skipAnimation = () => reduceMotion || document.hidden;

            const inflight = new Map(); // id -> {id, name, op, status, percent}
            const failed = new Map(); // id -> error message, until the folder is queued again

            const listEl = document.getElementById('folderList');
            const progressBox = document.getElementById('progressBox');

            function getXsrfToken() {
                return decodeURIComponent(document.cookie.match(/XSRF-TOKEN=([^;]+)/)?.[1] || '');
            }

            function showAlert(text) {
                const alertEl = document.getElementById('alert-4');
                document.getElementById('alertText').textContent = text;
                alertEl.classList.remove('hidden');
                alertEl.classList.add('flex');
                clearTimeout(showAlert.timer);
                showAlert.timer = setTimeout(() => {
                    alertEl.classList.add('hidden');
                    alertEl.classList.remove('flex');
                }, 5000);
            }

            function cardOf(id) {
                return listEl.querySelector(`li[data-id="${id}"]`);
            }

            // Paint one card from inflight/failed state.
            function paintCard(li) {
                const id = Number(li.dataset.id);
                const state = inflight.get(id);
                const status = state?.status ?? (failed.has(id) ? 'failed' : null);

                const badge = li.querySelector('.status-badge');
                const cb = li.querySelector('.folder-checkbox');
                const bar = li.querySelector('.card-progress');

                if (status) {
                    const [text, cls] = BADGES[status];
                    badge.textContent = state?.op === 'delete' && status !== 'failed' ? `${text} (hapus)` : text;
                    badge.className = `status-badge absolute top-2 left-2 z-10 px-2 py-0.5 rounded-md text-[10px] font-medium ${cls}`;
                    badge.title = status === 'failed' ? failed.get(id) : '';
                } else {
                    badge.className = 'status-badge hidden';
                }

                const busy = status === 'queued' || status === 'processing';
                if (busy) cb.checked = false;
                cb.disabled = busy;

                bar.classList.toggle('hidden', status !== 'processing');
                bar.firstElementChild.style.width = `${state?.percent ?? 0}%`;
            }

            function paintAll() {
                listEl.querySelectorAll('li[data-id]').forEach(paintCard);
                renderBox();
            }

            function renderBox() {
                const items = [...inflight.values()];
                if (items.length === 0) {
                    clearTimeout(renderBox.hideTimer);
                    if (!progressBox.classList.contains('hidden')) {
                        document.getElementById('progressTitle').textContent = 'Selesai';
                        document.getElementById('progressStatus').textContent = '✅ Semua antrian selesai';
                        document.getElementById('progressBar').style.width = '100%';
                        document.getElementById('progressPercent').textContent = '100%';
                        renderBox.hideTimer = setTimeout(() => progressBox.classList.add('hidden'), 3000);
                    }
                    return;
                }

                clearTimeout(renderBox.hideTimer);
                progressBox.classList.remove('hidden');
                const current = items.find(s => s.status === 'processing');
                const waiting = items.filter(s => s.status === 'queued').length;
                const pct = Math.round(current?.percent ?? 0);

                document.getElementById('progressTitle').textContent = current
                    ? `${current.op === 'delete' ? 'Menghapus' : 'Memindahkan'}: ${current.name}`
                    : 'Menunggu antrian...';
                document.getElementById('progressBar').style.width = `${pct}%`;
                document.getElementById('progressPercent').textContent = `${pct}%`;
                document.getElementById('progressStatus').textContent = `${waiting} folder lagi di antrian`;
            }

            // --- animation -------------------------------------------------

            // FLIP: record every card's position, run mutate(), then animate
            // each surviving card from its old spot to its new one — this is
            // what makes the following cards slide into a removed card's gap
            // (including wrapping to the previous row).
            function animateLayout(mutate) {
                const before = new Map();
                listEl.querySelectorAll('li[data-id]').forEach(li => before.set(li, li.getBoundingClientRect()));
                mutate();
                if (skipAnimation()) return;

                listEl.querySelectorAll('li[data-id]').forEach(li => {
                    const old = before.get(li);
                    if (!old) {
                        li.animate([{ opacity: 0, transform: 'translateY(8px)' }, { opacity: 1, transform: 'none' }],
                            { duration: 300, easing: 'ease-out' });
                        return;
                    }
                    const now = li.getBoundingClientRect();
                    const dx = old.left - now.left;
                    const dy = old.top - now.top;
                    if (dx || dy) {
                        li.animate([{ transform: `translate(${dx}px, ${dy}px)` }, { transform: 'none' }],
                            { duration: 300, easing: 'ease-in-out' });
                    }
                });
            }

            async function animateOut(li) {
                if (skipAnimation()) return;
                const anim = li.animate([{ opacity: 1, transform: 'scale(1)' }, { opacity: 0, transform: 'scale(.9)' }],
                    { duration: 200, easing: 'ease-in', fill: 'forwards' });
                // Safety net: never let one animation hold up the list (e.g. the
                // tab gets hidden mid-animation).
                await Promise.race([anim.finished, new Promise(r => setTimeout(r, 400))]).catch(() => {});
            }

            async function removeCard(id) {
                const li = cardOf(id);
                if (li) {
                    await animateOut(li);
                    animateLayout(() => li.remove());
                }
                // Even when the card isn't on this page, an earlier page may
                // have shrunk, so this page's contents shift too.
                scheduleRefill();
            }

            // --- refill: reconcile with the server's current page ----------

            function scheduleRefill(delay = 400) {
                clearTimeout(scheduleRefill.timer);
                scheduleRefill.timer = setTimeout(refill, delay);
            }

            async function refill() {
                if (refill.running) {
                    refill.again = true;
                    return;
                }
                refill.running = true;
                try {
                    const res = await fetch(location.href, { headers: { 'Accept': 'text/html' }, cache: 'no-store' });
                    if (!res.ok) return;
                    const doc = new DOMParser().parseFromString(await res.text(), 'text/html');
                    const fresh = doc.getElementById('folderList');
                    if (!fresh) return; // e.g. redirected to /login

                    const freshGrid = fresh.querySelector('#folderGrid');
                    const page = Number(new URL(location.href).searchParams.get('page') || 1);

                    // This page ran out (its items moved up to earlier pages):
                    // go to the previous one rather than show an empty page.
                    if (!freshGrid && page > 1) {
                        const url = new URL(location.href);
                        url.searchParams.set('page', page - 1);
                        location.replace(url);
                        return;
                    }

                    const grid = listEl.querySelector('#folderGrid');
                    if (!grid || !freshGrid) {
                        listEl.replaceChildren(...fresh.childNodes); // list became empty, or stopped being empty
                        paintAll();
                        return;
                    }

                    const freshIds = new Set([...freshGrid.children].map(li => li.dataset.id));
                    const leaving = [...grid.children].filter(li => !freshIds.has(li.dataset.id));
                    await Promise.all(leaving.map(animateOut));

                    animateLayout(() => {
                        leaving.forEach(li => li.remove());
                        // Walk the server's order: keep cards already shown
                        // (preserving checkbox selection), import new ones.
                        let prev = null;
                        for (const freshLi of [...freshGrid.children]) {
                            const li = cardOf(freshLi.dataset.id) || document.importNode(freshLi, true);
                            if (li.previousElementSibling !== prev || li.parentNode !== grid) {
                                prev ? prev.after(li) : grid.prepend(li);
                            }
                            prev = li;
                        }
                    });

                    listEl.querySelector('#paginationWrap')
                        ?.replaceWith(document.importNode(fresh.querySelector('#paginationWrap'), true));
                    paintAll();
                } catch (err) {
                    console.warn('refill failed', err);
                } finally {
                    refill.running = false;
                    if (refill.again) {
                        refill.again = false;
                        scheduleRefill(0);
                    }
                }
            }

            // --- SSE ---------------------------------------------------------

            function connect() {
                const es = new EventSource('/status/events');

                es.addEventListener('snapshot', (e) => {
                    inflight.clear();
                    for (const s of JSON.parse(e.data).items) inflight.set(s.id, s);
                    paintAll();
                    // Anything that finished while we were disconnected (or
                    // before a reload) is reconciled here.
                    scheduleRefill(0);
                });

                es.addEventListener('item', (e) => {
                    const evt = JSON.parse(e.data);
                    switch (evt.status) {
                        case 'queued':
                        case 'processing':
                            failed.delete(evt.id);
                            inflight.set(evt.id, { ...inflight.get(evt.id), ...evt, percent: inflight.get(evt.id)?.percent ?? 0 });
                            break;
                        case 'failed':
                            inflight.delete(evt.id);
                            failed.set(evt.id, evt.error || 'Gagal');
                            break;
                        default: // moved / deleted
                            inflight.delete(evt.id);
                            removeCard(evt.id);
                    }
                    const li = cardOf(evt.id);
                    if (li) paintCard(li);
                    renderBox();
                });

                es.addEventListener('progress', (e) => {
                    const evt = JSON.parse(e.data);
                    const state = inflight.get(evt.id);
                    if (!state) return;
                    state.percent = evt.percent;
                    const li = cardOf(evt.id);
                    if (li) paintCard(li);
                    renderBox();
                });

                es.addEventListener('changed', () => scheduleRefill());

                es.addEventListener('error', () => {
                    // While CONNECTING the browser retries by itself (retry:
                    // 3000 from the server). It gives up for good on a non-200
                    // response (backend down → 502, logged out → 500 from
                    // auth_request), so retry those ourselves.
                    if (es.readyState === EventSource.CLOSED) {
                        setTimeout(connect, 5000);
                    }
                });
            }

            // --- send --------------------------------------------------------

            document.getElementById('sendBtn').addEventListener('click', async () => {
                const checked = Array.from(listEl.querySelectorAll('.folder-checkbox:checked'))
                    .map(cb => parseInt(cb.value));

                if (checked.length === 0) {
                    showAlert('Pilih minimal 1 sebelum melanjutkan.');
                    return;
                }

                try {
                    // Through Laravel (not straight to the Go backend): the
                    // browser never sees the access_token kept in the session.
                    const res = await fetch('/status/move', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-XSRF-TOKEN': getXsrfToken(),
                        },
                        body: JSON.stringify({ Id: checked }),
                    });
                    const result = await res.json().catch(() => ({}));

                    // 409 = every selected folder was already taken (e.g.
                    // another user sent them a moment earlier).
                    if (!res.ok && res.status !== 409) {
                        throw new Error(result.Message || 'Gagal memulai proses pemindahan');
                    }

                    // The "Antri" badges arrive through the SSE stream (same
                    // path for every device), not set locally here.
                    const skipped = result?.Data?.skipped ?? [];
                    if (skipped.length > 0) {
                        const busy = skipped.filter(s => s.reason === 'sedang diproses').length;
                        showAlert(busy === skipped.length
                            ? `${busy} folder dilewati karena sedang diproses.`
                            : `${skipped.length} folder dilewati (${skipped.map(s => s.reason).join(', ')}).`);
                    }
                } catch (err) {
                    showAlert('Error: ' + err.message);
                }
            });

            // A tab that was hidden skipped its animations; resync on return.
            document.addEventListener('visibilitychange', () => {
                if (!document.hidden) scheduleRefill(0);
            });

            connect();
        </script>
    @endif

@endsection
