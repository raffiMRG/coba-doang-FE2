@extends('layouts.app')

@section('title', 'Backup')

@section('content')
  <div class="max-w-screen-md mx-auto p-4">
    <h1 class="mb-6 text-xl font-semibold text-gray-900 dark:text-white">Export / Import Database</h1>

    @if (session('success'))
      <div class="mb-4 p-3 text-sm text-green-800 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-900">
        {{ session('success') }}
      </div>
    @endif

    @if (session('error'))
      <div class="mb-4 p-3 text-sm text-red-800 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-900">
        {{ session('error') }}
      </div>
    @endif

    <div class="mb-8 p-4 bg-white rounded-lg shadow dark:bg-gray-900">
      <h2 class="mb-3 text-lg font-medium text-gray-900 dark:text-white">Export</h2>
      <p class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        "Full" bisa dipakai restore ke database kosong (termasuk struktur tabel). "Data only" cuma
        data, asumsi tabelnya sudah ada.
      </p>
      <div class="flex gap-3">
        <a href="{{ route('backup.export', ['mode' => 'full']) }}"
          class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700">
          Export (Full)
        </a>
        <a href="{{ route('backup.export', ['mode' => 'data']) }}"
          class="text-gray-900 bg-white border border-gray-300 hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700">
          Export (Data only)
        </a>
      </div>
    </div>

    <div class="p-4 bg-white rounded-lg shadow dark:bg-gray-900">
      <h2 class="mb-3 text-lg font-medium text-gray-900 dark:text-white">Import</h2>
      <p class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        Upload file .sql hasil export sebelumnya. Import "Full" akan menghapus &amp; membuat ulang semua tabel.
      </p>
      <form method="POST" action="{{ route('backup.import') }}" enctype="multipart/form-data" class="flex items-center gap-3">
        @csrf
        <input type="file" name="file" accept=".sql" required
          class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
        <button type="submit"
          class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 whitespace-nowrap">
          Import
        </button>
      </form>
    </div>
  </div>
@endsection
