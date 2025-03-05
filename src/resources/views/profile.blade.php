@extends('layouts/app')

@section('title')
    マイページ
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
    <div class="profile__container">
        <div class="profile__header">
            <div class="profile__img">
                <img src="{{ Auth::user()->profile && Auth::user()->profile->image ? asset('storage/' . Auth::user()->profile->image) : asset('images/default.jpg') }}"
                    alt="プロフィール画像">
            </div>
            <div class="profile__header-text">
                <p>{{ optional(Auth::user()->address)->name ?? '未設定' }}</p>
                <a href="{{ route('show.edit.profile') }}" class="edit-link">プロフィールを編集</a>
            </div>
        </div>

        <div class="profile__tab-menu">
            <a href="{{ route('mypage', ['page' => 'sell']) }}" class="{{ $page === 'sell' ? 'active' : '' }}">出品した商品</a>
            <a href="{{ route('mypage', ['page' => 'buy']) }}" class="{{ $page === 'buy' ? 'active' : '' }}">購入した商品</a>
        </div>

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
    </div>
@endsection
