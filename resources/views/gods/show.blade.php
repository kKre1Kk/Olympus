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

            <section class="mt-5" aria-labelledby="commentsTitle">
                <h2 class="h3 mb-4" id="commentsTitle">Комментарии ({{ $god->comments->count() }})</h2>

                @forelse ($god->comments as $comment)
                    @php($isFriendComment = in_array($comment->user_id, $friendIds))

                    <article class="card mb-3 {{ $isFriendComment ? 'border-warning border-2 bg-warning-subtle' : '' }}">
                        <div class="card-body">
                            <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                <strong>
                                    <a href="{{ route('users.show', $comment->user) }}">
                                        {{ $comment->user->name }}
                                    </a>
                                </strong>
                                @if ($isFriendComment)
                                    <span class="badge bg-warning text-dark">★ Друг</span>
                                @endif
                                <small class="text-muted ms-sm-auto">
                                    {{ $comment->created_at->format('d.m.Y H:i') }}
                                </small>
                            </div>
                            <p class="mb-0" style="white-space: pre-wrap;">{{ $comment->body }}</p>
                        </div>
                    </article>
                @empty
                    <p class="text-muted">Комментариев пока нет.</p>
                @endforelse

                @unless ($god->trashed())
                    <form action="{{ route('comments.store', $god) }}" method="POST" class="mt-4">
                        @csrf
                        <label for="commentBody" class="form-label fw-bold">Оставить комментарий</label>
                        <textarea id="commentBody" name="body" rows="3" maxlength="2000"
                                  class="form-control @error('body') is-invalid @enderror"
                                  required>{{ old('body') }}</textarea>
                        @error('body')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <button type="submit" class="btn btn-primary mt-3">Отправить комментарий</button>
                    </form>
                @endunless
            </section>
        </div>
    </main>

    @include('partials.footer')
    <script src="{{ asset('js/app.js') }}" defer></script>
</body>
</html>
