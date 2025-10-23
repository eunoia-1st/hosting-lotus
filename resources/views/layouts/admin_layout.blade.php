<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - @yield('title')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f2f2;
            font-family: 'Segoe UI', sans-serif;
        }

        /* --- Sidebar --- */
        nav.sidebar {
            width: 240px;
            height: 100vh;
            background-color: #a33336;
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            padding: 20px 15px;
            display: flex;
            flex-direction: column;
        }

        .sidebar-logo-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }

        .sidebar-logo-row img {
            width: 60px;
            height: 60px;
            object-fit: contain;
        }

        .sidebar-logo-title h5,
        .sidebar-logo-title small {
            margin: 0;
        }

        .profile-row {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin: 15px 0;
        }

        .profile-row img {
            width: 35px;
            height: 35px;
        }

        .nav-link {
            color: white;
            margin: 5px 0;
            border-radius: 8px;
            transition: all 0.2s ease-in-out;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
        }

        .nav-link i {
            font-size: 1.1rem;
        }

        .nav-link.active {
            background-color: #5e1217;
            font-weight: 500;
        }

        .nav-link:hover {
            background-color: #fff;
            color: #a33336 !important;
        }

        /* --- Main Content --- */
        .content {
            margin-left: 250px;
            padding: 25px;
            min-height: 100vh;
        }

        .content-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .content-header h2 {
            font-weight: 600;
            margin: 0;
            color: #333;
        }

        /* --- CLOCK STYLING (BARU) --- */
        .clock-container {
            display: flex;
            align-items: center;
            gap: 1.25rem;
            /* Jarak antara jam dan tanggal */
            background-color: #ffffff;
            padding: 8px 16px;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }

        .clock-container .time-display,
        .clock-container .date-display {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
            color: #555;
        }

        .clock-container i {
            font-size: 1.1rem;
            color: #a33336;
            /* Warna ikon disamakan dengan tema */
        }

        .clock-container span {
            font-weight: 500;
        }

        /* --- AKHIR CLOCK STYLING --- */

        /* --- Responsive --- */
        @media (max-width: 768px) {
            nav.sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .content {
                margin-left: 0;
                padding: 15px;
            }

            .content-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
        }
    </style>
</head>

<body>

    <nav class="sidebar">
        <div class="sidebar-logo-row">
            <img src="{{ asset('image/logo.png') }}" alt="logo">
            <div class="sidebar-logo-title">
                <h5 class="m-0">Lotus Garden</h5>
                <small>Cafe & Steak</small>
            </div>
        </div>
        <hr class="text-white">
        <div class="profile-row">
            <img src="https://cdn-icons-png.flaticon.com/512/149/149071.png" class="rounded-circle" alt="Profile">
            <span class="profile-name">{{ Auth::user()->name ?? 'Admin' }}</span>
        </div>
        <hr class="text-white">
        <ul class="nav flex-column flex-grow-1">
            <li><a href="{{ route('dashboard.main') }}"
                    class="nav-link {{ request()->routeIs('dashboard.main') ? 'active' : '' }}"><i
                        class="bi bi-bar-chart"></i> Dashboard</a></li>
            <li><a href="{{ route('feedback-answers.index') }}"
                    class="nav-link {{ request()->routeIs('feedback-answers.index') ? 'active' : '' }}"><i
                        class="bi bi-list-ul"></i> Feedbacks</a></li>
            <li><a href="{{ route('question-categories.index') }}"
                    class="nav-link {{ request()->routeIs('question-categories.index') ? 'active' : '' }}"><i
                        class="bi bi-gear"></i> Setting Feedback</a></li>
            <li><a href="{{ route('customers.index') }}"
                    class="nav-link {{ request()->routeIs('customers.index') ? 'active' : '' }}"><i
                        class="bi bi-people"></i> Customers</a></li>
            <li><a href="{{ route('employees.index') }}"
                    class="nav-link {{ request()->routeIs('employees.index') ? 'active' : '' }}"><i
                        class="bi bi-person-badge"></i> Employees</a></li>
            <li><a href="{{ route('feedback.categories') }}"
                    class="nav-link {{ request()->routeIs('feedback.categories') ? 'active' : '' }}"><i
                        class="bi bi-calendar-event"></i> Feedback's Page</a></li>
            <li>
                <a href="{{ route('admin.pengaturan.edit') }}"
                    class="nav-link {{ request()->routeIs('admin.pengaturan.edit') ? 'active' : '' }}">
                    <i class="bi bi-person-gear"></i> Pengaturan Admin
                </a>
            </li>
        </ul>
        <div class="mt-auto">
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger w-100"><i class="bi bi-box-arrow-right"></i>
                    Logout</button>
            </form>
        </div>
    </nav>

    <main class="content">
        <header class="content-header">
            <h2>@yield('page-title')</h2>

            <div class="clock-container">
                <div class="time-display">
                    <i class="bi bi-clock"></i>
                    <span id="currentTime"></span>
                </div>
                <div class="date-display">
                    <i class="bi bi-calendar3"></i>
                    <span id="currentDate"></span>
                </div>
            </div>
        </header>

        <hr>
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function updateClock() {
            const now = new Date();
            const timeOptions = {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            };
            const timeString = now.toLocaleTimeString('id-ID', timeOptions).replace(/\./g, ':');

            const dateOptions = {
                weekday: 'long',
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            };
            const dateString = now.toLocaleDateString('id-ID', dateOptions);

            document.getElementById('currentTime').textContent = timeString;
            document.getElementById('currentDate').textContent = dateString;
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>

</body>

</html>
