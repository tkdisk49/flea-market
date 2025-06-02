@extends('layouts/app')

@php
    use App\Models\Transaction;
@endphp

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
            @if (session('error'))
                <div class="session__error-message">
                    <p>{{ session('error') }}</p>
                </div>
            @endif
            <div class="chat__header">
                <div class="header__user-info">
                    <img src="{{ $partner->profile && $partner->profile->image ? asset('storage/' . $partner->profile->image) : asset('images/default.jpg') }}"
                        alt="プロフィール画像" class="header__user-image">
                    <h1 class="header__title">{{ $partner->name }}さんとの取引画面</h1>
                </div>

                @if ($transaction->status === Transaction::STATUS_STARTED && $transaction->buyer_id === $user->id)
                    <label for="modal-toggle" class="chat__complete-button">取引を完了する</label>
                    <input type="checkbox" id="modal-toggle" class="modal-toggle" hidden>
                @elseif ($transaction->status === Transaction::STATUS_BUYER_REVIEWED && $transaction->seller_id === $user->id)
                    <input type="checkbox" id="modal-toggle" class="modal-toggle" hidden checked>
                @endif
                {{-- モーダル画面 --}}
                <div class="modal">
                    <div class="modal__content">
                        <div class="modal__title">
                            <h2>取引が完了しました。</h2>
                        </div>

                        <div class="modal__rating">
                            <p>今回の取引相手はどうでしたか？</p>
                            <form action="{{ route('transaction.review.store', ['id' => $transaction->id]) }}"
                                method="POST">
                                @csrf
                                <div class="modal__rating-stars">
                                    @for ($i = 5; $i >= 1; $i--)
                                        <input type="radio" id="star{{ $i }}" name="rating"
                                            value="{{ $i }}">
                                        <label for="star{{ $i }}">&#9733;</label>
                                    @endfor
                                </div>
                                <div class="modal__rating-submit">
                                    <button type="submit" class="modal__rating-button">送信する</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
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
                @foreach ($messages as $message)
                    @if ($message->user_id !== $user->id)
                        <div class="chat__message chat__message--received">
                            <div class="chat__user-info">
                                <img src="{{ $partner->profile && $partner->profile->image ? asset('storage/' . $partner->profile->image) : asset('images/default.jpg') }}"
                                    alt="プロフィール画像" class="chat__user-image">
                                <span class="chat__user-name">{{ $partner->name }}</span>
                            </div>

                            <p class="chat__message-text">
                                {!! nl2br(e($message->content)) !!}
                            </p>
                            @if ($message->image)
                                <div class="chat__message-image-container">
                                    <img src="{{ asset('storage/' . $message->image) }}" alt="メッセージ画像"
                                        class="chat__message-image">
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="chat__message chat__message--sent">
                            <div class="chat__user-info chat__user-info--self">
                                <span class="chat__user-name">{{ $user->name }}</span>
                                <img src="{{ $user->profile && $user->profile->image ? asset('storage/' . $user->profile->image) : asset('images/default.jpg') }}"
                                    alt="プロフィール画像" class="chat__user-image">
                            </div>
                            @if (isset($editMessage) && $editMessage->id === $message->id)
                                <div class="chat__message-container">
                                    <form
                                        action="{{ route('transaction.chat.update', ['id' => $transaction->id, 'message' => $message->id]) }}"
                                        method="POST" class="chat__edit-form">
                                        @csrf
                                        @method('PATCH')
                                        <textarea name="content" class="chat__input-textarea">{{ old('content', $editMessage->content) }}</textarea>
                                        <button type="submit" class="chat__edit-submit">更新</button>
                                    </form>
                                </div>
                            @else
                                <div class="chat__message-container">
                                    <p class="chat__message-text">
                                        {!! nl2br(e($message->content)) !!}
                                    </p>
                                    @if ($message->image)
                                        <div class="chat__message-image-container">
                                            <img src="{{ asset('storage/' . $message->image) }}" alt="メッセージ画像"
                                                class="chat__message-image">
                                        </div>
                                    @endif
                                    <div class="chat__button-group">
                                        <a href="{{ route('transaction.chat.edit', ['id' => $transaction->id, 'message' => $message->id]) }}"
                                            class="chat__edit-link">編集</a>
                                        <form
                                            action="{{ route('transaction.chat.destroy', ['id' => $transaction->id, 'message' => $message->id]) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="chat__delete-button">削除</button>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                @endforeach
            </div>

            <div class="chat__input">
                @if ($errors->any())
                    <div class="chat__error">
                        <p>{{ $errors->first() }}</p>
                    </div>
                @endif
                <form action="{{ route('transaction.chat.store', ['id' => $transaction->id]) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <textarea name="content" id="chat-input" class="chat__input-textarea" placeholder="取引メッセージを記入して下さい">{{ old('content') }}</textarea>
                    <input type="file" name="image" id="image" class="chat__input-file"
                        accept="image/png, image/jpeg" value="画像を追加">
                    <label for="image" class="chat__input-label">画像を追加</label>
                    <button type="submit" class="chat__send-button">
                        <img src="{{ asset('images/inputbutton.png') }}" alt="送信">
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        window.transactionId = @json($transaction->id);
    </script>
    <script src="{{ asset('js/chat.js') }}"></script>
@endsection
