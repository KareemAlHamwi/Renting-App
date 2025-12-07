<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>{{ $title ?? 'Baytak' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="/css/app.css" />
</head>

<body data-theme="{{ session('theme', 'dark') }}">

    <!-- Sidebar -->
    <aside class="sidebar" aria-label="Main navigation">
        <div class="brand">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <rect width="24" height="24" rx="5" fill="var(--primary)" />
            </svg>
            <h1>Baytak</h1>
        </div>

        <nav class="sidebar-nav" role="navigation">
            <a href="#">Dashboard</a>

            {{-- <div class="section-title">Users</div> --}}
            <a href="#">All Users</a>
            {{-- <a href="#">Landlords</a> --}}
            {{-- <a href="#">Tenants</a> --}}

            {{-- <div class="section-title">Properties</div> --}}
            <a href="#">All Properties</a>
            {{-- <a href="#">Photos</a> --}}
            {{-- <a href="#">Reviews</a> --}}

            {{-- <div class="section-title">Reservations</div> --}}
            <a href="#">All Reservations</a>
            {{-- <a href="#">Pending</a> --}}
            {{-- <a href="#">Reserved</a> --}}
            {{-- <a href="#">Completed</a> --}}

            {{-- <div class="section-title">Regions</div> --}}
            <a href="#">Governorates</a>

            {{-- <div class="section-title">System</div> --}}
            <a href="#">Settings</a>
        </nav>

        <footer class="footer">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-primary">Logout</button>
            </form>
        </footer>
    </aside>

    <!-- Content -->
    <main class="content-wrapper">
        @yield('content')
    </main>

</body>

</html>
