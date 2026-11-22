<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $god->name }} — Olympus</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    @include('partials.header')

    <main class="page-main">
        <div class="container">
            <article class="card god-card">
                <div class="row g-0">
                    @if ($god->image_path)
                        <div class="col-md-4">
                            <img src="{{ asset('storage/' . $god->image_path) }}"
                                 class="img-fluid w-100 h-100"
                                 style="object-fit: cover;"
                                 alt="{{ $god->name }}">
                        </div>
                    @endif

                    <div class="{{ $god->image_path ? 'col-md-8' : 'col-12' }}">
                        <div class="card-body p-4">
                            <h1 class="card-title">{{ $god->name }}</h1>
                            <h5>{{ $god->sphere }}</h5>
                            <p>{{ $god->description }}</p>
                            <p>Дата добавления: {{ $god->recorded_at ?? 'Не указана' }}</p>
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

                            @if ($god->trashed())
                                <div class="alert alert-warning">Этот объект находится в удалённых.</div>

                                @can('restore-god', $god)
                                    <form action="{{ route('gods.restore', $god) }}" method="POST" class="mb-2">
                                        @csrf
                                        <button class="btn btn-success">Восстановить</button>
                                    </form>
                                @endcan

                                @can('force-delete-god', $god)
                                    <form action="{{ route('gods.forceDelete', $god) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger">Удалить навсегда</button>
                                    </form>
                                @endcan
                            @else
                                <div class="d-flex flex-wrap gap-2 mb-4">
                                    @can('update-god', $god)
                                        <a href="{{ route('gods.edit', $god) }}" class="btn btn-primary">
                                            Изменить
                                        </a>
                                    @endcan

                                    @can('delete-god', $god)
                                        <form action="{{ route('gods.destroy', $god) }}" method="POST"
                                              onsubmit="return confirm('Удалить этого бога?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Удалить</button>
                                        </form>
                                    @endcan
                                </div>
                            @endif

                            <h2 class="h3">Дополнительная информация</h2>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item"><strong>Символы:</strong> {{ $god->symbols ?: '—' }}</li>
                                <li class="list-group-item"><strong>Родители:</strong> {{ $god->parents ?: '—' }}</li>
                                <li class="list-group-item"><strong>Братья и сёстры:</strong> {{ $god->siblings ?: '—' }}</li>
                                <li class="list-group-item"><strong>Супруг:</strong> {{ $god->spouse ?: '—' }}</li>
                                <li class="list-group-item"><strong>Дети:</strong> {{ $god->children ?: '—' }}</li>
                                <li class="list-group-item"><strong>Храм:</strong> {{ $god->temple ?: '—' }}</li>
                                <li class="list-group-item"><strong>Миф:</strong> {{ $god->myth ?: '—' }}</li>
                            </ul>

                            <a href="{{ route('gods.index') }}" class="btn btn-secondary mt-4">
                                К списку богов
                            </a>
                        </div>
                    </div>
                </div>
            </article>
        </div>
    </main>

    @include('partials.footer')
    <script src="{{ asset('js/app.js') }}" defer></script>
</body>
</html>
