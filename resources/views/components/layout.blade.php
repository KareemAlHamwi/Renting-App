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
            <h1>Baytak</h1>
            <button id="themeToggle" class="btn-primary">Theme</button>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const body = document.body;
                const toggleBtn = document.getElementById("themeToggle");

                toggleBtn.addEventListener("click", function() {
                    const currentTheme = body.getAttribute("data-theme");
                    const newTheme = currentTheme === "dark" ? "light" : "dark";
                    body.setAttribute("data-theme", newTheme);

                    // If you want to persist via localStorage:
                    localStorage.setItem("theme", newTheme);

                    // If you want to persist via Laravel session,
                    // you’d need an AJAX call to update session('theme') server-side.
                });

                // Restore from localStorage if available
                const savedTheme = localStorage.getItem("theme");
                if (savedTheme) {
                    body.setAttribute("data-theme", savedTheme);
                }
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
