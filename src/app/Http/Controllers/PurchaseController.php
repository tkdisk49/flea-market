<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Http\Requests\PurchaseRequest;
use App\Models\Address;
use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;

class PurchaseController extends Controller
{
    public function show(Request $request, $id)
    {
        $item = Item::findOrFail($id);

        $address = Address::where('user_id', Auth::id())->first();

        $paymentMethod = $request->session()->get('payment_method');

        if ($item->status === Item::STATUS_SOLD) {
            return redirect()->route('detail', ['id' => $id])->with('error', 'この商品は売り切れです');
        }

        return view('purchase.purchase', compact('item', 'address', 'paymentMethod'));
    }

    public function store(PurchaseRequest $request)
    {
        $user = Auth::user();
        $item = Item::findOrFail($request->item_id);
        $paymentMethod = $request->payment_method;

        if ($item->status === Item::STATUS_SOLD) {
            return redirect()->route('detail', ['id' => $item->id])->with('error', 'この商品はすでに購入済みです');
        }

        DB::beginTransaction();

        try {
            Purchase::create([
                'user_id' => $user->id,
                'item_id' => $item->id,
                'address_id' => $user->address->id,
                'payment_method' => $paymentMethod,
            ]);

            $item->update(['status' => Item::STATUS_SOLD]);

            DB::commit();

            return redirect()->route('purchase.complete');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('home')->with('error', '購入を完了できませんでした');
        }
    }

    public function updatePaymentMethod(Request $request, $id)
    {
        $request->session()->flash('payment_method', $request->payment_method);

        return redirect()->route('purchase.show', ['id' => $id]);
    }

    public function checkout(PurchaseRequest $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $item = Item::findOrFail($request->item_id);
        $paymentMethod = $request->payment_method;

        if ($paymentMethod === 'konbini') {
            $paymentType = ['konbini'];
        } else {
            $paymentType = ['card'];
        }

        $session = StripeSession::create([
            'payment_method_types' => $paymentType,
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'unit_amount' => $item->price,
                    'product_data' => [
                        'name' => $item->name,
                    ],
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('purchase.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('purchase.cancel'),
            'metadata' => [
                'item_id' => $item->id,
                'payment_method' => $paymentMethod,
            ],
        ]);

        return Redirect::away($session->url);
    }

    public function success(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $session_id = $request->query('session_id');

            if (!$session_id) {
                return redirect()->route('home')->with('error', '決済情報を取得できませんでした');
            }

            $session = StripeSession::retrieve($session_id);

            $user = Auth::user();
            $item = Item::findOrFail($session->metadata->item_id);
            $paymentMethod = $session->metadata->payment_method;

            DB::beginTransaction();

            Purchase::create([
                'user_id' => $user->id,
                'item_id' => $item->id,
                'address_id' => $user->address->id,
                'payment_method' => $paymentMethod,
            ]);

            $item->update(['status' => Item::STATUS_SOLD]);

            DB::commit();

            session()->forget(['payment_method', 'purchase_item_id']);

            return redirect()->route('purchase.complete');
        } catch (ApiErrorException $e) {
            DB::rollBack();
            return redirect()->route('home')->with('error', '決済情報を取得できませんでした');
        }
    }

    public function cancel()
    {
        return redirect()->route('home')->with('error', '支払いがキャンセルされました');
    }

    public function changeAddress($id)
    {
        session(['purchase_item_id' => $id]);
        return view('purchase.address', compact('id'));
    }

    public function updateAddress(AddressRequest $request)
    {
        $existingAddress = Auth::user()->address;

        if ($existingAddress) {
            $existingAddress->update([
                'name' => $request->name,
                'post_code' => $request->post_code,
                'address' => $request->address,
                'building' => $request->building,
            ]);

            return redirect()->route('purchase.show', ['id' => session('purchase_item_id')]);
        } else {
            return redirect()->route('purchase.show', ['id' => session('purchase_item_id')])->with('error', '更新する住所が見つかりませんでした。');
        }
    }

    public function thanks()
    {
        return view('purchase.complete');
    }
}
