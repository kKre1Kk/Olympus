<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $god->name }} — Olympus</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    @include('partials.header')
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
                            <p>
                                Автор:
                                @if($god->user)
                                    <a href="{{ route('users.show', $god->user) }}">
                                        {{ $god->user->username }}
                                    </a>
                                @else
                                    Не указан
                                @endif
                            </p>
                            
                            @if($god->trashed())

                                <div class="alert alert-warning">
                                    Этот бог удалён
                                </div>

                                @can('restore-god', $god)
                                    <form action="{{ route('gods.restore', $god) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-success">Восстановить</button>
                                    </form>
                                @endcan

                                @can('force-delete-god', $god)
                                    <form action="{{ route('gods.forceDelete', $god) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger">Удалить навсегда</button>
                                    </form>
                                @endcan

                            @else

                                <div class="d-flex gap-2 mb-4">

                                    @can('update-god', $god)
                                        <a href="{{ route('gods.edit', $god) }}" class="btn btn-primary">
                                            Изменить
                                        </a>
                                    @endcan

                                    @can('delete-god', $god)
                                        <form action="{{ route('gods.destroy', $god) }}" method="POST"
                                            onsubmit="return confirm('Удалить этого бога?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">
                                                Удалить
                                            </button>
                                        </form>
                                    @endcan

                                </div>

                            @endif

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
</body>
</html>
