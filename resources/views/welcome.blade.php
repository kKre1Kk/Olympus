<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Пять богов Древней Греции — адаптивная галерея Bootstrap.">
        <title>Olympus — Боги Древней Греции</title>
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    </head>
    <body id="top">
        <header class="site-header">
            <nav class="navbar navbar-expand-md navbar-dark" aria-label="Главная навигация">
                <div class="container">
                    <a class="navbar-brand" href="#top">
                        <span class="logo-icon" aria-hidden="true">Ω</span>
                        <span>Olympus</span>
                    </a>
                    <a href="{{ route('gods.create') }}" class="btn header-more">Добавить</a>
                </div>
            </nav>
        </header>

        <main class="page-main">
            <section class="container" id="gods" aria-labelledby="mainTitle">
                <h1 class="main-title" id="mainTitle">Боги Древней Греции</h1>
                <p class="main-subtitle">Главные божества древнегреческой мифологии</p>

                <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-wide-4 g-4 justify-content-center god-grid">
                    @foreach ($gods as $god)
                        <div class="col">
                            <article class="card god-card">
                                <div class="god-card__figure">
                                    <img class="card-img-top img-fluid god-card__image" src="{{ asset('storage/' . $god->image_path) }}" alt="{{ $god->name }}">
                                    <span class="god-card__badge">{{ $god->badge }}</span>
                                </div>
                                <div class="card-body">
                                    <h2 class="card-title">{{ $god->name }}</h2>
                                    <p class="god-card__sphere">{{ $god->sphere }}</p>
                                    <p class="card-text">{{ $god->description }}</p>
                                    <a href="{{ route('gods.show', $god) }}" class="btn btn-primary mt-auto">Подробнее</a>
                                </div>
                            </article>
                        </div>
                    @endforeach
                </div>
            </section>
        </main>

        <footer class="site-footer">
            <div class="container d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-3">
                <span>Яковенко Кирилл. Лабораторная работа №3</span>
                <div class="d-flex gap-2 align-items-center" role="group" aria-label="Ссылки на мессенджеры">
                    <a class="social-link" href="https://telegram.org/" target="_blank" aria-label="Открыть официальный сайт Telegram" title="Telegram">
                        <img class="social-icon social-telegram img-fluid" src="{{ asset('storage/gods/telegram.png') }}" alt="Telegram" width="29" height="29">
                    </a>
                    <a class="social-link" href="https://max.ru/" target="_blank" aria-label="Открыть официальный сайт MAX" title="MAX">
                        <img class="social-icon social-max img-fluid" src="{{ asset('storage/gods/MAX.png') }}" alt="MAX" width="29" height="29">
                    </a>
                </div>
            </div>
        </footer>

        <div class="modal fade god-modal" role="dialog" id="godModal" tabindex="-1" aria-labelledby="godModalTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title fs-4" id="godModalTitle">Информация о боге</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12 col-lg-5">
                                <img class="img-fluid god-modal__image" alt="" width="960" height="1393">
                            </div>
                            <div class="col-12 col-lg-7">
                                <p class="god-modal__sphere mb-2"></p>
                                <p class="god-modal__description"></p>
                                <h3 class="fs-6 fw-bold">Факты</h3>
                                <ul class="list-group list-group-flush" id="godModalFacts"></ul>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between gap-2">
                        <span class="small god-modal__position" aria-live="polite"></span>
                        <span class="small text-secondary d-none d-sm-inline">Переключение: ← →</span>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="toast-container position-fixed bottom-0 end-0 p-3">
            <div id="downloadToast" class="toast" role="status" aria-live="polite" aria-atomic="true">
                <div class="toast-header">
                    <i class="fa-solid fa-spinner loading-icon me-2" aria-hidden="true"></i>
                    <strong class="me-auto">Olympus</strong>
                    <small>Уведомление</small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Закрыть"></button>
                </div>
                <div class="toast-body">На данный момент функция загрузки недоступна.</div>
            </div>
        </div>
        <script src="{{ asset('js/app.js') }}" defer></script>
    </body>
</html>
