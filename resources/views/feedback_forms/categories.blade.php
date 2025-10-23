@extends('layouts.customer_layout')

@section('title', 'Pilih Kategori Feedback')
@section('header', 'Pilih Kategori Feedback')

@section('content')
    <div class="container py-3">
        {{-- Instruksi singkat --}}
        <p class="text-center mb-4" style="font-size: 1rem; color: #555;">
            Pilih kategori feedback yang ingin Anda isi. Tekan satu kategori untuk memulai.
        </p>

        {{-- Kategori --}}
        <div class="category-container">
            @if ($categories->isEmpty())
            <p class="empty-message">Tidak ada kategori feedback yang tersedia saat ini.</p>
            @else
            @foreach ($categories as $category)
                <a href="{{ route('feedback.questions', $category->id) }}" class="category-card" style="text-decoration: none;">
                <span class="category-title">{{ $category->name }}</span>
                </a>
            @endforeach
            @endif
        </div>
    </div>

    <style>
        :root {
            --primary-bg: #6C8E6B;
            --accent-bg: #a33336;
            --neutral-bg: #fbeff1;
            --text-color: #2e2e2e;
            --card-shadow: rgba(0, 0, 0, 0.06);
        }

        .category-container {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .category-card {
            display: block;
            border-radius: 16px;
            padding: 18px;
            text-align: center;
            font-size: 18px;
            font-weight: 600;
            font-family: 'Playfair Display', serif;
            color: #fff;
            background: var(--primary-bg);
            box-shadow: 0 4px 12px var(--card-shadow);
            transition: transform 0.2s ease, background 0.2s ease;
            position: relative;
            overflow: hidden;
        }

        .category-card::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 0%;
            height: 3px;
            background: var(--accent-bg);
            transition: width 0.2s ease;
        }

        .category-card:hover {
            transform: translateY(-3px);
            background: var(--accent-bg);
        }

        .category-card:hover::after {
            width: 100%;
        }

        .category-title {
            position: relative;
            z-index: 1;
            color: #fff;
        }

        .empty-message {
            text-align: center;
            color: #a33336;
            font-size: 16px;
            margin-top: 28px;
            font-family: 'Poppins', sans-serif;
        }

        /* Responsif Tablet & HP */
        @media (max-width: 768px) {
            .category-card {
                font-size: 17px;
                padding: 16px;
            }
        }

        @media (max-width: 480px) {
            .category-card {
                font-size: 16px;
                padding: 14px;
                border-radius: 14px;
            }
        }
    </style>
@endsection
