@extends('layouts/app')

@section('title')
    coachtechフリマ
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
    <div id="item__container" data-page="{{ $page }}">
        <div class="item__header">
            <a class="header__tab" href="{{ route('home') }}" data-tab="home">おすすめ</a>
            <a class="header__tab" href="{{ Auth::check() ? route('home', ['page' => 'mylist']) : route('login') }}"
                data-tab="mylist">マイリスト</a>
        </div>

        <div class="item__group" id="items">
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

        <div class="item__group" id="likes">
            @foreach ($likeItems as $like)
                <div class="item__card">
                    <a href="{{ route('detail', ['id' => $like->item->id]) }}">
                        <img src="{{ asset('storage/' . $like->item->image) }}" alt="{{ $like->item->name }}">
                    </a>
                    <p class="item__name">{{ $like->item->name }}</p>
                    @if ($like->item->status === 2)
                        <p class="item__sold">Sold</p>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
@endsection
