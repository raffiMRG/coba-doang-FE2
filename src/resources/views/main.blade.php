@extends('layouts.app')

@section('title', 'Folder List')

@section('content')

    <h1 class="text-3xl font-bold underline">Folder List</h1>

    @if ($error)
        <p style="color: red;">{{ $error }}</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Folder Name</th>
                    <th>Thumbnail</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($folders as $folder)
                    <tr>
                        <td>{{ $folder['id'] }}</td>
                        <td>{{ $folder['name'] }}</td>
                        <td>
                            <img src="{{ $folder['thumbnail'] }}" class="w-3xs">
                            {{-- <img src="{{ fix_thumbnail_url($folder['thumbnail']) }}" style="width:100px;"> --}}
                            {{-- <img src="{{ clean_thumbnail_url($folder['thumbnail']) }}" style="width:100px;"> --}}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

@endsection
