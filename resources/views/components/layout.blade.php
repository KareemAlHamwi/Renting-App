<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>{{ $title ?? 'Renting App' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link rel="stylesheet" href="/css/app.css">


</head>
<body data-theme="{{ session('theme', 'light') }}">

    <aside class="sidebar" aria-label="Main navigation">
        <div class="brand">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" aria-hidden>
                <rect width="24" height="24" rx="5" fill="var(--primary)"></rect>
            </svg>
            <h1>Baytak</h1>
        </div>

        <nav class="sidebar-nav" role="navigation" aria-label="Sidebar">
            <a href="#">Dashboard</a>

            <hr class="my-3 border-gray-300">


            <div class="section-title">Users</div>
            <a href="#">All Users</a>
            <a href="#">Landlords</a>
            <a href="#">Tenants</a>

            <hr class="my-3 border-gray-300">

            <div class="section-title">Properties</div>
            <a href="#">All Properties</a>
            <a href="#">Photos</a>
            <a href="#">Reviews</a>

            <hr class="my-3 border-gray-300">

            <div class="section-title">Reservations</div>
            <a href="#">All Reservations</a>
            <a href="#">Pending</a>
            <a href="#">Reserved</a>
            <a href="#">Completed</a>

            <hr class="my-3 border-gray-300">

            <div class="section-title">Regions</div>
            <a href="#">Governorates</a>

            <hr class="my-3 border-gray-300">

            <div class="section-title">System</div>
            <a href="#">Logs</a>
            <a href="#">Settings</a>
        </nav>

        <div class="footer">
            <a href="#" style="display:inline-block; margin-bottom:8px;">Profile</a>
            <form action="#" method="POST" style="display:inline">@csrf<button type="submit" style="background:transparent;border:0;padding:6px 0;color:var(--text-secondary);cursor:pointer">Logout</button></form>
        </div>
    </aside>

    <main class="content-wrapper">
        @yield('content')
    </main>

</body>
</html>
