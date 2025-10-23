@extends('layouts.admin_layout')

@section('title', 'Tambah Karyawan')
@section('page-title', 'Tambah Karyawan Baru')

@section('content')

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Form Tambah Karyawan</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('employees.store') }}">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold">Nama</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}"
                        placeholder="Masukkan nama karyawan" required>
                </div>

                <div class="mb-3">
                    <label for="position" class="form-label fw-semibold">Posisi</label>
                    <select name="position" id="position" class="form-select" required>
                        <option value="" disabled {{ old('position') ? '' : 'selected' }}>-- Pilih Posisi --</option>
                        @foreach (['office', 'waiter', 'cook', 'staff', 'bar'] as $pos)
                            <option value="{{ $pos }}" {{ old('position') == $pos ? 'selected' : '' }}>
                                {{ ucfirst($pos) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="d-flex gap-2 mt-3">
                    <button type="submit" class="btn btn-success">💾 Simpan</button>
                    <a href="{{ route('employees.index') }}" class="btn btn-secondary">← Kembali</a>
                </div>
            </form>
        </div>
    </div>

@endsection
