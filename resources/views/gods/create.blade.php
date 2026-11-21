
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить бога — Olympus</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    @include('partials.header')

    <main class="page-main">
        <div class="container">
            <h1 class="main-title">Добавить бога</h1>
            @include('gods.form')
        </div>
    </main>

    <footer class="site-footer">
        <div class="container">
            <span>Яковенко Кирилл. Лабораторная работа №3</span>
        </div>
    </footer>
</body>
</html>
