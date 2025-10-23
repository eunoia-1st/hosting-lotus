@extends('layouts.admin_layout')

@section('title', 'Tambah Pertanyaan')
@section('page-title', 'Tambah Pertanyaan')

@section('content')
    <div class="container mt-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">➕ Tambah Pertanyaan untuk Kategori: {{ $category->name }}</h4>
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

                <form action="{{ route('questions.store', $category->id) }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="question_text" class="form-label">Pertanyaan <span class="text-danger">*</span></label>
                        <textarea id="question_text" name="question_text" class="form-control" rows="4"
                            placeholder="Masukkan pertanyaan..." required>{{ old('question_text') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="question_type" class="form-label">Tipe Pertanyaan <span
                                class="text-danger">*</span></label>
                        <select id="question_type" name="question_type" class="form-select" required>
                            <option value="">-- Pilih Tipe Pertanyaan --</option>
                            <option value="checkbox" {{ old('question_type') == 'checkbox' ? 'selected' : '' }}>Checkbox
                            </option>
                            <option value="option" {{ old('question_type') == 'option' ? 'selected' : '' }}>Option</option>
                            <option value="text" {{ old('question_type') == 'text' ? 'selected' : '' }}>Text</option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('question-categories.edit', $category->id) }}" class="btn btn-secondary">
                            ← Batal
                        </a>
                        <button type="submit" class="btn btn-success">
                            💾 Simpan Pertanyaan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection
