@extends('layouts.app')

@section('title', 'Backup')

@section('content')
  <div class="max-w-screen-md mx-auto">
    <h1 class="mb-6 text-2xl font-bold text-white tracking-tight">Export / Import Database</h1>

    @if (session('success'))
      <div class="mb-4 p-3 text-sm rounded-lg bg-green-950/50 border border-green-900 text-green-300">
        {{ session('success') }}
      </div>
    @endif

    @if (session('error'))
      <div class="mb-4 p-3 text-sm rounded-lg bg-red-950/50 border border-red-900 text-red-300">
        {{ session('error') }}
      </div>
    @endif

    <div class="mb-6 p-5 bg-gray-900 rounded-xl ring-1 ring-white/10">
      <h2 class="mb-3 text-lg font-semibold text-white">Export</h2>
      <p class="mb-4 text-sm text-gray-400">
        "Full" bisa dipakai restore ke database kosong (termasuk struktur tabel). "Data only" cuma
        data, asumsi tabelnya sudah ada.
      </p>
      <div class="flex gap-3">
        <a href="{{ route('backup.export', ['mode' => 'full']) }}"
          class="text-white bg-indigo-600 hover:bg-indigo-500 focus:ring-4 focus:ring-indigo-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center transition">
          Export (Full)
        </a>
        <a href="{{ route('backup.export', ['mode' => 'data']) }}"
          class="text-gray-200 bg-gray-800 border border-gray-700 hover:bg-gray-700 focus:ring-4 focus:ring-gray-700 font-medium rounded-lg text-sm px-5 py-2.5 text-center transition">
          Export (Data only)
        </a>
      </div>
    </div>

    <div class="mb-6 p-5 bg-gray-900 rounded-xl ring-1 ring-white/10">
      <h2 class="mb-3 text-lg font-semibold text-white">Export Folder List (DST_DIR)</h2>
      <p class="mb-4 text-sm text-gray-400">
        Scan folder yang ada di DST_DIR (folder "done" di home server) dan export nama-nama
        foldernya sebagai file .json (terurut alfabet) — untuk diproses lebih lanjut di laptop lain.
      </p>
      <a href="{{ route('backup.exportDstFolders') }}"
        class="text-white bg-indigo-600 hover:bg-indigo-500 focus:ring-4 focus:ring-indigo-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center transition inline-block">
        Export Folder List
      </a>
    </div>

    <div class="p-5 bg-gray-900 rounded-xl ring-1 ring-white/10">
      <h2 class="mb-3 text-lg font-semibold text-white">Import</h2>
      <p class="mb-4 text-sm text-gray-400">
        Upload file .sql hasil export sebelumnya. Import "Full" akan menghapus &amp; membuat ulang semua tabel.
      </p>
      <form method="POST" action="{{ route('backup.import') }}" enctype="multipart/form-data" class="flex items-center gap-3">
        @csrf
        <input type="file" name="file" accept=".sql" required
          class="block w-full text-sm text-gray-300 border border-gray-700 rounded-lg cursor-pointer bg-gray-800 focus:outline-none file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-gray-700 file:text-gray-200 file:text-sm">
        <button type="submit"
          class="text-white bg-indigo-600 hover:bg-indigo-500 focus:ring-4 focus:ring-indigo-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center transition whitespace-nowrap">
          Import
        </button>
      </form>
    </div>
  </div>
@endsection
