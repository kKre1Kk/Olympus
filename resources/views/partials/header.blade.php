
<header class="site-header">
    <nav class="navbar navbar-expand-md navbar-dark">
        <div class="container flex-wrap">

            <a class="navbar-brand" href="{{ route('gods.index') }}">
                <span class="logo-icon" aria-hidden="true">Ω</span>
                <span>Olympus</span>
            </a>

            <div class="d-flex flex-wrap align-items-center gap-2">

                <a href="{{ route('gods.index') }}" class="btn header-more">
                    Главная
                </a>

                <a href="{{ route('users.index') }}" class="btn header-more">
                    Пользователи
                </a>

                <a href="{{ route('users.show', auth()->user()) }}" class="btn header-more">
                    Мой профиль
                </a>

                @can('admin')
                    <a href="{{ route('gods.trashed') }}" class="btn header-more">
                        Удалённые
                    </a>
                @endcan

                <a href="{{ route('gods.create') }}" class="btn header-more">
                    Добавить
                </a>

                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn header-more">
                        Выйти
                    </button>
                </form>

            </div>
        </div>
    </nav>
</header>
