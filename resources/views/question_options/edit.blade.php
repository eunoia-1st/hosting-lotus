@extends('layouts.admin_layout')

@section('title', 'Edit Opsi Pertanyaan')
@section('page-title', 'Edit Opsi Pertanyaan')

@section('content')
    <div class="container mt-4">

        {{-- Card Edit Opsi --}}
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">✏️ Edit Opsi Pertanyaan</h4>
            </div>
            <div class="card-body">

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
                <form
                    action="{{ route('question-options.update', ['question' => $questionOption->question_id, 'option' => $questionOption->id]) }}"
                    method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Pertanyaan --}}
                    <div class="mb-3">
                        <label class="form-label">Pertanyaan</label>
                        <input type="text" class="form-control"
                            value="{{ $questionOption->question->question_text ?? '-' }}" disabled>
                        <input type="hidden" name="question_id" value="{{ $questionOption->question_id }}">
                    </div>

                    {{-- Isi Opsi --}}
                    <div class="mb-3">
                        <label class="form-label">Isi Opsi</label>
                        <input type="text" name="question_value" class="form-control"
                            value="{{ old('question_value', $questionOption->question_value) }}" required>
                    </div>

                    {{-- Tombol aksi --}}
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('question-options.index', ['question' => $questionOption->question_id]) }}"
                            class="btn btn-secondary">
                            ← Kembali
                        </a>
                        <button type="submit" class="btn btn-success">
                            💾 Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection
