@extends('layouts.admin_layout')

@section('title', 'Detail Feedback')
@section('page-title', 'Detail Feedback')

@section('content')
    <div class="container mt-4">
        <a href="{{ route('customers.index', $feedbackDetail->customer_id) }}" class="btn btn-primary mb-3">
            &larr; Kembali
        </a>

        <div class="card shadow-sm p-4" style="border-radius:18px; min-height:80vh; background:#fff;">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold mb-0">Detail Feedback</h2>
                <div class="text-end text-muted">
                    <div>{{ $feedbackDetail->created_at->format('l, d F Y') }}</div>
                    <div>Pukul {{ $feedbackDetail->created_at->format('H:i') }} WITA</div>
                </div>
            </div>

            <div class="row g-4">
                <!-- Sidebar Info -->
                <div class="col-md-4">
                    <div class="d-flex flex-column gap-3">
                        <div class="shadow-sm rounded-3 p-3 d-flex align-items-center gap-2">
                            <i class="bi bi-person-circle fs-4 text-danger"></i>
                            <span>{{ $feedbackDetail->customer->name ?? 'Anonim' }}</span>
                        </div>

                        <div class="shadow-sm rounded-3 p-3 d-flex align-items-center gap-2">
                            <i class="bi bi-telephone-fill fs-4 text-danger"></i>
                            <span>{{ $feedbackDetail->customer->phone ?? '-' }}</span>
                        </div>

                        <div class="shadow-sm rounded-3 p-3 d-flex align-items-center gap-2">
                            <i class="bi bi-envelope-fill fs-4 text-danger"></i>
                            <span>{{ $feedbackDetail->customer->email ?? '-' }}</span>
                        </div>

                        <div class="shadow-sm rounded-3 p-3 d-flex align-items-center gap-2">
                            <i class="bi bi-grid-1x2-fill fs-4 text-danger"></i>
                            <span>{{ $feedbackDetail->seat->name ?? '-' }}</span>
                        </div>

                        <div class="shadow-sm rounded-3 p-3">
                            @php
                                $groupedEmployees = $feedbackDetail->employees->groupBy('position');
                            @endphp
                            @forelse ($groupedEmployees as $position => $employees)
                                <div class="fw-bold text-capitalize mb-1">{{ $position }}:</div>
                                <ul class="ps-3 mb-2">
                                    @foreach ($employees as $employee)
                                        <li>{{ $employee->name }}</li>
                                    @endforeach
                                </ul>
                            @empty
                                <p class="text-muted mb-0">Tidak ada karyawan terkait.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Feedback Answers -->
                <div class="col-md-8">
                    <div class="d-flex flex-column gap-3">

                        @php
                            // Group jawaban berdasarkan pertanyaan agar checkbox tidak duplikat
                            $groupedAnswers = $feedbackDetail->answers->groupBy('question_id');
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
@endsection
