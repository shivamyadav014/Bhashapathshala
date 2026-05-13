<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'LinguaLift')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --primary: #2454d6;
            --primary-dark: #173a96;
            --accent: #0f9f8f;
            --ink: #182230;
            --muted: #667085;
            --line: #d9e2ef;
            --surface: #ffffff;
            --soft: #f3f7fb;
            --shadow: 0 18px 45px rgba(24, 34, 48, 0.09);
        }

        body {
            min-height: 100vh;
            background:
                radial-gradient(circle at top left, rgba(15, 159, 143, 0.11), transparent 30rem),
                linear-gradient(180deg, #f8fbff 0%, #eef4f8 100%);
            color: var(--ink);
            font-family: "Segoe UI", system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
        }

        a {
            color: var(--primary);
        }

        .navbar {
            background: rgba(255, 255, 255, 0.92);
            border-bottom: 1px solid rgba(217, 226, 239, 0.85);
            box-shadow: 0 10px 30px rgba(24, 34, 48, 0.06);
            backdrop-filter: blur(14px);
        }

        .navbar .navbar-brand,
        .navbar .nav-link {
            color: var(--ink) !important;
        }

        .navbar-brand {
            font-weight: 800;
        }

        .brand-mark {
            display: inline-grid;
            width: 2rem;
            height: 2rem;
            margin-right: .45rem;
            place-items: center;
            color: #fff;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            border-radius: 8px;
            box-shadow: 0 10px 22px rgba(36, 84, 214, 0.22);
        }

        .nav-link {
            font-weight: 600;
        }

        .nav-link:hover,
        .nav-link:focus {
            color: var(--primary) !important;
        }

        .sidebar {
            min-height: calc(100vh - 64px);
            background: rgba(255, 255, 255, 0.92);
            border-right: 1px solid var(--line);
            box-shadow: 12px 0 35px rgba(24, 34, 48, 0.05);
        }

        .dashboard-shell.sidebar-collapsed .sidebar {
            display: none;
        }

        .dashboard-shell.sidebar-collapsed .main-content {
            width: 100%;
        }

        .sidebar-toggle {
            display: inline-grid;
            width: 2.75rem;
            height: 2.75rem;
            place-items: center;
            color: var(--ink);
            background: rgba(255, 255, 255, 0.94);
            border: 1px solid var(--line);
            border-radius: 8px;
            box-shadow: 0 10px 24px rgba(24, 34, 48, 0.08);
        }

        .sidebar-toggle:hover,
        .sidebar-toggle:focus {
            color: var(--primary);
            border-color: #b8c8df;
        }

        .sidebar-toggle i {
            font-size: 1.25rem;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: .65rem;
            margin: .2rem .75rem;
            padding: .8rem .9rem;
            color: #344054;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
        }

        .sidebar-link:hover,
        .sidebar-link.active {
            background: #eaf2ff;
            color: var(--primary);
        }

        .main-content {
            padding: 2rem;
        }

        .page-shell {
            width: min(1180px, calc(100vw - 2rem));
            margin-inline: auto;
        }

        .card,
        .list-group-item,
        .alert,
        .form-control,
        .form-select {
            border-color: var(--line);
            border-radius: 8px;
        }

        .card {
            background: rgba(255, 255, 255, 0.96);
            border: 1px solid var(--line);
            box-shadow: 0 14px 36px rgba(24, 34, 48, 0.07);
            transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
        }

        .card:hover {
            border-color: #b8c8df;
            box-shadow: var(--shadow);
            transform: translateY(-3px);
        }

        .btn {
            border-radius: 8px;
            font-weight: 700;
        }

        .btn-primary {
            background: var(--primary);
            border-color: var(--primary);
            box-shadow: 0 12px 24px rgba(36, 84, 214, 0.18);
        }

        .btn-primary:hover,
        .btn-primary:focus {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        .btn-success {
            background: var(--accent);
            border-color: var(--accent);
        }

        .badge {
            border-radius: 999px;
            padding: .45rem .65rem;
            font-weight: 700;
        }

        .badge-soft {
            color: #175cd3;
            background: #eaf2ff;
        }

        .section-title {
            display: flex;
            align-items: end;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1.25rem;
        }

        .text-muted {
            color: var(--muted) !important;
        }

        .course-media {
            display: grid;
            min-height: 170px;
            place-items: center;
            overflow: hidden;
            background:
                linear-gradient(135deg, rgba(36, 84, 214, .92), rgba(15, 159, 143, .88)),
                repeating-linear-gradient(45deg, rgba(255,255,255,.16) 0 1px, transparent 1px 14px);
            color: #fff;
        }

        .course-media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .course-media i {
            font-size: 2.4rem;
        }

        .course-media.has-image i {
            display: none;
        }

        .course-card .card-title {
            line-height: 1.3;
        }

        .meta-pill {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .35rem .6rem;
            color: #344054;
            background: #f2f5f9;
            border: 1px solid var(--line);
            border-radius: 999px;
            font-size: .84rem;
            font-weight: 700;
        }

        footer {
            border-top: 1px solid var(--line);
            background: #111827 !important;
        }

        @media (max-width: 767.98px) {
            .main-content {
                padding: 1.25rem;
            }

            .sidebar {
                min-height: auto;
                border-right: 0;
                border-bottom: 1px solid var(--line);
            }

            .section-title {
                display: block;
            }
        }
    </style>

    @yield('extra-css')
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
            <span class="brand-mark"><i class="fas fa-book-reader"></i></span>LinguaLift
        </a>

        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div id="nav" class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <li class="nav-item"><a class="nav-link" href="{{ route('courses.index') }}">Courses</a></li>

                @auth
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('profile') }}">
                            <i class="fas fa-user-circle me-1"></i>{{ auth()->user()->name }}
                        </a>
                    </li>

                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="btn nav-link" type="submit">Logout</button>
                        </form>
                    </li>
                @else
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                    <li class="nav-item"><a class="btn btn-primary ms-lg-2" href="{{ route('register') }}">Register</a></li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

@auth
<div class="container-fluid dashboard-shell sidebar-collapsed">
    <div class="px-3 pt-3">
        <button class="sidebar-toggle" type="button" aria-label="Open dashboard menu" aria-controls="dashboard-sidebar" aria-expanded="false">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    <div class="row">
        <div id="dashboard-sidebar" class="col-md-2 sidebar p-0">
            <div class="p-3 border-bottom">
                <small class="text-muted fw-bold text-uppercase">{{ ucfirst(auth()->user()->role) }} Dashboard</small>
            </div>

            @php $role = auth()->user()->role; @endphp

            <a href="{{ route($role.'.dashboard') }}" class="sidebar-link"><i class="fas fa-table-columns"></i> Dashboard</a>

            @if($role === 'admin')
                <a href="{{ route('admin.courses') }}" class="sidebar-link"><i class="fas fa-layer-group"></i> Courses</a>
                <a href="{{ route('admin.users') }}" class="sidebar-link"><i class="fas fa-users"></i> Users</a>
                <a href="{{ route('admin.reports') }}" class="sidebar-link"><i class="fas fa-chart-line"></i> Reports</a>
            @endif

            @if($role === 'instructor')
                <a href="{{ route('instructor.courses') }}" class="sidebar-link"><i class="fas fa-chalkboard-user"></i> My Courses</a>
            @endif

            @if($role === 'student')
                <a href="{{ route('courses.index') }}" class="sidebar-link"><i class="fas fa-compass"></i> Browse courses</a>
                <a href="{{ route('student.my-courses') }}" class="sidebar-link"><i class="fas fa-book-open"></i> My courses</a>
                <a href="{{ route('student.performance') }}" class="sidebar-link"><i class="fas fa-chart-simple"></i> Performance</a>
            @endif

            <hr>

            <a href="{{ route('profile') }}" class="sidebar-link"><i class="fas fa-user"></i> Profile</a>
            <a href="{{ route('settings') }}" class="sidebar-link"><i class="fas fa-gear"></i> Settings</a>
        </div>

        <div class="col-md-10 main-content">
            @if($errors->any())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $e)
                        <div>{{ $e }}</div>
                    @endforeach
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-warning">{{ session('error') }}</div>
            @endif

            @yield('content')
        </div>
    </div>
</div>
@else
<div class="page-shell py-5">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-warning">{{ session('error') }}</div>
    @endif
    @yield('content')
</div>
@endauth

<footer class="text-white text-center p-3 mt-5">
    &copy; 2026 LinguaLift
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const shell = document.querySelector('.dashboard-shell');
        const sidebarToggle = document.querySelector('.sidebar-toggle');

        if (!shell || !sidebarToggle) {
            return;
        }

        sidebarToggle.addEventListener('click', () => {
            const isCollapsed = shell.classList.toggle('sidebar-collapsed');
            sidebarToggle.setAttribute('aria-expanded', String(!isCollapsed));
            sidebarToggle.setAttribute('aria-label', isCollapsed ? 'Open dashboard menu' : 'Close dashboard menu');
        });
    });
</script>

@yield('extra-js')

@include('components.chatbot')
<script src="{{ asset('js/chatbot.js') }}"></script>

</body>
</html>
