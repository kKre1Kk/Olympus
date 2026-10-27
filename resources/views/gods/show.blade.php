<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $god->name }} — Olympus</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <header class="site-header">
        <nav class="navbar navbar-expand-md navbar-dark">
            <div class="container">
                <a class="navbar-brand" href="{{ route('gods.index') }}">
                    <span class="logo-icon" aria-hidden="true">Ω</span>
                    <span>Olympus</span>
                </a>
                <a href="{{ route('gods.index') }}" class="btn header-more">На главную</a>
            </div>
        </nav>
    </header>
    <main class="page-main">
        <div class="container">
            <div class="card god-card">
                <div class="row g-0">
                    <div class="col-md-4">
                        <img src="{{ asset('storage/' . $god->image_path) }}" class="img-fluid" alt="{{ $god->name }}">
                    </div>

                    <div class="col-md-8">
                        <div class="card-body">
                            <h1>{{ $god->name }}</h1>
                            <h5>{{ $god->sphere }}</h5>
                            <p>{{ $god->description }}</p>
                            <p>Дата добавления: {{ $god->recorded_at ?? 'Не указана' }}</p>
                            <div class="d-flex gap-2 mb-4">
                                <a href="{{ route('gods.edit', $god) }}" class="btn btn-primary">
                                    Изменить
                                </a>

                                <form action="{{ route('gods.destroy', $god) }}" method="POST" onsubmit="return confirm('Удалить этого бога?')">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-danger">
                                        Удалить
                                    </button>
                                </form>
                            </div>
                            <h3>Факты</h3>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item"><strong>Символы:</strong> {{ $god->symbols }}</li>
                                <li class="list-group-item"><strong>Родители:</strong> {{ $god->parents }}</li>
                                <li class="list-group-item"><strong>Братья и сёстры:</strong> {{ $god->siblings }}</li>
                                <li class="list-group-item"><strong>Супруг:</strong> {{ $god->spouse }}</li>
                                <li class="list-group-item"><strong>Дети:</strong> {{ $god->children }}</li>
                                <li class="list-group-item"><strong>Храм:</strong> {{ $god->temple }}</li>
                                <li class="list-group-item"><strong>Миф:</strong> {{ $god->myth }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
<footer class="site-footer">
    <div class="container d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-3">
        <span>Яковенко Кирилл. Лабораторная работа №3</span>
        <div class="d-flex gap-2 align-items-center">
            <a class="social-link" href="https://telegram.org/" target="_blank" title="Telegram">
                <img class="social-icon social-telegram img-fluid"
                     src="{{ asset('storage/gods/telegram.png') }}"
                     alt="Telegram" width="29" height="29">
            </a>
            <a class="social-link" href="https://max.ru/" target="_blank" title="MAX">
                <img class="social-icon social-max img-fluid"
                     src="{{ asset('storage/gods/MAX.png') }}"
                     alt="MAX" width="29" height="29">
            </a>
        </div>
    </div>
</footer>
</html>
