
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход — Olympus</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>

<div class="container py-5">
    <div class="card god-card p-4 mx-auto" style="max-width: 500px;">

        <h1>Вход в Olympus</h1>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username"
                    value="{{ old('username') }}"
                    class="form-control" required autofocus>
                @error('username')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Пароль</label>
                <input type="password" name="password"
                       class="form-control" required>
                @error('password')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label>
                    <input type="checkbox" name="remember">
                    Запомнить меня
                </label>
            </div>

            <button class="btn btn-primary">Войти</button>

            <a href="{{ route('register') }}" class="btn btn-secondary">
                Регистрация
            </a>

        </form>

    </div>
</div>

</body>
</html>
