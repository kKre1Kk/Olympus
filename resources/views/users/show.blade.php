
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $user->name }} — Olympus</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>

@include('partials.header')

<main class="page-main">
    <div class="container">

        <h1 class="main-title">{{ $user->name }}</h1>

        <p>Username: {{ $user->username }}</p>

        <h2 class="mt-4 mb-4">Боги пользователя</h2>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-4">

            @forelse($gods as $god)

                <div class="col">
                    <article class="card god-card">

                        <div class="god-card__figure">
                            @if($god->image_path)
                                <img
                                    class="card-img-top img-fluid god-card__image"
                                    src="{{ asset('storage/' . $god->image_path) }}"
                                    alt="{{ $god->name }}"
                                >
                            @endif

                            <span class="god-card__badge">
                                {{ $god->badge }}
                            </span>
                        </div>

                        <div class="card-body">

                            <h3 class="card-title">{{ $god->name }}</h3>

                            <p class="god-card__sphere">
                                {{ $god->sphere }}
                            </p>

                            @if($god->trashed())

                                <span class="badge bg-danger">Удалён</span>

                                @can('restore-god', $god)
                                    <form action="{{ route('gods.restore', $god) }}"
                                          method="POST" class="mt-2">
                                        @csrf
                                        <button class="btn btn-success">
                                            Восстановить
                                        </button>
                                    </form>
                                @endcan

                                @can('force-delete-god', $god)
                                    <form action="{{ route('gods.forceDelete', $god) }}"
                                          method="POST" class="mt-2">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger">
                                            Удалить навсегда
                                        </button>
                                    </form>
                                @endcan

                            @else

                                <a href="{{ route('gods.show', $god) }}"
                                   class="btn btn-primary mt-auto">
                                    Подробнее
                                </a>

                            @endif

                        </div>
                    </article>
                </div>

            @empty

                <p>У пользователя пока нет богов.</p>

            @endforelse

        </div>

    </div>
</main>

</body>
</html>
