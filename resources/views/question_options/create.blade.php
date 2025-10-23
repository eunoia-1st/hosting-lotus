@extends('layouts.admin_layout')

@section('title', 'Tambah Opsi Pertanyaan')
@section('page-title', 'Tambah Opsi Pertanyaan')

@section('content')
    <div class="container mt-4">

        {{-- Card Tambah Opsi --}}
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">➕ Tambah Opsi untuk Pertanyaan</h5>
            </div>
            <div class="card-body">

                {{-- Pertanyaan --}}
                <p><strong>Pertanyaan:</strong> <em>"{{ $question->question_text }}"</em></p>

                {{-- Error --}}
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- Form --}}
                <form action="{{ route('question-options.store', ['question' => $question->id]) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Isi Opsi</label>
                        <input type="text" name="question_value" class="form-control" placeholder="Masukkan teks opsi"
                            value="{{ old('question_value') }}" required>
                    </div>

                    {{-- Tombol aksi --}}
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('question-options.index', $question->id) }}" class="btn btn-secondary">
                            ❌ Batal
                        </a>
                        <button type="submit" class="btn btn-success">
                            💾 Simpan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection
