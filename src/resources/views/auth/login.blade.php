<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz@0,14..32;1,14..32&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
</head>

<body>
    <header class="header">
        <div class="header__container">
            <div class="header__logo">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('images/logo.svg') }}" alt="COACHTECH">
                </a>
            </div>
        </div>
    </header>

    <main>
        <div class="login__container">
            <div class="login__heading">
                <h2>ログイン</h2>
            </div>
            @if (session('error'))
                <p class="error__message">{{ session('error') }}</p>
            @endif
            <form class="login__form" action="{{ route('login.store') }}" method="POST">
                @csrf
                <div class="form__group">
                    <label class="form__label" for="email">メールアドレス</label>
                    <input class="form__input" type="email" name="email">
                    <p class="form__error">
                        @error('email')
                            {{ $message }}
                        @enderror
                    </p>
                </div>
                <div class="form__group">
                    <label class="form__label" for="password">パスワード</label>
                    <input class="form__input" type="password" name="password">
                    <p class="form__error">
                        @error('password')
                            {{ $message }}
                        @enderror
                    </p>
                </div>
                <div class="form__button">
                    <input class="form__button-submit" type="submit" value="ログインする">
                </div>
            </form>
            <div class="form__link">
                <a href="/register">会員登録はこちら</a>
            </div>
        </div>
    </main>
</body>

</html>
