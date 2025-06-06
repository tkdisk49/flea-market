@extends('layouts/app')

@section('title')
    マイページ
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/profile/profile.css') }}">
@endsection

@section('content')
    <div class="profile__container">
        <div class="profile__header">
            <div class="profile__img">
                <img src="{{ Auth::user()->profile && Auth::user()->profile->image ? asset('storage/' . Auth::user()->profile->image) : asset('images/default.jpg') }}"
                    alt="プロフィール画像">
            </div>
            <div class="profile__header-text">
                <div class="profile__header-text--info">
                    <p>{{ optional(Auth::user()->address)->name ?? '未設定' }}</p>
                    @if (isset($roundedAverageRating))
                        <div class="profile__rating">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $roundedAverageRating)
                                    <span class="profile__star profile__star--colored">&#9733;</span>
                                @else
                                    <span class="profile__star profile__star--empty">&#9733;</span>
                                @endif
                            @endfor
                        </div>
                    @endif
                </div>
                <div class="profile__header-text--link">
                    <a href="{{ route('profile.edit') }}" class="edit-link">プロフィールを編集</a>
                </div>
            </div>
        </div>

        <div class="profile__tab-menu">
            <a href="{{ route('mypage', ['page' => 'sell']) }}" class="{{ $page === 'sell' ? 'active' : '' }}">出品した商品</a>
            <a href="{{ route('mypage', ['page' => 'buy']) }}" class="{{ $page === 'buy' ? 'active' : '' }}">購入した商品</a>
            <a href="{{ route('mypage', ['page' => 'trading']) }}" class="{{ $page === 'trading' ? 'active' : '' }}">
                取引中の商品
                @if ($newMessageCount > 0)
                    <span class="profile__new-message-count">{{ $newMessageCount }}</span>
                @endif
            </a>
        </div>

        @if ($page === 'trading')
            <div class="profile__item-list">
                @foreach ($transactions as $transaction)
                    <div class="item__card">
                        <a href="{{ route('transaction.chat', ['id' => $transaction->id]) }}">
                            <img src="{{ asset('storage/' . $transaction->item->image) }}"
                                alt="{{ $transaction->item->name }}">
                        </a>
                        @if ($transaction->newMessageCount > 0)
                            <span class="profile__new-message-count badge">
                                {{ $transaction->newMessageCount }}
                            </span>
                        @endif
                        <p class="item__name">{{ $transaction->item->name }}</p>
                    </div>
                @endforeach
            </div>
        @else
            <div class="profile__item-list">
                @foreach ($items as $item)
                    <div class="item__card">
                        <a href="{{ route('detail', ['id' => $item->id]) }}">
                            <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}">
                        </a>
                        <p class="item__name">{{ $item->name }}</p>
                        @if ($item->status === 2)
                            <p class="item__sold">Sold</p>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
