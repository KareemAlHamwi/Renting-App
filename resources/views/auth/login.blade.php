<!DOCTYPE html>
<html lang="en" data-theme="{{ session('theme', 'dark') }}">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login | Baytak</title>
    <link rel="stylesheet" href="/css/app.css" />
</head>

<script>
    (function() {
        const savedTheme = localStorage.getItem("theme");
        if (savedTheme) {
            document.documentElement.setAttribute("data-theme", savedTheme);
        }
    })();
</script>

<body>

    <main class="login-wrapper">
        <section class="login-card">
            <h1>Baytak</h1>

            <form method="POST" action="/login" novalidate>
                @csrf

                <div class="input-group">
                    <label for="username">Username</label>
                    <input id="username" type="text" name="username" required autocomplete="username" />

                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password" />
                </div>

                <button type="submit" class="btn-primary login-btn">
                    Login
                </button>

                @if ($errors->any())
                    <div class="error-message" role="alert">
                        {{ $errors->first() }}
                    </div>
                @endif
            </form>
        </section>
    </main>

</body>

</html>
