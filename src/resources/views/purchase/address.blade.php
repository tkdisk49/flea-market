@extends('layouts/app')

@section('title')
    住所の変更
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/address.css') }}">
@endsection

@section('content')
    <div class="address__container">
        <div class="address__heading">
            <h2>住所の変更</h2>
        </div>

        <form class="address__update-form" action="{{ route('purchase.address.update') }}" method="POST">
            @csrf
            @method('PATCH')
            <div class="update-form__group">
                <label class="update-form__label" for="name">お名前</label>
                <input class="update-form__input" type="text" name="name" value="{{ old('name') }}">
                @error('name')
                    <p class="update-form__error">{{ $message }}</p>
                @enderror
            </div>
            <div class="update-form__group">
                <label class="update-form__label" for="post_code">郵便番号</label>
                <input class="update-form__input" type="text" name="post_code" value="{{ old('post_code') }}">
                @error('post_code')
                    <p class="update-form__error">{{ $message }}</p>
                @enderror
            </div>
            <div class="update-form__group">
                <label class="update-form__label" for="address">住所</label>
                <input class="update-form__input" type="text" name="address" value="{{ old('address') }}">
                @error('address')
                    <p class="update-form__error">{{ $message }}</p>
                @enderror
            </div>
            <div class="update-form__group">
                <label class="update-form__label" for="building">建物名</label>
                <input class="update-form__input" type="text" name="building" value="{{ old('building') }}">
                @error('building')
                    <p class="update-form__error">{{ $message }}</p>
                @enderror
            </div>
            <div class="update-form__submit">
                <input type="submit" value="更新する">
            </div>
        </form>
    </div>
@endsection
