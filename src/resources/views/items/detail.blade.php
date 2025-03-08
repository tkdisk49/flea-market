@extends('layouts/app')

@section('title')
    {{ $item->name }}
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/items/detail.css') }}">
@endsection

@section('content')
    @if (session('error'))
        <div class="session__error-message">
            {{ session('error') }}
        </div>
    @endif
    <div class="detail__container">
        <div class="detail__item-image">
            <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}">
        </div>

        <div class="detail__item-contents">
            <h2 class="item__name">{{ $item->name }}</h2>
            <p class="item__brand">ブランド名: {{ $item->brand ?? 'なし' }}</p>

            <p class="item__price">
                ¥<span class="item__price-value">{{ number_format($item->price ?? 0) }}</span> (税込)
            </p>

            <div class="detail__buttons">
                <form action="{{ route('like.toggle', ['id' => $item->id]) }}" method="POST">
                    @csrf
                    <div class="like__icon-count">
                        <button class="like__button-submit" type="submit">
                            <img class="like__icon {{ $isLiked ? 'liked' : '' }}" src="{{ asset('images/not_liked.png') }}"
                                alt="いいね">
                        </button>
                        <p class="like__count">{{ $likeCount }}</p>
                    </div>
                </form>

                <div class="comment__icon-count">
                    <img class="comment__icon" src="{{ asset('images/comment.png') }}" alt="コメント">
                    <p class="comment__count">{{ $commentCount }}</p>
                </div>
            </div>

            <div class="detail__purchase">
                @if ($item->status === 1)
                    <a href="{{ route('purchase.show', ['id' => $item->id]) }}" class="purchase__button">購入手続きへ</a>
                @elseif ($item->status === 2)
                    <p class="purchase__sold">売り切れ</p>
                @endif
            </div>

            <div class="detail__description">
                <h3 class="description__title">商品説明</h3>
                <p class="description__content">{{ $item->description }}</p>
            </div>

            <div class="detail__info">
                <h3 class="info__title">商品の情報</h3>
                <table class="info__table">
                    <tr class="info__row">
                        <th class="info__category-title">カテゴリー</th>
                        @foreach ($item->categories as $category)
                            <td class="info__category-content">{{ $category->content }}</td>
                        @endforeach
                    </tr>

                    <tr class="info__row">
                        <th class="info__condition-title">商品の状態</th>
                        <td class="info__condition-content">
                            @if ($item->condition == 1)
                                良好
                            @elseif ($item->condition == 2)
                                目立った傷や汚れなし
                            @elseif ($item->condition == 3)
                                やや傷や汚れあり
                            @else
                                状態が悪い
                            @endif
                        </td>
                    </tr>
                </table>
            </div>

            <div class="detail__comment">
                <h3 class="comment__title">コメント({{ $commentCount }})</h3>
                @foreach ($comments as $comment)
                    <div class="comment__group">
                        <img src="{{ asset(optional($comment->user->profile)->image ? 'storage/' . $comment->user->profile->image : 'images/default.jpg') }}"
                            alt="{{ $comment->user->name }}" class="comment__user-image">
                        <p class="comment__user-name">{{ $comment->user->name }}</p>
                    </div>
                    <div class="comment__content">
                        <p class="comment__content-text">{{ $comment->content }}</p>
                    </div>
                @endforeach
            </div>

            <div class="detail__comment-form">
                <h3 class="comment-form__title">商品へのコメント</h3>
                <form action="{{ route('comment.store', ['id' => $item->id]) }}" method="POST">
                    @csrf
                    <div class="comment-form__textarea">
                        <textarea name="content"></textarea>
                    </div>
                    @error('content')
                        <p class="comment-form__error">{{ $message }}</p>
                    @enderror
                    <div class="comment-form__submit">
                        @if ($item->status === 1)
                            <input type="submit" class="comment-form__submit-btn" value="コメントを送信する">
                        @elseif ($item->status === 2)
                            <p class="purchase__sold">この商品は売り切れです</p>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
