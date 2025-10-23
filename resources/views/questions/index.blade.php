@extends('layouts.admin_layout')

@section('content')
    <div class="container mt-4">
        <h1 class="mb-4">📋 Pertanyaan untuk Kategori: <span class="text-primary">{{ $category->name }}</span></h1>

        <a href="{{ route('questions.create', $category->id) }}" class="btn btn-primary mb-3">
            ➕ Tambah Pertanyaan
        </a>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card shadow">
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Teks Pertanyaan</th>
                            <th>Tipe</th>
                            <th width="180">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($category->questions as $question)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $question->question_text }}</td>
                                <td>
                                    @if (in_array($question->question_type, ['option', 'checkbox']))
                                        <a href="{{ route('question-options.index', $question->id) }}"
                                            class="badge bg-info text-dark">
                                            {{ ucfirst($question->question_type) }} 🔍
                                        </a>
                                    @else
                                        <span class="badge bg-secondary">Text</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('questions.edit', $question->id) }}" class="btn btn-sm btn-warning">
                                        ✏️ Edit
                                    </a>
                                    <form action="{{ route('questions.destroy', $question->id) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Yakin ingin menghapus pertanyaan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            🗑 Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">Belum ada pertanyaan untuk kategori ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <a href="{{ route('question-categories.index') }}" class="btn btn-secondary mt-3">
                    ← Kembali ke Daftar Kategori
                </a>
            </div>
        </div>
    </div>
@endsection
