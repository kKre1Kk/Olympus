<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Боги Древней Греции — галерея Olympus.">
    <title>Olympus — Боги Древней Греции</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body id="top">
    @include('partials.header')

    <main class="page-main">
        <section class="container" id="gods" aria-labelledby="mainTitle">
            <h1 class="main-title" id="mainTitle">Боги Древней Греции</h1>
            <p class="main-subtitle">Главные божества древнегреческой мифологии</p>

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
                                    @if ($god->user)
                                        <a href="{{ route('users.show', $god->user) }}">
                                            {{ $god->user->username }}
                                        </a>
                                    @else
                                        Не указан
                                    @endif
                                </p>
                                <p class="card-text">{{ $god->description }}</p>
                                <a href="{{ route('gods.show', $god) }}" class="btn btn-primary mt-auto">
                                    Подробнее
                                </a>
                            </div>
                        </article>
                    </div>
                @empty
                    <p>Пока нет добавленных объектов.</p>
                @endforelse
            </div>
        </section>
    </main>

    @include('partials.footer')
    <script src="{{ asset('js/app.js') }}" defer></script>
</body>
</html>
