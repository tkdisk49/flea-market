@extends('layouts/app')

@section('title')
    商品の出品
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/items/exhibit.css') }}">
@endsection

@section('content')
    <div class="exhibit__container">
        <div class="exhibit__header">
            <h2>商品の出品</h2>
        </div>
        <form class="exhibit__form" action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="exhibit-form__group">
                <p>商品画像</p>
                <div class="exhibit-form__image">
                    <input class="exhibit__file-input" type="file" id="item_image" name="image"
                        accept="image/jpeg, image/png">
                    <label for="item_image" class="custom__file-label">画像を選択する</label>
                </div>
                <div class="form__error">
                    @error('image')
                        <p class="form__error-message">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="exhibit__section">
                <h3>商品の詳細</h3>
            </div>

            <div class="exhibit-form__group">
                <p>カテゴリー</p>
                <div class="category-form__group">
                    @foreach ($categories as $category)
                        <input type="checkbox" name="categories[]" id="category_{{ $category->id }}"
                            value="{{ $category->id }}">
                        <label for="category_{{ $category->id }}" class="category-label">{{ $category->content }}</label>
                    @endforeach
                </div>
                <div class="form__error">
                    @error('categories')
                        <p class="form__error-message">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="exhibit-form__group">
                <p>商品の状態</p>
                <div class="select__wrapper">
                    <select class="condition-select" name="condition">
                        <option disabled selected>選択してください</option>
                        <option value="1">良好</option>
                        <option value="2">目立った傷や汚れなし</option>
                        <option value="3">やや傷や汚れあり</option>
                        <option value="4">状態が悪い</option>
                    </select>
                </div>
                <div class="form__error">
                    @error('condition')
                        <p class="form__error-message">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="exhibit__section">
                <h3>商品名と説明</h3>
            </div>

            <div class="exhibit-form__group">
                <p>商品名</p>
                <input type="text" name="name" value="{{ old('name') }}">
                <div class="form__error">
                    @error('name')
                        <p class="form__error-message">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="exhibit-form__group">
                <p>ブランド名</p>
                <input type="text" name="brand" value="{{ old('brand') }}">
            </div>

            <div class="exhibit-form__group">
                <p>商品の説明</p>
                <textarea class="description-input" name="description"></textarea>
                <div class="form__error">
                    @error('description')
                        <p class="form__error-message">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="exhibit-form__group">
                <p>販売価格</p>
                <div class="price__container">
                    <input type="number" name="price" min="0">
                </div>
                <div class="form__error">
                    @error('price')
                        <p class="form__error-message">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="exhibit-form__submit">
                <input type="submit" value="出品する">
            </div>
        </form>
    </div>
@endsection
