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
        <section class="container" aria-labelledby="profileTitle">
            <h1 class="main-title" id="profileTitle">{{ $user->name }}</h1>
            <p class="main-subtitle">
                Username: {{ $user->username }}

</p>

            @if (auth()->id() !== $user->id)
                @if ($isFriend)
                    <form action="{{ route('friends.destroy', $user) }}" method="POST" class="mb-4">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger">Удалить из друзей</button>
                    </form>
                @elseif ($incomingRequest)
                    <div class="d-flex flex-wrap gap-2 mb-4">
                        <form action="{{ route('friend-requests.accept', $incomingRequest) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success">Принять заявку в друзья</button>
                        </form>
                        <form action="{{ route('friend-requests.destroy', $incomingRequest) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger">Отклонить заявку</button>
                        </form>
                    </div>
                @elseif ($outgoingRequest)
                    <form action="{{ route('friend-requests.destroy', $outgoingRequest) }}" method="POST" class="mb-4">
                        @csrf
                        @method('DELETE')
                        <span class="text-muted me-2">Заявка отправлена, ожидается ответ.</span>
                        <button type="submit" class="btn btn-outline-secondary">Отменить заявку</button>
                    </form>
                @else
                    <form action="{{ route('friends.store', $user) }}" method="POST" class="mb-4">
                        @csrf
                        <button type="submit" class="btn btn-primary">Отправить заявку в друзья</button>
                    </form>
                @endif
            @endif

            <section class="mb-5" aria-labelledby="friendsTitle">
                <h2 class="h3 mb-3" id="friendsTitle">Друзья ({{ $friends->count() }})</h2>
                @forelse ($friends as $friend)
                    <a href="{{ route('users.show', $friend) }}"
                       class="badge bg-primary text-decoration-none me-2 mb-2 p-2">
                        {{ $friend->name }} ({{ $friend->username }})
                    </a>
                @empty
                    <p class="text-muted">Пока нет друзей.</p>
                @endforelse
            </section>

            <h2 class="h3 mb-4">Объекты пользователя</h2>

            <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-4">
                @forelse ($gods as $god)
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
                                <h3 class="card-title">{{ $god->name }}</h3>
                                <p class="god-card__sphere">{{ $god->sphere }}</p>

                                @if ($god->trashed())
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
                                @else
                                    <a href="{{ route('gods.show', $god) }}" class="btn btn-primary mt-auto">
                                        Подробнее
                                    </a>
                                @endif
                            </div>
                        </article>
                    </div>
                @empty
                    <div class="col-12">
                        <p>У пользователя пока нет объектов.</p>
                    </div>
                @endforelse
            </div>

        </section>
    </main>

    @include('partials.footer')
    <script src="{{ asset('js/app.js') }}" defer></script>
</body>
</html>
