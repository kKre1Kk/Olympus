@props(['god', 'variant' => 'catalog'])

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
            @if ($variant === 'profile')
                <h3 class="card-title">{{ $god->name }}</h3>
            @else
                <h2 class="card-title">{{ $god->name }}</h2>
            @endif

            <p class="god-card__sphere">{{ $god->sphere }}</p>

            @if ($variant === 'catalog' || $variant === 'feed')
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

                @if ($variant === 'feed')
                    <small class="text-muted mb-2">
                        Добавлено: {{ $god->created_at->format('d.m.Y H:i') }}
                    </small>
                @endif

                <p class="card-text">{{ $god->description }}</p>
            @elseif ($variant === 'trashed')
                <p>Создатель: {{ $god->user?->username ?? 'Не указан' }}</p>
            @endif

            @if ($variant === 'profile' && $god->trashed())
                <span class="badge bg-danger mb-2">Удалён</span>

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
            @elseif ($variant === 'trashed')
                <a href="{{ route('gods.show', $god) }}" class="btn btn-primary">
                    Подробнее
                </a>

                <div class="d-flex flex-wrap gap-2 mt-3">
                    <form action="{{ route('gods.restore', $god) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success">Восстановить</button>
                    </form>

                    <form action="{{ route('gods.forceDelete', $god) }}" method="POST"
                          onsubmit="return confirm('Удалить объект без возможности восстановления?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Удалить навсегда</button>
                    </form>
                </div>
            @else
                <a href="{{ route('gods.show', $god) }}" class="btn btn-primary mt-auto">
                    Подробнее
                </a>
            @endif
        </div>
    </article>
</div>
