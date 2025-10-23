<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Customer Page')</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Poppins:wght@400;500&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #fbeff1;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .header {
            background-color: #a33336;
            color: white;
            padding: 12px 16px;
        }

        .header-content {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            flex-wrap: wrap;
            gap: 10px;
        }

        .header-logo img {
            width: 90px;
            height: auto;
        }

        .header-title h1 {
            font-size: 1.6rem;
            font-weight: 600;
            margin: 0;
            line-height: 1.1;
        }

        .header-title p {
            font-size: 1rem;
            margin: 0;
            font-weight: 400;
        }

        main {
            flex: 1;
            padding: 16px 12px;
        }

        footer {
            background-color: #f5e6e8;
            color: #3a3a3a;
            padding: 20px 12px;
            text-align: center;
            font-size: 13px;
            line-height: 1.6;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
        }

        footer h4 {
            margin-bottom: 8px;
            font-family: 'Playfair Display', serif;
            font-size: 16px;
            color: #8b1e24;
            font-weight: 600;
        }

        footer p {
            margin: 2px 0;
        }

        footer .copy {
            margin-top: 10px;
            font-size: 12px;
            color: #777;
        }

        /* Responsif Mobile */
        @media (max-width: 576px) {
            .header-logo img {
                width: 70px;
            }

            .header-title h1 {
                font-size: 1.3rem;
            }

            .header-title p {
                font-size: 0.9rem;
            }

            main {
                padding: 12px 8px;
            }

            footer {
                font-size: 12px;
                padding: 16px 8px;
            }
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header class="header">
        <div class="container header-content">
            <div class="header-logo">
                <img src="{{ asset('image/logo.png') }}" alt="logo">
            </div>
            <div class="header-title">
                <h1>Lotus Garden</h1>
                <p>Cafe & Steak</p>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer>
        <h4>Kontak Kami</h4>
        <p>Jl. A. Wahab Syahranie No.01, Gn. Kelua, Kota Samarinda</p>
        <p>Email: Lotusgardencafe@gmail.com | Telp: (0812) 5635 2529</p>
        <p class="copy">&copy; 2025 Lotus Garden. All Rights Reserved.</p>
    </footer>

    <!-- Bootstrap JS (opsional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
