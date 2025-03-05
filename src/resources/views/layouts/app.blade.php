<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz@0,14..32;1,14..32&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/default.css') }}">
    @yield('css')
</head>

<body>
    <div class="default">
        <header class="header">
            <div class="header__container">
                <div class="header__logo">
                    <a href="{{ route('home') }}">
                        <img src="{{ asset('images/logo.svg') }}" alt="COACHTECH">
                    </a>
                </div>
                <form class="header__form" action="{{ route('items.search') }}" method="GET">
                    <div class="header__search">
                        <input type="text" name="query" value="{{ request('query') }}" placeholder="なにをお探しですか？">
                    </div>
                </form>
                <nav class="header__nav">
                    @auth
                        <form class="logout-form" action="/logout" method="POST">
                            @csrf
                            <input class="logout-btn" type="submit" value="ログアウト">
                        </form>
                    @endauth
                    @guest
                        <a href="/login" class="btn login-btn">ログイン</a>
                    @endguest
                    <a href="{{ route('mypage') }}" class="btn mypage-btn">マイページ</a>
                    <a href="{{ route('items.create') }}" class="exhibit-btn">出品</a>
                </nav>
            </div>
        </header>

        <main>
            @yield('content')
        </main>
    </div>
</body>

</html>
