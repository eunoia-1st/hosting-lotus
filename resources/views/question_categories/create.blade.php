@extends('layouts.admin_layout')

@section('title', 'Tambah Kategori')
@section('page-title', 'Tambah Kategori')

@section('content')
    <div class="container mt-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">➕ Tambah Kategori Baru</h4>
            </div>
            <div class="card-body">

                {{-- Error Messages --}}
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('question-categories.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}"
                            placeholder="Masukkan nama kategori" required>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('question-categories.index') }}" class="btn btn-secondary">
                            ← Kembali
                        </a>
                        <button type="submit" class="btn btn-success">
                            💾 Simpan Kategori
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection
