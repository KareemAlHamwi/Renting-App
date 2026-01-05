<!DOCTYPE html>
<html lang="en" data-theme="{{ session('theme', 'dark') }}">

<head>
    <meta charset="UTF-8" />
    <title>{{ $title ?? 'Baytak' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="/css/app.css" />
</head>

<body>

    <aside class="sidebar" aria-label="Main navigation">
        <div class="brand">
            <h1>Baytak</h1>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const root = document.documentElement;
                const toggleBtn = document.getElementById("themeToggle");

                const savedTheme = localStorage.getItem("theme");
                if (savedTheme) {
                    root.setAttribute("data-theme", savedTheme);
                }

                toggleBtn.addEventListener("click", function() {
                    const currentTheme = root.getAttribute("data-theme") || "dark";
                    const newTheme = currentTheme === "dark" ? "light" : "dark";

                    root.setAttribute("data-theme", newTheme);
                    localStorage.setItem("theme", newTheme);
                });
            });
        </script>



        <nav class="sidebar-nav" role="navigation">
            <a href="/" class="{{ request()->is('/') ? 'active' : '' }}">Home</a>
            <a href="/users" class="{{ request()->is('users*') ? 'active' : '' }}">Users</a>
            <a href="/properties" class="{{ request()->is('properties*') ? 'active' : '' }}">Properties</a>
            <a href="/reservations" class="{{ request()->is('reservations*') ? 'active' : '' }}">Reservations</a>
            {{-- <a href="/governorates" class="{{ request()->is('governorates*') ? 'active' : '' }}">Governorates</a> --}}
        </nav>

        <footer class="footer">
            {{-- <a href="/settings" class="{{ request()->is('settings') ? 'active' : '' }}">Settings</a> --}}
            <a id="themeToggle" class="{{ request()->is('settings') ? 'active' : '' }}">Theme</a>

            <a href="{{ route('logout') }}"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn-primary">
                Logout
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </footer>

    </aside>

    <!-- Content -->
    <main class="content-wrapper">
        @yield('content')
    </main>

</body>

</html>
