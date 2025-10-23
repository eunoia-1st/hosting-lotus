@extends('layouts.admin_layout')

@section('title', 'Detail Feedback')
@section('page-title', 'Detail Feedback')

@section('content')
    <div class="container mt-4">

        {{-- Tombol Kembali --}}
        <a href="{{ route('feedback-answers.index', $feedbackAnswer->customer_id) }}" class="btn btn-outline-primary mb-4">
            &larr; Kembali
        </a>

        {{-- Card utama --}}
        <div class="card shadow-sm p-4" style="border-radius:18px; min-height:80vh; background:#fff;">

            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
                <h2 class="fw-bold mb-0">Detail Feedback</h2>
                <div class="text-end text-muted">
                    <div>{{ $feedbackAnswer->created_at->format('l, d F Y') }}</div>
                    <div>Pukul {{ $feedbackAnswer->created_at->format('H:i') }} WITA</div>
                </div>
            </div>

            <div class="row g-4">

                {{-- Sidebar Info --}}
                <div class="col-lg-4 col-md-12">
                    <div class="d-flex flex-column gap-3">

                        <div class="shadow-sm rounded-3 p-3 d-flex align-items-center gap-2">
                            <i class="bi bi-person-circle fs-4 text-danger"></i>
                            <span>{{ $feedbackAnswer->customer->name ?? 'Anonim' }}</span>
                        </div>

                        <div class="shadow-sm rounded-3 p-3 d-flex align-items-center gap-2">
                            <i class="bi bi-telephone-fill fs-4 text-danger"></i>
                            <span>{{ $feedbackAnswer->customer->phone ?? '-' }}</span>
                        </div>

                        <div class="shadow-sm rounded-3 p-3 d-flex align-items-center gap-2">
                            <i class="bi bi-envelope-fill fs-4 text-danger"></i>
                            <span>{{ $feedbackAnswer->customer->email ?? '-' }}</span>
                        </div>

                        <div class="shadow-sm rounded-3 p-3 d-flex align-items-center gap-2">
                            <i class="bi bi-grid-1x2-fill fs-4 text-danger"></i>
                            <span>{{ $feedbackAnswer->seat->name ?? '-' }}</span>
                        </div>

                        {{-- Karyawan Terkait --}}
                        <div class="shadow-sm rounded-3 p-3">
                            @php
                                // Kelompokkan karyawan berdasarkan label posisi dari relasi employees
                                $groupedEmployees = $feedbackAnswer->employees->groupBy('position_label');
                            @endphp

                            @forelse ($groupedEmployees as $positionLabel => $employees)
                                <div class="fw-bold text-primary mb-1">{{ $positionLabel }}:</div>
                                <ul class="ps-3 mb-2">
                                    @foreach ($employees as $employee)
                                        <li>{{ $employee->name }}</li>
                                    @endforeach
                                </ul>
                            @empty
                                <p class="text-muted mb-0">Tidak ada karyawan yang bekerja pada saat feedback ini dibuat.
                                </p>
                            @endforelse
                        </div>

                    </div>
                </div>

                {{-- Feedback Answers --}}
                <div class="col-md-8">
                    <div class="d-flex flex-column gap-3">

                        @php
                            // Group jawaban berdasarkan pertanyaan agar checkbox tidak duplikat
                            $groupedAnswers = $feedbackAnswer->answers->groupBy('question_id');
                        @endphp

                        @foreach ($groupedAnswers as $questionId => $answers)
                            @php
                                $question = $answers->first()->question;
                                $type = $question->question_type;
                                $display = null;

                                if ($type === 'text') {
                                    $display = $answers->first()->answer_text;
                                } elseif ($type === 'option') {
                                    $optionId = $answers->first()->option_id;
                                    $display = $question->question_options
                                        ->where('id', $optionId)
                                        ->pluck('question_value')
                                        ->first();
                                } elseif ($type === 'checkbox') {
                                    $optionIds = $answers->pluck('option_id')->toArray();
                                    $display = $question->question_options
                                        ->whereIn('id', $optionIds)
                                        ->pluck('question_value');
                                }
                            @endphp

                            <div class="shadow-sm rounded-3 p-3">
                                <div class="fw-bold mb-2">{{ $question->question_text }}</div>

                                @if ($type === 'text')
                                    <p class="text-secondary mb-0">{{ $display ?: '-' }}</p>
                                @elseif ($type === 'option')
                                    <div class="text-secondary">{{ $display ?: '-' }}</div>
                                @elseif ($type === 'checkbox')
                                    @if ($display && $display->count())
                                        <ul class="mb-0 ps-3 text-secondary">
                                            @foreach ($display as $val)
                                                <li>{{ $val }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <div class="text-secondary">-</div>
                                    @endif
                                @else
                                    <div class="text-secondary">-</div>
                                @endif
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Tombol Kembali */
        .btn-outline-primary {
            color: #fff;
            background-color: #0d6efd;
            border-color: #0d6efd;
            font-weight: 500;
        }

        .btn-outline-primary:hover {
            background-color: #0b5ed7;
            color: #fff;
            border-color: #0a58ca;
            transform: translateY(-1px);
            transition: 0.2s;
        }

        /* Shadow dan card */
        .shadow-sm {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05) !important;
        }

        .fw-bold {
            font-weight: 600 !important;
        }

        .text-primary {
            color: #a33336 !important;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .d-flex.justify-content-between.align-items-center.flex-wrap {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }
        }
    </style>
@endsection
