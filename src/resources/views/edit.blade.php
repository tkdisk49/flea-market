@extends('layouts/app')

@section('title')
    プロフィール設定
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/edit.css') }}">
@endsection

@section('content')
    <div class="edit__container">
        <div class="edit__heading">
            <h2>プロフィール設定</h2>
        </div>

        <form class="edit__form" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <div class="form__group">
                <div class="form__img">
                    <div class="img__current">
                        <img src="{{ Auth::user()->profile && Auth::user()->profile->image ? asset('storage/' . Auth::user()->profile->image) : asset('images/default.jpg') }}"
                            alt="プロフィール画像">
                    </div>
                    <div class="img__input">
                        <input class="img__file-input" type="file" id="profile_image" name="profile_image"
                            accept="image/jpeg, image/png" value="画像を選択する">
                        <label for="profile_image" class="custom__file-label">画像を選択する</label>
                    </div>
                </div>
                @error('image')
                    <p class="form__error">
                        {{ $message }}
                    </p>
                @enderror
            </div>
            <div class="form__group">
                <label class="form__label" for="name">ユーザー名</label>
                <input class="form__input" type="text" name="name"
                    value="{{ old('name', Auth::user()->address ? Auth::user()->address->name : '') }}">
                <p class="form__error">
                    @error('name')
                        {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="form__group">
                <label class="form__label" for="post_code">郵便番号</label>
                <input class="form__input" type="text" name="post_code"
                    value="{{ old('post_code', Auth::user()->address ? Auth::user()->address->post_code : '') }}">
                <p class="form__error">
                    @if ($errors->has('post_code'))
                        {{ $errors->first('post_code') }}
                    @endif
                </p>
            </div>
            <div class="form__group">
                <label class="form__label" for="address">住所</label>
                <input class="form__input" type="text" name="address"
                    value="{{ old('address', Auth::user()->address ? Auth::user()->address->address : '') }}">
                <p class="form__error">
                    @error('address')
                        {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="form__group">
                <label class="form__label" for="building">建物名</label>
                <input class="form__input" type="text" name="building"
                    value="{{ old('building', Auth::user()->address ? Auth::user()->address->building : '') }}">
                <p class="form__error">
                    @error('building')
                        {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="form__button">
                <input class="form__button-submit" type="submit" value="更新する">
            </div>
        </form>
    </div>
@endsection
