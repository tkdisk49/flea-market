@extends('layouts/app')

@section('title')
    商品の購入
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
    @if (session('error'))
        <div class="session__error-message">
            {{ session('error') }}
        </div>
    @endif
    <div class="purchase__container">
        <div class="purchase__container-left">
            <div class="item__info-group">
                <div class="item__info-image">
                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}">
                </div>
                <div class="item__info-content">
                    <h2 class="item__name">{{ $item->name }}</h2>
                    <p class="item__price"><span>¥</span> {{ number_format($item->price) }}</p>
                </div>
            </div>

            <div class="payment__info-group">
                <h3 class="payment__info-title">支払い方法</h3>
                <form action="{{ route('purchase.update-payment', ['id' => $item->id]) }}" method="POST">
                    @csrf
                    <div class="payment__select-wrapper">
                        <select name="payment_method" class="payment__info-input" onchange="this.form.submit()">
                            <option disabled selected>選択してください</option>
                            <option value="1" {{ $paymentMethod == '1' ? 'selected' : '' }}>コンビニ支払い</option>
                            <option value="2" {{ $paymentMethod == '2' ? 'selected' : '' }}>カード支払い</option>
                        </select>
                    </div>
                    @error('payment_method')
                        <p class="payment__error-message">{{ $message }}</p>
                    @enderror
                </form>
            </div>

            <div class="buyer__info-group">
                <div class="buyer__info-header">
                    <h3 class="buyer__info-title">配送先</h3>
                    <a href="{{ route('purchase.change-address', ['id' => $item->id]) }}"
                        class="buyer__change-address">変更する</a>
                </div>
                <div class="buyer__info-address">
                    <p>{{ Auth::user()->address->name }}</p>
                    <p>〒 {{ Auth::user()->address->post_code }}</p>
                    <p>{{ Auth::user()->address->address }}</p>
                    <p>{{ Auth::user()->address->building }}</p>
                </div>
            </div>
        </div>

        <div class="purchase__container-right">
            <div class="purchase__info-group">
                <table class="info-group__table">
                    <tr class="info-group__row">
                        <th class="info-group__header">商品代金</th>
                        <td class="info-group__text">¥ <span>{{ number_format($item->price) }}</span></td>
                    </tr>
                    <tr class="info-group__row">
                        <th class="info-group__header">支払い方法</th>
                        <td class="info-group__text">
                            @if ($paymentMethod == '1')
                                コンビニ支払い
                            @elseif ($paymentMethod == '2')
                                カード支払い
                            @else
                                未選択
                            @endif
                        </td>
                    </tr>
                </table>
            </div>

            <div class="purchase__submit">
                <form action="{{ route('purchase.store', ['id' => $item->id]) }}" method="POST">
                    @csrf
                    <input type="hidden" name="address_id" value="{{ Auth::user()->address->id }}">
                    <input type="hidden" name="payment_method" value="{{ $paymentMethod }}">
                    <input type="submit" class="purchase__submit-btn" value="購入する">
                </form>
            </div>
        </div>
    </div>
@endsection
