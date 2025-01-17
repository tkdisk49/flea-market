<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/default.css') }}">
    @yield('css')
    <title>COATHTECH</title>
</head>

<body>
    <div class="default">
        <div class="header">
            <img src="{{ asset('img/logo.svg') }}" class="header__logo" alt="">
            <input type="text" name="keyword" class="header__search-form" placeholder="なにをお探しですか？" value="{{ request('keyword') }}">
            @yield('link')
        </div>
    </div>
    <div class="content">
        @yield('content')
    </div>
</body>
</html>