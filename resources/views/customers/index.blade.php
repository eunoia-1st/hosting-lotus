@extends('layouts.admin_layout')

@section('title', 'Daftar Pelanggan')
@section('page-title', 'Daftar Pelanggan')

@section('content')
    {{-- CDN Material Symbols --}}
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=download" />

    <div class="container mt-4">
        {{-- <h2 class="mb-4">👥 Daftar Pelanggan</h2> --}}

        {{-- Filter & Export --}}
        <div class="card mb-3 shadow-sm p-3">
            <form method="GET" action="" class="row g-2 align-items-end justify-content-end">
                <div class="col-auto">
                    <label for="start_date" class="form-label">Tanggal Mulai</label>
                    <input type="date" class="form-control" id="start_date" name="start_date"
                        value="{{ request('start_date') }}">
                </div>
                <div class="col-auto">
                    <label for="end_date" class="form-label">Tanggal Akhir</label>
                    <input type="date" class="form-control" id="end_date" name="end_date"
                        value="{{ request('end_date') }}">
                </div>
                <div class="col-auto d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('customers.export', ['start_date' => request('start_date'), 'end_date' => request('end_date')]) }}"
                        class="btn btn-success d-flex align-items-center">
                        <span class="material-symbols-outlined me-1" style="font-size:20px;">download</span>
                        Export
                    </a>
                </div>
            </form>
        </div>

        {{-- Tabel Pelanggan --}}
        <div class="card shadow">
            <div class="card-body p-0">
                <table class="table table-striped table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th style="width:5%">No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th style="width:20%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $cust)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $cust->name }}</td>
                                <td>{{ $cust->email }}</td>
                                <td>{{ $cust->phone }}</td>
                                <td>{{ $cust->address }}</td>
                                <td class="d-flex gap-2 flex-wrap">
                                    <button class="btn btn-info btn-sm toggle-feedback flex-grow-1">
                                        Lihat Feedback
                                    </button>
                                    <a href="{{ route('customers.edit', $cust->id) }}"
                                        class="btn btn-warning btn-sm flex-grow-1">Edit</a>
                                    <form method="POST" action="{{ route('customers.destroy', $cust->id) }}"
                                        class="d-inline flex-grow-1">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('Yakin hapus?')"
                                            class="btn btn-danger btn-sm w-100">Hapus</button>
                                    </form>
                                </td>
                            </tr>

                            {{-- Baris feedback tersembunyi --}}
                            <tr class="feedback-row d-none">
                                <td colspan="7" class="p-0">
                                    <table class="table table-sm table-bordered mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width:5%">No</th>
                                                <th>Kategori</th>
                                                <th style="width:20%">Tanggal Submit</th>
                                                <th style="width:15%">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $filteredFeedbacks = $cust->feedbacks;
                                                if (request('start_date')) {
                                                    $filteredFeedbacks = $filteredFeedbacks->where(
                                                        'created_at',
                                                        '>=',
                                                        request('start_date') . ' 00:00:00',
                                                    );
                                                }
                                                if (request('end_date')) {
                                                    $filteredFeedbacks = $filteredFeedbacks->where(
                                                        'created_at',
                                                        '<=',
                                                        request('end_date') . ' 23:59:59',
                                                    );
                                                }
                                            @endphp
                                            @forelse($filteredFeedbacks as $index => $feedback)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>
                                                        @php
                                                            $categories = $feedback->answers
                                                                ->pluck('question.question_category.name')
                                                                ->unique()
                                                                ->implode(', ');
                                                        @endphp
                                                        {{ $categories }}
                                                    </td>
                                                    <td>{{ $feedback->created_at->format('d-m-Y H:i') }}</td>
                                                    <td>
                                                        <a href="{{ route('customers.feedbackDetail', $feedback->id) }}"
                                                            class="btn btn-sm btn-info w-100">Lihat Detail</a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center text-muted py-2">
                                                        Belum ada feedback
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-3">
                                    Belum ada pelanggan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Script toggle feedback --}}
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            document.querySelectorAll(".toggle-feedback").forEach(btn => {
                btn.addEventListener("click", () => {
                    const tr = btn.closest("tr").nextElementSibling;
                    tr.classList.toggle("d-none");

                    if (tr.classList.contains("d-none")) {
                        btn.textContent = "Lihat Feedback";
                        btn.classList.remove("btn-secondary");
                        btn.classList.add("btn-info");
                    } else {
                        btn.textContent = "Sembunyikan Feedback";
                        btn.classList.remove("btn-info");
                        btn.classList.add("btn-secondary");
                    }
                });
            });
        });
    </script>
@endsection
