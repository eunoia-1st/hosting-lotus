@extends('layouts.customer_layout')

@section('title', 'Terima Kasih!')

@section('content')
    <div class="thankyou-container">
        <div class="thankyou-card">
            <!-- Icon Checkmark -->
            <div class="checkmark">✔️</div>

            <!-- Heading -->
            <h1>Terima Kasih!</h1>

            <!-- Subheading -->
            <h2>Feedback Anda Telah Kami Terima</h2>

            <!-- Body Text -->
            <p>
                Terima kasih telah berbagi masukan dengan kami. Setiap saran Anda sangat berarti untuk meningkatkan layanan
                Lotus Garden. 🌿
            </p>
        </div>
    </div>

    <style>
        .thankyou-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 80vh;
            padding: 20px;
            background-color: #fbeff1;
        }

        .thankyou-card {
            background-color: #FAF9F6;
            padding: 40px 30px;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            text-align: center;
            max-width: 500px;
            width: 100%;
        }

        .checkmark {
            font-size: 60px;
            color: #6C8E6B;
            margin-bottom: 20px;
        }

        h1 {
            font-family: 'Playfair Display', serif;
            color: #6C8E6B;
            font-size: 28px;
            margin-bottom: 10px;
        }

        h2 {
            font-family: 'Playfair Display', serif;
            color: #C9A227;
            font-size: 20px;
            margin-bottom: 18px;
        }

        p {
            font-family: 'Poppins', sans-serif;
            color: #333;
            font-size: 15px;
            line-height: 1.6;
            margin-bottom: 25px;
        }

        .btn-back {
            display: inline-block;
            padding: 12px 20px;
            background-color: #a33336;
            color: #fff;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
            transition: background 0.3s, transform 0.2s;
        }

        .btn-back:hover {
            background-color: #8b1e24;
            transform: translateY(-2px);
        }

        @media (max-width: 480px) {
            .thankyou-card {
                padding: 30px 20px;
            }

            .checkmark {
                font-size: 50px;
            }

            h1 {
                font-size: 24px;
            }

            h2 {
                font-size: 18px;
            }

            p {
                font-size: 14px;
            }
        }
    </style>
@endsection
