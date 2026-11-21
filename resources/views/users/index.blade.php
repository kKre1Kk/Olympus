
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
    <div class="container">

        <h1 class="main-title">Пользователи Olympus</h1>

        <div class="list-group">
            @foreach($users as $user)
                <a href="{{ route('users.show', $user) }}"
                   class="list-group-item list-group-item-action">

                    <strong>{{ $user->name }}</strong>

                    <span class="text-muted">
                        ({{ $user->username }})
                    </span>

                    <span class="badge bg-primary">
                        Богов: {{ $user->gods_count }}
                    </span>

                </a>
            @endforeach
        </div>

    </div>
</main>

</body>
</html>
