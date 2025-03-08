<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>メール認証</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz@0,14..32;1,14..32&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth/verify-email.css') }}">
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
        <div class="verify-email__container">
            <div class="verify-email__content">
                <p>登録していただいたメールアドレスに認証メールを送付しました。</p>
                <p>メール認証を完了してください。</p>

                @if (session('message'))
                    <p class="alert-resend">{{ session('message') }}</p>
                @endif

                <form action="{{ route('verification.resend') }}" method="POST">
                    @csrf
                    <button class="resend-email-btn" type="submit">認証メールを再送する</button>
                </form>
            </div>
        </div>
    </main>
</body>

</html>
