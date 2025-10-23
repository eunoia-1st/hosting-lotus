@extends('layouts.admin_layout')

@section('title', 'Daftar Kategori Pertanyaan')
@section('page-title', 'Daftar Kategori Pertanyaan')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="{{ route('question-categories.create') }}" class="btn btn-primary">➕ Tambah Kategori Baru</a>
        </div>

        {{-- Alerts --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body p-3">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle mb-0">
                        <thead class="table-dark text-center">
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>Nama</th>
                                <th style="width: 120px;">Status</th>
                                <th style="width: 320px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($categories as $category)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $category->name }}</td>
                                    <td class="text-center">
                                        @if ($category->status === 'active')
                                            <span class="badge bg-success">Aktif</span>
                                        @elseif ($category->status === 'pending')
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @else
                                            <span class="badge bg-secondary">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        {{-- Tombol Active --}}
                                        @if ($category->status === 'active')
                                            <form action="{{ route('question-categories.toggle', $category) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-warning"
                                                    {{ $category->isActiveHours() ? 'disabled' : '' }}>
                                                    Nonaktifkan
                                                </button>
                                            </form>
                                        @endif

                                        {{-- Tombol Pending --}}
                                        @if ($category->status === 'pending')
                                            <form action="{{ route('question-categories.toggle', $category) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-danger">Cancel</button>
                                            </form>
                                        @endif

                                        {{-- Tombol Inactive --}}
                                        @if ($category->status === 'inactive')
                                            <form action="{{ route('question-categories.toggle', $category) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-success">Publish</button>
                                            </form>

                                            <a href="{{ route('question-categories.edit', $category) }}"
                                                class="btn btn-sm btn-primary">✏️ Edit</a>

                                            <form action="{{ route('question-categories.destroy', $category) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="return confirm('Hapus kategori ini?')"
                                                    class="btn btn-sm btn-danger">🗑 Hapus</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Belum ada kategori.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Bootstrap JS untuk alert dismiss --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection
