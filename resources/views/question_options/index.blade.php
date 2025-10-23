@extends('layouts.admin_layout')

@section('title', 'Opsi Pertanyaan')
@section('page-title', 'Opsi Pertanyaan')

@section('content')
    <div class="container mt-4">

        {{-- Judul --}}
        <div class="mb-3">
            <h3>⚙️ Opsi untuk Pertanyaan: <em>"{{ $question->question_text }}"</em></h3>
        </div>

        {{-- Alert --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Card Tabel --}}
        <div class="card shadow-sm">
            <div class="card-body p-3">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th style="width:50px;">No</th>
                                <th>Opsi Pilihan</th>
                                <th style="width:180px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Form Tambah Opsi Baru --}}
                            <tr class="table-success">
                                <form action="{{ route('question-options.store', $question->id) }}" method="POST">
                                    @csrf
                                    <td>➕</td>
                                    <td>
                                        <input type="text" name="question_value" class="form-control"
                                            placeholder="Masukkan opsi baru" required>
                                    </td>
                                    <td class="text-center">
                                        <button type="submit" class="btn btn-sm btn-success">Simpan</button>
                                    </td>
                                </form>
                            </tr>

                            {{-- List Opsi yang sudah ada --}}
                            @forelse ($options as $option)
                                <tr>
                                    <form action="{{ route('question-options.update', [$question->id, $option->id]) }}"
                                        method="POST">
                                        @csrf
                                        @method('PUT')
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <input type="text" name="question_value"
                                                value="{{ $option->question_value }}" class="form-control" required>
                                        </td>
                                        <td class="text-center">
                                            <button type="submit" class="btn btn-sm btn-warning mb-1">✏️ Update</button>
                                    </form>
                                    <form action="{{ route('question-options.destroy', [$question->id, $option->id]) }}"
                                        method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Hapus opsi ini?')"
                                            class="btn btn-sm btn-danger mb-1">🗑 Hapus</button>
                                    </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">Belum ada opsi ditambahkan</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Tombol Kembali --}}
                <div class="mt-3">
                    <a href="{{ route('question-categories.edit', $question->question_category_id) }}"
                        class="btn btn-secondary">
                        ← Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection
