@extends('layouts.customer_layout')

@section('title', 'Form Feedback')
@section('header', 'Form Feedback - ' . ($category->name ?? 'Kategori'))

@section('content')
    <div class="container py-3">

        {{-- Error Validation --}}
        @if ($errors->any())
            <div class="error-message mb-3">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('feedback.submit', $category->id) }}" method="POST" class="feedback-form">
            @csrf

            {{-- Seat Selection --}}
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-bold">Nomor Meja</span>
                    <span id="selected-seat-display" class="badge bg-success">-</span>
                </div>
                <button type="button" id="toggle-seat-btn" class="btn-toggle-seat w-100 mb-2">
                    Pilih Nomor Meja
                </button>
                <div class="seat-container" id="seat-container" style="display:none;">
                    @foreach ($seats as $seat)
                        <div class="seat-card {{ $seat->status === 'booked' ? 'booked' : 'available' }}"
                            data-seat-id="{{ $seat->id }}">
                            {{ $seat->name }}
                        </div>
                    @endforeach
                    <input type="hidden" name="seat_id" id="selected-seat" value="{{ old('seat_id') }}">
                </div>
            </div>

            {{-- Data Pribadi --}}
            <h3>Data Pribadi</h3>
            <div class="form-group mb-4">
                <label>Nama (opsional)</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Masukkan nama Anda">

                <label>Email</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="Masukkan email Anda">

                <label>Nomor Telepon</label>
                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Masukkan nomor telepon">

                <label>Alamat</label>
                <textarea name="address" rows="3" placeholder="Tuliskan alamat Anda...">{{ old('address') }}</textarea>
            </div>

            {{-- Pertanyaan Feedback --}}
            <h3>Pertanyaan</h3>
            @foreach ($questions as $question)
                <div class="question-block mb-3">
                    <p class="question-text">{{ $question->question_text }}</p>

                    {{-- Radio --}}
                    @if ($question->question_type === 'option')
                        <div class="option-group">
                            @foreach ($question->question_options as $option)
                                <label class="option-card">
                                    <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option->id }}"
                                        {{ old("answers.{$question->id}") == $option->id ? 'checked' : '' }}>
                                    {{ $option->question_value }}
                                </label>
                            @endforeach
                        </div>
                    @endif

                    {{-- Checkbox --}}
                    @if ($question->question_type === 'checkbox')
                        <div class="option-group">
                            @foreach ($question->question_options as $option)
                                <label class="option-card">
                                    <input type="checkbox" name="answers[{{ $question->id }}][]"
                                        value="{{ $option->id }}"
                                        {{ is_array(old("answers.{$question->id}")) && in_array($option->id, old("answers.{$question->id}")) ? 'checked' : '' }}>
                                    {{ $option->question_value }}
                                </label>
                            @endforeach
                        </div>
                    @endif

                    {{-- Text --}}
                    @if ($question->question_type === 'text')
                        <textarea name="answers[{{ $question->id }}]" placeholder="Tuliskan jawaban Anda di sini...">{{ old("answers.{$question->id}") }}</textarea>
                    @endif
                </div>
            @endforeach

            {{-- Buttons --}}
            <div class="form-buttons d-flex flex-column gap-2">
                <button type="submit" class="btn-submit w-100">Kirim Feedback</button>
                <a href="{{ route('feedback.categories') }}" class="btn-cancel w-100">Batal</a>
            </div>
        </form>
    </div>

    <style>
        /* === Lotus Garden Feedback Form - Mobile Friendly === */
        .container {
            max-width: 500px;
            margin: auto;
            padding: 0 12px;
        }

        .error-message {
            color: #C94F4F;
            background-color: #FFE5E5;
            padding: 12px 16px;
            border-radius: 10px;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
        }

        .feedback-form h3 {
            font-family: 'Playfair Display', serif;
            color: #a33336;
            margin-bottom: 12px;
            font-size: 20px;
        }

        .form-group label {
            font-weight: 500;
            margin-bottom: 6px;
            display: block;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 14px;
            border-radius: 12px;
            border: 1px solid #ccc;
            margin-bottom: 12px;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            transition: all 0.3s ease;
            background-color: #FAF9F6;
            resize: vertical;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #C9A227;
            box-shadow: 0 0 8px rgba(201, 162, 39, 0.3);
            background-color: #fffef9;
        }

        .question-block {
            margin-bottom: 18px;
        }

        .question-text {
            font-weight: 600;
            margin-bottom: 10px;
            font-size: 15px;
        }

        .option-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .option-card {
            display: flex;
            align-items: center;
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 10px;
            cursor: pointer;
            background-color: #FAF9F6;
            font-size: 14px;
            transition: all 0.2s;
        }

        .option-card input {
            margin-right: 10px;
        }

        .option-card:hover {
            background-color: #f0e7e7;
        }

        .feedback-form textarea {
            min-height: 120px;
            padding: 14px;
            border-radius: 12px;
            font-size: 14px;
            background-color: #FAF9F6;
        }

        .form-buttons {
            margin-top: 16px;
        }

        .btn-submit,
        .btn-cancel {
            padding: 14px;
            font-weight: 600;
            border-radius: 10px;
            text-align: center;
            transition: all 0.2s;
        }

        .btn-submit {
            background-color: #a33336;
            color: #fff;
            border: none;
        }

        .btn-submit:hover {
            background-color: #8b1e24;
            transform: translateY(-2px);
        }

        .btn-cancel {
            background-color: #E0E0E0;
            color: #2e2e2e;
            text-decoration: none;
        }

        .btn-cancel:hover {
            background-color: #cfcfcf;
            transform: translateY(-2px);
        }

        /* Seat Selection */
        .btn-toggle-seat {
            width: 100%;
            padding: 12px;
            background-color: #a33336;
            color: #fff;
            border-radius: 10px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            margin-bottom: 12px;
        }

        .btn-toggle-seat:hover {
            background-color: #8b1e24;
        }

        .seat-container {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }

        .seat-card {
            padding: 12px;
            border-radius: 10px;
            text-align: center;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            border: 2px solid #6C8E6B;
        }

        .seat-card.available {
            background-color: #E8F3E5;
            color: #2e4600;
        }

        .seat-card.booked {
            background-color: #f0f0f0;
            color: #999;
            cursor: not-allowed;
            border: 2px solid #ccc;
        }

        .seat-card.selected {
            background-color: #6C8E6B;
            color: #fff;
            border: 2px solid #6C8E6B;
        }

        #selected-seat-display {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 6px;
            display: inline-block;
        }

        @media (max-width: 768px) {
            .seat-container {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 480px) {
            .seat-container {
                grid-template-columns: repeat(2, 1fr);
            }

            .question-text {
                font-size: 14px;
            }

            input,
            textarea {
                font-size: 13px;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const seatContainer = document.getElementById('seat-container');
            const toggleBtn = document.getElementById('toggle-seat-btn');
            const selectedSeatInput = document.getElementById('selected-seat');
            const seatDisplay = document.getElementById('selected-seat-display');

            // Toggle seat container
            toggleBtn.addEventListener('click', function() {
                seatContainer.style.display = seatContainer.style.display === 'none' ? 'grid' : 'none';
            });

            // Seat selection
            document.querySelectorAll('.seat-card.available').forEach(card => {
                card.addEventListener('click', function() {
                    document.querySelectorAll('.seat-card.selected').forEach(s => s.classList
                        .remove('selected'));
                    this.classList.add('selected');
                    selectedSeatInput.value = this.dataset.seatId;
                    seatDisplay.textContent = 'Nomor Meja: ' + this.textContent;

                    // Auto-close seat list setelah memilih
                    seatContainer.style.display = 'none';
                });
            });

            // Pre-select if old input
            if (selectedSeatInput.value) {
                const preSelected = document.querySelector(`.seat-card[data-seat-id="${selectedSeatInput.value}"]`);
                if (preSelected) {
                    preSelected.classList.add('selected');
                    seatDisplay.textContent = 'Nomor Meja: ' + preSelected.textContent;
                }
            }
        });
    </script>
@endsection
