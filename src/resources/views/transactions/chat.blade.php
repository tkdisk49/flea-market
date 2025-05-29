@extends('layouts/app')

@section('title')
    取引チャット
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/transactions/chat.css') }}">
@endsection

@section('content')
    <div class="chat__container">
        <div class="chat__sidebar">
            <h2 class="chat__sidebar-title">その他の取引</h2>
            {{-- 他の取引のリストを表示 --}}
            <ul class="chat__sidebar-list">
                @foreach ($otherTransactions as $otherTransaction)
                    <li class="chat__sidebar-item">
                        <a href="{{ route('transaction.chat', ['id' => $otherTransaction->id]) }}" class="chat__sidebar-link">
                            {{ $otherTransaction->item->name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="chat__main">
            <div class="chat__header">
                <h1 class="chat__title">{{ $partner->name }}さんとの取引画面</h1>
                {{-- Todo: 購入者にのみ表示され、取引を完了させ評価モーダルを表示するボタン --}}
                <button class="chat__complete-button">取引を完了する</button>
            </div>

            <div class="chat__item-info">
                <div class="chat__item-image">
                    <img src="{{ asset('storage/' . $transaction->item->image) }}" alt="{{ $transaction->item->name }}">
                </div>
                <div class="chat__item-content">
                    <h2 class="chat__item-name">{{ $transaction->item->name }}</h2>
                    <p class="chat__item-price">
                        <span>¥</span> {{ number_format($transaction->item->price) }}
                    </p>
                </div>
            </div>

            <div class="chat__messages">
                {{-- メッセージのリストを表示 --}}
                @foreach ($partnerMessages as $message)
                    <div class="chat__message chat__message--received">
                        <div class="chat__user-info">
                            {{-- 相手のプロフィール画像を表示 --}}
                            <img src="{{ $partner->profile && $partner->profile->image ? asset('storage/' . $partner->profile->image) : asset('images/default.jpg') }}"
                                alt="プロフィール画像" class="chat__user-image">
                            <span class="chat__user-name">{{ $partner->name }}</span>
                        </div>
                        {{-- 相手のメッセージ内容を表示 --}}
                        <p class="chat__message-text">
                            {{ $message->content }}
                            @if ($message->image)
                                <img src="{{ asset('storage/' . $message->image) }}" alt="メッセージ画像"
                                    class="chat__message-image">
                            @endif
                        </p>
                    </div>
                @endforeach

                @foreach ($userMessages as $message)
                    <div class="chat__message chat__message--sent">
                        <div class="chat__user-info">
                            {{-- 自分のプロフィール画像を表示 --}}
                            <img src="{{ $user->profile && $user->profile->image ? asset('storage/' . $user->profile->image) : asset('images/default.jpg') }}"
                                alt="プロフィール画像" class="chat__user-image">
                            <span class="chat__user-name">{{ $user->name }}</span>
                        </div>
                        {{-- 自分のメッセージ内容を表示 --}}
                        <p class="chat__message-text">
                            {{ $message->content }}
                            @if ($message->image)
                                <img src="{{ asset('storage/' . $message->image) }}" alt="メッセージ画像"
                                    class="chat__message-image">
                            @endif
                        </p>
                    </div>
                @endforeach
            </div>

            <div class="chat__input">
                <form action="{{ route('transaction.chat.store', ['id' => $transaction->id]) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @if ($errors->any())
                        <div class="chat__error">
                            <p>{{ $errors->first() }}</p>
                        </div>
                    @endif
                    <textarea name="content" class="chat__input-textarea" placeholder="取引メッセージを記入して下さい"></textarea>
                    <input type="file" name="image" id="image" class="chat__input-file"
                        accept="image/png, image/jpeg" value="画像を追加">
                    <label for="image" class="chat__input-label">画像を追加</label>
                    <button type="submit" class="chat__send-button">送信</button>
                </form>
            </div>
        </div>
    </div>
@endsection
