<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Lotus Garden</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #fbeff1;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            max-width: 400px;
            width: 100%;
            padding: 2rem;
            border-radius: 12px;
            background: #fff;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
        }

        .login-card h3 {
            font-weight: bold;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .btn-login {
            background-color: #8b1e24;
            color: #fff;
            font-weight: bold;
            width: 100%;
        }

        .btn-login:hover {
            background-color: #a52834;
        }

        .logo {
            display: flex;
            justify-content: center;
            margin-bottom: 1rem;
        }

        .logo img {
            max-height: 80px;
        }

        .form-check-label {
            font-size: 0.9rem;
        }

        .forgot-link {
            font-size: 0.9rem;
            text-decoration: none;
            color: #8b1e24;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="login-card">
        <div class="logo">
            <img src="{{ asset('image/logo2.png') }}" alt="Lotus Garden Logo">
        </div>
        <h3>Please Login</h3>

        {{-- Menampilkan pesan sukses setelah logout --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Menggunakan route dari controller sebelumnya, pastikan namanya 'admin.login' atau sesuaikan --}}
        {{-- Jika di web.php kamu tidak menamainya, gunakan url('/admin/login') --}}
        <form method="POST" action="{{ url('/admin/login') }}">
            @csrf
            <div class="mb-3">
                {{-- DIUBAH MENJADI USERNAME --}}
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control @error('username') is-invalid @enderror" id="username"
                    name="username" placeholder="Enter username" value="{{ old('username') }}" required autofocus>

                {{-- PESAN ERROR SPESIFIK UNTUK USERNAME --}}
                @error('username')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter password"
                    required>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Remember Me</label>
                </div>
                <a href="#" class="forgot-link">Forgot Password?</a>
            </div>
            <button type="submit" class="btn btn-login">Login</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
