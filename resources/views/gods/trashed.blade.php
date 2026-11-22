<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Удалённые боги — Olympus</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    @include('partials.header')

    <main class="page-main">
        <section class="container" aria-labelledby="trashedTitle">
            <h1 class="main-title" id="trashedTitle">Удалённые объекты</h1>
            <p class="main-subtitle">Восстановление или окончательное удаление доступно администратору.</p>

            <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-wide-4 g-4 justify-content-center god-grid">
                @forelse ($gods as $god)
                    <div class="col">
                        <article class="card god-card">
                            <div class="god-card__figure">
                                @if ($god->image_path)
                                    <img class="card-img-top img-fluid god-card__image"
                                         src="{{ asset('storage/' . $god->image_path) }}"
                                         alt="{{ $god->name }}">
                                @endif
                                <span class="god-card__badge">{{ $god->badge }}</span>
                            </div>

                            <div class="card-body">
                                <h2 class="card-title">{{ $god->name }}</h2>
                                <p class="god-card__sphere">{{ $god->sphere }}</p>
                                <p>Создатель: {{ $god->user?->username ?? 'Не указан' }}</p>

                                <a href="{{ route('gods.show', $god) }}" class="btn btn-primary">
                                    Подробнее
                                </a>

                                <div class="d-flex flex-wrap gap-2 mt-3">
                                    <form action="{{ route('gods.restore', $god) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success">Восстановить</button>
                                    </form>

                                    <form action="{{ route('gods.forceDelete', $god) }}" method="POST"
                                          onsubmit="return confirm('Удалить объект без возможности восстановления?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Удалить навсегда</button>
                                    </form>
                                </div>
                            </div>
                        </article>
                    </div>
                @empty
                    <div class="col-12">
                        <p class="text-center">Удалённых объектов пока нет.</p>
                    </div>
                @endforelse
            </div>
        </section>
    </main>

    @include('partials.footer')
    <script src="{{ asset('js/app.js') }}" defer></script>
</body>
</html>
