@extends('layouts.admin_layout')

@section('title', 'Edit Kategori')
@section('page-title', 'Edit Kategori')

@section('content')
    <div class="container mt-5">

        {{-- Alerts --}}
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
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Card Edit Kategori --}}
        <div class="card shadow-sm mb-5">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">✏️ Edit Kategori: {{ $question_category->name }}</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('question-categories.update', $question_category) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Kategori</label>
                        <input type="text" class="form-control" id="name" name="name"
                            value="{{ old('name', $question_category->name) }}" required>
                    </div>

                    <div class="d-flex justify-content-between mt-3">
                        <a href="{{ route('question-categories.index') }}" class="btn btn-secondary">← Kembali</a>
                        <button type="submit" class="btn btn-success">💾 Update Kategori</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Card Daftar Pertanyaan --}}
        <div class="card shadow">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">📋 Pertanyaan dalam kategori ini</h5>
                <a href="{{ route('questions.create', $question_category->id) }}" class="btn btn-light btn-sm">
                    ➕ Tambah Pertanyaan
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered mb-0 align-middle">
                        <thead class="table-dark text-center">
                            <tr>
                                <th style="width:50px;">No</th>
                                <th>Teks Pertanyaan</th>
                                <th style="width:120px;">Tipe</th>
                                <th style="width:180px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($question_category->questions as $question)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $question->question_text }}</td>
                                    <td class="text-center">
                                        @if (in_array($question->question_type, ['option', 'checkbox']))
                                            <a href="{{ route('question-options.index', $question->id) }}"
                                                class="badge bg-info text-dark px-2 py-1">
                                                {{ ucfirst($question->question_type) }} 🔍
                                            </a>
                                        @else
                                            <span class="badge bg-secondary px-2 py-1">Text</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('questions.edit', $question->id) }}"
                                            class="btn btn-sm btn-warning mb-1">
                                            ✏️ Edit
                                        </a>
                                        <form action="{{ route('questions.destroy', $question->id) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Yakin ingin menghapus pertanyaan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger mb-1">
                                                🗑 Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">
                                        Belum ada pertanyaan untuk kategori ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection
