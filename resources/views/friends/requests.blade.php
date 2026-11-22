<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Заявки в друзья — Olympus</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    @include('partials.header')

    <main class="page-main">
        <section class="container">
            <h1 class="main-title mb-4">Заявки в друзья</h1>

            <h2 class="h3 mb-3">Входящие ({{ $incoming->count() }})</h2>
            <div class="list-group mb-5">
                @forelse ($incoming as $friendRequest)
                    <div class="list-group-item d-flex flex-wrap gap-3 align-items-center justify-content-between">
                        <a href="{{ route('users.show', $friendRequest->sender) }}">
                            {{ $friendRequest->sender->name }} ({{ $friendRequest->sender->username }})
                        </a>
                        <div class="d-flex flex-wrap gap-2">
                            <form action="{{ route('friend-requests.accept', $friendRequest) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success">Принять</button>
                            </form>
                            <form action="{{ route('friend-requests.destroy', $friendRequest) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger">Отклонить</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="list-group-item text-muted">Новых заявок нет.</div>
                @endforelse
            </div>

            <h2 class="h3 mb-3">Исходящие ({{ $outgoing->count() }})</h2>
            <div class="list-group">
                @forelse ($outgoing as $friendRequest)
                    <div class="list-group-item d-flex flex-wrap gap-3 align-items-center justify-content-between">
                        <div>
                            <a href="{{ route('users.show', $friendRequest->receiver) }}">
                                {{ $friendRequest->receiver->name }} ({{ $friendRequest->receiver->username }})
                            </a>
                            <span class="text-muted ms-2">Ожидает ответа</span>
                        </div>
                        <form action="{{ route('friend-requests.destroy', $friendRequest) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-secondary">Отменить</button>
                        </form>
                    </div>
                @empty
                    <div class="list-group-item text-muted">Вы ещё не отправляли заявки.</div>
                @endforelse
            </div>
        </section>
    </main>

    @include('partials.footer')
    <script src="{{ asset('js/app.js') }}" defer></script>
</body>
</html>
