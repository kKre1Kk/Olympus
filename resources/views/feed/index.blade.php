<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Лента друзей — Olympus</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    @include('partials.header')

    <main class="page-main">
        <section class="container" aria-labelledby="feedTitle">
            <h1 class="main-title" id="feedTitle">Лента друзей</h1>
            <p class="main-subtitle">Новые боги, добавленные вашими друзьями.</p>

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
                                <p>
                                    Автор:
                                    <a href="{{ route('users.show', $god->user) }}">
                                        {{ $god->user->username }}
                                    </a>
                                </p>
                                <small class="text-muted mb-2">
                                    Добавлено: {{ $god->created_at->format('d.m.Y H:i') }}
                                </small>
                                <p class="card-text">{{ $god->description }}</p>
                                <a href="{{ route('gods.show', $god) }}" class="btn btn-primary mt-auto">
                                    Подробнее
                                </a>
                            </div>
                        </article>
                    </div>
                @empty
                    <div class="col-12 text-center">
                        <p>В ленте пока нет новых объектов друзей.</p>
                        <a href="{{ route('users.index') }}" class="btn btn-primary">
                            Найти друзей
                        </a>
                    </div>
                @endforelse
            </div>
        </section>
    </main>

    @include('partials.footer')
    <script src="{{ asset('js/app.js') }}" defer></script>
</body>
</html>
