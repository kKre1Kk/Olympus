<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Пользователи — Olympus</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    @include('partials.header')

    <main class="page-main">
        <section class="container" aria-labelledby="usersTitle">
            <h1 class="main-title" id="usersTitle">Пользователи Olympus</h1>

            <div class="list-group">
                @forelse ($users as $user)
                    <a href="{{ route('users.show', $user) }}"
                       class="list-group-item list-group-item-action d-flex flex-wrap align-items-center gap-2">
                        <strong>{{ $user->name }}</strong>
                        <span class="text-muted">({{ $user->username }})</span>
                        <span class="badge bg-primary ms-sm-auto">
                            Объектов: {{ $user->gods_count }}
                        </span>
                    </a>
                @empty
                    <p>Пользователей пока нет.</p>
                @endforelse
            </div>
        </section>
    </main>

    @include('partials.footer')
    <script src="{{ asset('js/app.js') }}" defer></script>
</body>
</html>
