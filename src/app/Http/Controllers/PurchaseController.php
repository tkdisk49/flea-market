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

class PurchaseController extends Controller
{
    const SOLD = 2;

    public function show(Request $request, $id)
    {
        $item = Item::findOrFail($id);

        $address = Address::where('user_id', Auth::id())->first();

        $paymentMethod = $request->session()->get('payment_method');

        if ($item->status === self::SOLD) {
            return redirect()->route('detail', ['id' => $id])->with('error', 'この商品は売り切れです');
        }

        return view('purchase.purchase', compact('item', 'address', 'paymentMethod'));
    }

    public function updatePaymentMethod(Request $request, $id)
    {
        $request->session()->flash('payment_method', $request->payment_method);

        return redirect()->route('purchase.show', ['id' => $id]);
    }

    public function store(PurchaseRequest $request, $id)
    {
        DB::beginTransaction();

        try {
            $user = Auth::user();
            $item = Item::findOrFail($id);

            Purchase::create([
                'user_id' => $user->id,
                'item_id' => $id,
                'address_id' => $request->address_id,
                'payment_method' => $request->payment_method,
            ]);

            $item->update(['status' => self::SOLD]);

            DB::commit();

            session()->forget(['payment_method', 'purchase_item_id']);

            return redirect()->route('purchase.complete');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('purchase.show', ['id' => $id])->with('error', '購入処理に失敗しました。');
        }
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
}
