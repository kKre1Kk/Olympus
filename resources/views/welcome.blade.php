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
                    <x-god-card :god="$god" />
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
