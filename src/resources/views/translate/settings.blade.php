@extends('layouts.app')

@section('title', 'Setting Translate')

@php
  // Must match the allow-lists in cobaDoangBackend
  // Repository/TranslateRepositorys/SettingsRepository.go.
  $translators = [
    'Offline (gratis, lokal)' => [
      'offline' => 'offline — otomatis (Sugoi untuk JP, m2m100_big lainnya)',
      'sugoi' => 'sugoi — JP→EN',
      'm2m100' => 'm2m100',
      'm2m100_big' => 'm2m100_big (besar)',
      'nllb' => 'nllb',
      'nllb_big' => 'nllb_big (besar)',
      'jparacrawl' => 'jparacrawl',
      'jparacrawl_big' => 'jparacrawl_big (besar)',
      'mbart50' => 'mbart50',
      'qwen2' => 'qwen2',
      'qwen2_big' => 'qwen2_big (besar)',
    ],
    'API (berbayar, butuh key)' => [
      'chatgpt' => 'chatgpt — OpenAI',
      'custom_openai' => 'custom_openai — Anthropic / Ollama / OpenAI-compatible',
      'deepseek' => 'deepseek',
      'gemini' => 'gemini',
    ],
    'Lainnya' => ['none' => 'none — tanpa translate'],
  ];
  $targetLangs = ['ENG', 'IND', 'JPN', 'CHS', 'CHT', 'KOR'];
  $detectors = ['default', 'dbconvnext', 'ctd', 'craft', 'paddle'];
  $ocrs = ['32px', '48px', '48px_ctc', 'mocr'];
  $inpainters = ['default', 'lama_large', 'lama_mpe', 'none', 'original'];
  $precisions = ['bf16', 'fp16', 'fp32'];
  $gpuModes = [
    'limited' => 'limited — GPU, translator offline di CPU (aman untuk VGA 4GB)',
    'full' => 'full — semua di GPU (butuh VRAM besar kalau translator offline)',
    'cpu' => 'cpu — tanpa GPU (lambat)',
  ];
  $s = $settings ?? [];
  $val = fn ($key) => old($key, $s[$key] ?? '');
  $input = 'block w-full rounded-lg bg-gray-800 border border-gray-700 text-gray-100 text-sm px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500';
  $label = 'block mb-1 text-sm font-medium text-gray-300';
@endphp

@section('content')
  <div class="max-w-screen-md mx-auto">
    <div class="mb-6 flex items-center justify-between">
      <h1 class="text-2xl font-bold text-white tracking-tight">
        Setting Translate
        @if ($s['is_default'] ?? false)
          <span class="ml-2 align-middle px-2 py-0.5 rounded-full text-xs font-medium bg-gray-800 text-gray-400 border border-gray-700">Default</span>
        @endif
      </h1>
      <a href="{{ route('translate') }}" class="text-sm text-indigo-400 hover:underline">&larr; Kembali</a>
    </div>

    @if (session('success'))
      <div class="mb-4 p-3 text-sm rounded-lg bg-green-950/50 border border-green-900 text-green-300">{{ session('success') }}</div>
    @endif
    @if (session('error'))
      <div class="mb-4 p-3 text-sm rounded-lg bg-red-950/50 border border-red-900 text-red-300">{{ session('error') }}</div>
    @endif

    @if ($error)
      <div class="p-4 rounded-lg bg-red-950/50 border border-red-900 text-red-300">{{ $error }}</div>
    @else
      <form method="POST" action="{{ route('translate.settings.save') }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="p-5 bg-gray-900 rounded-xl ring-1 ring-white/10">
          <h2 class="mb-4 text-lg font-semibold text-white">Translator</h2>
          <div class="grid sm:grid-cols-3 gap-4">
            <div class="sm:col-span-2">
              <label for="translator" class="{{ $label }}">Translator</label>
              <select id="translator" name="translator" class="{{ $input }}">
                @foreach ($translators as $group => $options)
                  <optgroup label="{{ $group }}">
                    @foreach ($options as $value => $text)
                      <option value="{{ $value }}" @selected($val('translator') === $value)>{{ $text }}</option>
                    @endforeach
                  </optgroup>
                @endforeach
              </select>
            </div>
            <div>
              <label for="target_lang" class="{{ $label }}">Bahasa tujuan</label>
              <select id="target_lang" name="target_lang" class="{{ $input }}">
                @foreach ($targetLangs as $value)
                  <option value="{{ $value }}" @selected($val('target_lang') === $value)>{{ $value }}</option>
                @endforeach
              </select>
            </div>
          </div>
        </div>

        <div class="p-5 bg-gray-900 rounded-xl ring-1 ring-white/10">
          <h2 class="mb-1 text-lg font-semibold text-white">API LLM</h2>
          <p class="mb-4 text-sm text-gray-400">Hanya dipakai kalau translator = chatgpt / custom_openai / deepseek / gemini.</p>
          <div class="mb-4 flex flex-wrap gap-2">
            <span class="text-sm text-gray-400 self-center">Preset:</span>
            <button type="button" class="preset-btn px-3 py-1.5 rounded-lg text-xs bg-gray-800 border border-gray-700 text-gray-200 hover:bg-gray-700"
              data-translator="chatgpt" data-base="" data-model="gpt-4o-mini">OpenAI</button>
            <button type="button" class="preset-btn px-3 py-1.5 rounded-lg text-xs bg-gray-800 border border-gray-700 text-gray-200 hover:bg-gray-700"
              data-translator="custom_openai" data-base="https://api.anthropic.com/v1/" data-model="claude-haiku-4-5">Anthropic</button>
            <button type="button" class="preset-btn px-3 py-1.5 rounded-lg text-xs bg-gray-800 border border-gray-700 text-gray-200 hover:bg-gray-700"
              data-translator="custom_openai" data-base="http://localhost:11434/v1" data-model="qwen2.5:7b">Ollama</button>
          </div>
          <div class="grid sm:grid-cols-2 gap-4">
            <div>
              <label for="api_base" class="{{ $label }}">Base URL <span class="text-gray-500">(kosong = default provider)</span></label>
              <input id="api_base" name="api_base" type="url" value="{{ $val('api_base') }}" class="{{ $input }}">
            </div>
            <div>
              <label for="api_model" class="{{ $label }}">Model</label>
              <input id="api_model" name="api_model" type="text" value="{{ $val('api_model') }}" class="{{ $input }}">
            </div>
            <div class="sm:col-span-2">
              <label for="api_key" class="{{ $label }}">API key</label>
              <input id="api_key" name="api_key" type="password" autocomplete="off" class="{{ $input }}"
                placeholder="{{ ($s['has_api_key'] ?? false) ? '•••••••• tersimpan — kosongkan untuk tetap pakai key lama' : 'belum ada key' }}">
              @if ($s['has_api_key'] ?? false)
                <label class="mt-2 inline-flex items-center gap-2 text-sm text-gray-400">
                  <input type="checkbox" name="clear_api_key" value="1" class="rounded bg-gray-800 border-gray-700"> Hapus key tersimpan
                </label>
              @endif
            </div>
          </div>
        </div>

        <div class="p-5 bg-gray-900 rounded-xl ring-1 ring-white/10">
          <h2 class="mb-1 text-lg font-semibold text-white">Pipeline lokal</h2>
          <p class="mb-4 text-sm text-gray-400">
            VGA 4GB: pakai mode GPU <span class="font-mono">limited</span>. Mode <span class="font-mono">full</span> + translator offline besar
            (m2m100_big / nllb_big) akan OOM. Translator API tidak memakai VRAM. Kalau masih OOM, turunkan inpainting size ke 1024.
          </p>
          <div class="grid sm:grid-cols-3 gap-4">
            @foreach (['detector' => $detectors, 'ocr' => $ocrs, 'inpainter' => $inpainters] as $field => $options)
              <div>
                <label for="{{ $field }}" class="{{ $label }}">{{ ucfirst($field) }}</label>
                <select id="{{ $field }}" name="{{ $field }}" class="{{ $input }}">
                  @foreach ($options as $value)
                    <option value="{{ $value }}" @selected($val($field) === $value)>{{ $value }}</option>
                  @endforeach
                </select>
              </div>
            @endforeach
            <div>
              <label for="detection_size" class="{{ $label }}">Detection size</label>
              <input id="detection_size" name="detection_size" type="number" min="512" max="4096" step="64" value="{{ $val('detection_size') }}" class="{{ $input }}">
            </div>
            <div>
              <label for="inpainting_size" class="{{ $label }}">Inpainting size</label>
              <input id="inpainting_size" name="inpainting_size" type="number" min="512" max="4096" step="64" value="{{ $val('inpainting_size') }}" class="{{ $input }}">
            </div>
            <div>
              <label for="inpainting_precision" class="{{ $label }}">Inpainting precision</label>
              <select id="inpainting_precision" name="inpainting_precision" class="{{ $input }}">
                @foreach ($precisions as $value)
                  <option value="{{ $value }}" @selected($val('inpainting_precision') === $value)>{{ $value }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <fieldset class="mt-4">
            <legend class="{{ $label }}">Mode GPU</legend>
            <div class="space-y-1">
              @foreach ($gpuModes as $value => $text)
                <label class="flex items-center gap-2 text-sm text-gray-300">
                  <input type="radio" name="gpu_mode" value="{{ $value }}" @checked($val('gpu_mode') === $value) class="bg-gray-800 border-gray-700"> {{ $text }}
                </label>
              @endforeach
            </div>
          </fieldset>
        </div>

        <div class="flex items-center justify-between">
          <button type="submit" form="resetForm" onclick="return confirm('Reset semua setting ke default? API key tersimpan juga ikut terhapus.')"
            class="text-gray-200 bg-gray-800 border border-gray-700 hover:bg-gray-700 font-medium rounded-lg text-sm px-5 py-2.5 transition">
            Reset ke default
          </button>
          <button type="submit"
            class="text-white bg-indigo-600 hover:bg-indigo-500 focus:ring-4 focus:ring-indigo-800 font-medium rounded-lg text-sm px-5 py-2.5 transition">
            Simpan
          </button>
        </div>
      </form>

      <form id="resetForm" method="POST" action="{{ route('translate.settings.reset') }}">
        @csrf
        @method('DELETE')
      </form>

      <script>
        document.querySelectorAll('.preset-btn').forEach((btn) => {
          btn.addEventListener('click', () => {
            document.getElementById('translator').value = btn.dataset.translator;
            document.getElementById('api_base').value = btn.dataset.base;
            document.getElementById('api_model').value = btn.dataset.model;
            document.getElementById('api_key').focus();
          });
        });
      </script>
    @endif
  </div>
@endsection
