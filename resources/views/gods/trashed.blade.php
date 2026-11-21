
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
    
    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-wide-4 g-4 justify-content-center god-grid">

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

                        @if($god->badge)
                            <span class="god-card__badge">
                                {{ $god->badge }}
                            </span>
                        @endif
                    </div>

                    <div class="card-body">

                        <h2 class="card-title">{{ $god->name }}</h2>

                        <p class="god-card__sphere">
                            {{ $god->sphere }}
                        </p>

                        <p class="card-text">
                            Владелец: {{ $god->user?->username ?? 'Не указан' }}
                        </p>

                        <a href="{{ route('gods.show', $god) }}"
                        class="btn btn-primary">
                            Подробнее
                        </a>

                        <div class="d-flex flex-wrap gap-2 mt-3">

                            <form action="{{ route('gods.restore', $god) }}"
                                method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    Восстановить
                                </button>
                            </form>

                            <form action="{{ route('gods.forceDelete', $god) }}"
                                method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    Удалить навсегда
                                </button>
                            </form>

                        </div>

                    </div>
                </article>
            </div>

        @empty

            <p>Удалённых богов пока нет.</p>

        @endforelse

    </div>

</main>

</body>
</html>
