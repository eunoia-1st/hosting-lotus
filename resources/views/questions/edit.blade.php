@extends('layouts.admin_layout') {{-- gunakan layout admin yang sudah ada --}}

@section('title', 'Edit Pertanyaan')
@section('page-title', 'Edit Pertanyaan')

@section('content')
    <div class="container mt-4">
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">✏️ Edit Pertanyaan</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('questions.update', $question->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Input Pertanyaan --}}
                    <div class="mb-3">
                        <label for="question_text" class="form-label">Pertanyaan</label>
                        <input type="text" name="question_text" id="question_text"
                            value="{{ old('question_text', $question->question_text) }}"
                            class="form-control @error('question_text') is-invalid @enderror">
                        @error('question_text')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Pilih Tipe --}}
                    <div class="mb-3">
                        <label for="question_type" class="form-label">Tipe</label>
                        <select name="question_type" id="question_type"
                            class="form-select @error('question_type') is-invalid @enderror">
                            <option value="checkbox" {{ $question->question_type == 'checkbox' ? 'selected' : '' }}>Checkbox
                            </option>
                            <option value="option" {{ $question->question_type == 'option' ? 'selected' : '' }}>Option
                            </option>
                            <option value="text" {{ $question->question_type == 'text' ? 'selected' : '' }}>Text</option>
                        </select>
                        @error('question_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('question-categories.edit', $question->question_category_id) }}"
                            class="btn btn-outline-secondary me-2">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            💾 Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
