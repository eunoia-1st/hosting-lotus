@extends('layouts.admin_layout')

@section('title', 'Daftar Karyawan')
@section('page-title', 'Daftar Karyawan')

@section('content')
    <div class="container mt-4">

        {{-- Filter & Search --}}
        <div class="card mb-3 shadow-sm p-3">
            <form method="GET" action="{{ route('employees.index') }}" class="d-flex gap-2 flex-wrap align-items-end">
                <div class="flex-grow-1">
                    <input type="text" name="search" class="form-control" placeholder="Cari nama..."
                        value="{{ request('search') }}">
                </div>

                <div class="flex-grow-1">
                    <select name="position" class="form-select">
                        <option value="">Semua Posisi</option>
                        @foreach ($positions as $pos)
                            <option value="{{ $pos }}" {{ request('position') == $pos ? 'selected' : '' }}>
                                {{ ucfirst($pos) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('employees.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>

        {{-- Tambah Karyawan --}}
        <div class="mb-3">
            <a href="{{ route('employees.create') }}" class="btn btn-success">+ Tambah Karyawan</a>
        </div>

        {{-- Tabel Karyawan --}}
        <div class="card shadow">
            <div class="card-body p-0 table-responsive">
                <table class="table table-striped table-hover mb-0 align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th style="width:5%">No</th>
                            <th>Nama</th>
                            <th>Posisi</th>
                            <th style="width:25%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($employees as $emp)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $emp->name }}</td>
                                <td>{{ ucfirst($emp->position) }}</td>
                                <td class="d-flex gap-2 flex-wrap">
                                    <button class="btn btn-info btn-sm toggle-shift flex-grow-1">Lihat Shift</button>
                                    <a href="{{ route('employees.editDetails', $emp->id) }}"
                                        class="btn btn-warning btn-sm flex-grow-1">Edit</a>
                                    <form method="POST" action="{{ route('employees.destroy', $emp->id) }}"
                                        class="d-inline flex-grow-1" onsubmit="return confirm('Yakin hapus karyawan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm w-100">Hapus</button>
                                    </form>
                                </td>
                            </tr>

                            {{-- ================= PERUBAHAN UTAMA DI SINI ================= --}}
                            <tr class="shift-row d-none">
                                <td colspan="4">
                                    <div class="table-responsive p-3 bg-light border rounded">
                                        <h6 class="mb-2">Jadwal Shift: <strong>{{ $emp->name }}</strong></h6>
                                        <table class="table table-sm table-bordered text-center mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Hari</th>
                                                    {{-- Kolom Jam Mulai & Selesai digabung menjadi Jam Kerja --}}
                                                    <th style="width: 40%">Jam Kerja</th>
                                                    <th>Tipe Shift</th>
                                                    <th>Libur</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if ($emp->employee_shifts->count())
                                                    @foreach ($emp->employee_shifts->sortBy(function ($shift) {
            $daysOrder = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
            return array_search($shift->day, $daysOrder);
        }) as $detail)
                                                        <tr>
                                                            <td>{{ ucfirst($detail->day) }}</td>

                                                            {{-- Logika untuk menampilkan jam kerja berdasarkan tipe shift --}}
                                                            <td>
                                                                @if ($detail->shift_type === 'split' && $detail->start_time && $detail->start_time_2)
                                                                    <span>{{ \Carbon\Carbon::parse($detail->start_time)->format('H:i') }}
                                                                        -
                                                                        {{ \Carbon\Carbon::parse($detail->end_time)->format('H:i') }}</span>
                                                                    <span class="fw-bold mx-1">&</span>
                                                                    <span>{{ \Carbon\Carbon::parse($detail->start_time_2)->format('H:i') }}
                                                                        -
                                                                        {{ \Carbon\Carbon::parse($detail->end_time_2)->format('H:i') }}</span>
                                                                @elseif ($detail->start_time)
                                                                    <span>{{ \Carbon\Carbon::parse($detail->start_time)->format('H:i') }}
                                                                        -
                                                                        {{ \Carbon\Carbon::parse($detail->end_time)->format('H:i') }}</span>
                                                                @else
                                                                    <span class="text-muted">-</span>
                                                                @endif
                                                            </td>

                                                            <td>{{ $detail->shift_type ? ucfirst($detail->shift_type) : '-' }}
                                                            </td>
                                                            <td>
                                                                @if (!$detail->start_time && !$detail->shift_type)
                                                                    <span class="badge bg-success">Libur</span>
                                                                @else
                                                                    <span class="badge bg-danger">Kerja</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="4" class="text-center text-muted py-2">Belum ada
                                                            shift</td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                            {{-- ================= AKHIR DARI PERUBAHAN ================= --}}

                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">Tidak ada karyawan ditemukan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Script toggle shift --}}
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            document.querySelectorAll(".toggle-shift").forEach(btn => {
                btn.addEventListener("click", () => {
                    const tr = btn.closest("tr").nextElementSibling;
                    tr.classList.toggle("d-none");

                    if (tr.classList.contains("d-none")) {
                        btn.textContent = "Lihat Shift";
                        btn.classList.remove("btn-secondary");
                        btn.classList.add("btn-info");
                    } else {
                        btn.textContent = "Sembunyikan Shift";
                        btn.classList.remove("btn-info");
                        btn.classList.add("btn-secondary");
                    }
                });
            });
        });
    </script>
@endsection
