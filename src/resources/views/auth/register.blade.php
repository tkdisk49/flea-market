<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>会員登録</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz@0,14..32;1,14..32&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth/register.css') }}">
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
        <div class="register__container">
            @if (session('error'))
                <div class="session__error-message">
                    {{ session('error') }}
                </div>
            @endif
            <div class="register__heading">
                <h2>会員登録</h2>
            </div>
            <form action="/register" class="register__form" method="POST">
                @csrf
                <div class="form__group">
                    <label class="form__label" for="name">ユーザー名</label>
                    <input class="form__input" type="text" name="name" value="{{ old('name') }}">
                    <p class="form__error">
                        @error('name')
                            {{ $message }}
                        @enderror
                    </p>
                </div>
                <div class="form__group">
                    <label class="form__label" for="email">メールアドレス</label>
                    <input class="form__input" type="email" name="email" value="{{ old('email') }}">
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
                        @if ($errors->has('password'))
                            {{ $errors->first('password') }}
                        @endif
                    </p>
                </div>
                <div class="form__group">
                    <label class="form__label" for="password_confirmation">確認用パスワード</label>
                    <input class="form__input" type="password" name="password_confirmation">
                </div>
                <div class="form__button">
                    <input class="form__button-submit" type="submit" value="登録する">
                </div>
            </form>
            <div class="form__link">
                <a href="/login">ログインはこちら</a>
            </div>
        </div>
    </main>
</body>

</html>
