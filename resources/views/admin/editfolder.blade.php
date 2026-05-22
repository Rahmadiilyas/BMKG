@extends('dashboard')

@section('content')
<h1 class="h3 mb-4 text-gray-800">Edit Folder</h1>

<div class="card shadow">
<div class="card-body">

<form action="{{ route('admin.folder.update', $folder->id) }}" method="POST">
@csrf

<div class="form-group">
    <label>Nama Folder</label>
    <input type="text" name="nama_folder"
           class="form-control"
           value="{{ $folder->nama_folder }}" required>
</div>

<div class="form-group">
    <label>Parent Folder</label>
    <select name="parent_id" class="form-control">
        <option value="">-- Folder Utama --</option>
        @foreach($folders as $f)
            <option value="{{ $f->id }}"
                {{ $folder->parent_id == $f->id ? 'selected' : '' }}>
                {{ $f->nama_folder }}
            </option>
        @endforeach
    </select>
</div>

<button class="btn btn-success">Update</button>
<a href="{{ route('admin.folder') }}" class="btn btn-secondary">Kembali</a>

</form>
</div>
</div>
@endsection
