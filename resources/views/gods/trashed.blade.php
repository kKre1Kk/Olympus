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
                    <x-god-card :god="$god" variant="trashed" />
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
