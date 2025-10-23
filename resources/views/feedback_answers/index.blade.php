@extends('layouts.admin_layout')

@section('title', 'Dafar Feedback')
@section('page-title', 'Daftar Feedback')

@section('content')
    {{-- CDN Material Symbols --}}
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=download" />

    <div class="container mt-4">
        {{-- <h2 class="mb-4">📋 Daftar Feedback</h2> --}}

        {{-- Filter --}}
        <div class="card mb-3 shadow-sm p-3">
            <form method="GET" action="{{ route('feedback-answers.index') }}" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label for="category" class="form-label">Kategori</label>
                    <select name="category" id="category" class="form-select">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="start_date" class="form-label">Tanggal Mulai</label>
                    <input type="date" id="start_date" name="start_date" class="form-control"
                        value="{{ request('start_date') }}">
                </div>

                <div class="col-md-3">
                    <label for="end_date" class="form-label">Tanggal Akhir</label>
                    <input type="date" id="end_date" name="end_date" class="form-control"
                        value="{{ request('end_date') }}">
                </div>

                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1">Filter</button>
                    <a href="{{ route('feedback-answers.index') }}" class="btn btn-secondary flex-grow-1">Reset</a>
                    <a href="{{ route('feedback-answers.export', [
                        'start_date' => request('start_date'),
                        'end_date' => request('end_date'),
                        'category' => request('category'),
                    ]) }}"
                        class="btn btn-success d-flex align-items-center justify-content-center flex-grow-1">
                        <span class="material-symbols-outlined me-1" style="font-size:20px;">download</span>
                        Export
                    </a>
                </div>
            </form>
        </div>

        {{-- Tabel Feedback --}}
        <div class="card shadow">
            <div class="card-body p-0">
                <table class="table table-striped table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th style="width:5%">No</th>
                            <th>Kategori</th>
                            <th style="width:20%">Tanggal Submit</th>
                            <th style="width:15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($feedbackAnswer as $index => $feedback)
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
                                    <a href="{{ route('feedback-answers.show', $feedback->id) }}"
                                        class="btn btn-sm btn-primary w-100">Lihat Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">
                                    Belum ada feedback masuk
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
