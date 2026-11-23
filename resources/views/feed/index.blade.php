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
                    <x-god-card :god="$god" variant="feed" />
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
