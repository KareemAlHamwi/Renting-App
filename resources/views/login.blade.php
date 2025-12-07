<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link rel="stylesheet" href="/css/app.css">
</head>

<body data-theme="{{ session('theme', 'light') }}">

    <div class="login-wrapper">
        <div class="login-card">
            <h1>Baytak</h1>

            <form method="POST" action="/login">
                @csrf

                <div class="input-group">
                    <label>Username</label>
                    <input type="text" name="email" required>
                </div>

                <div class="input-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>

                <button type="submit" class="btn-primary login-btn">Login</button>
            </form>
        </div>
    </div>

</body>

</html>
