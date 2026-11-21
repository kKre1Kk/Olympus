
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация — Olympus</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>

<div class="container py-5">
    <div class="card god-card p-4 mx-auto" style="max-width: 500px;">

        <h1>Регистрация</h1>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Имя</label>
                <input type="text" name="name"
                       value="{{ old('name') }}"
                       class="form-control" required>
                @error('name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username"
                       value="{{ old('username') }}"
                       class="form-control" required>
                @error('username')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email"
                       value="{{ old('email') }}"
                       class="form-control" required>
                @error('email')
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
                <label class="form-label">Повторите пароль</label>
                <input type="password" name="password_confirmation"
                       class="form-control" required>
            </div>

            <button class="btn btn-primary">
                Зарегистрироваться
            </button>

            <a href="{{ route('login') }}" class="btn btn-secondary">
                Войти
            </a>

        </form>

    </div>
</div>

</body>
</html>
