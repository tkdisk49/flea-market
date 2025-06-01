<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Http\Requests\ProfileRequest;
use App\Models\Address;
use App\Models\Item;
use App\Models\Message;
use App\Models\Profile;
use App\Models\Review;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
    {
        return view('profile.edit');
    }

    public function mypage(Request $request)
    {
        $user = Auth::user();
        $page = $request->query('page', 'sell');

        $items = null;
        $transactions = null;

        if ($page === 'sell') {
            $items = $user->items;
        } elseif ($page === 'buy') {
            $items = Item::whereHas('purchases', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->get();
        } elseif ($page === 'trading') {
            $transactions = Transaction::where(function ($query) use ($user) {
                $query->where('buyer_id', $user->id)
                    ->orWhere('seller_id', $user->id);
            })
                ->whereIn('status', [Transaction::STATUS_STARTED, Transaction::STATUS_BUYER_REVIEWED])
                ->get();

            foreach ($transactions as $transaction) {
                $transaction->newMessageCount = $transaction->messages()
                    ->where('is_read', false)
                    ->where('user_id', '!=', $user->id)
                    ->count();
            }
        } else {
            return redirect()->route('mypage', ['page' => 'sell']);
        }

        $newMessageCount = Message::whereHas('transaction', function ($query) use ($user) {
            $query->where('buyer_id', $user->id)->orWhere('seller_id', $user->id);
        })
            ->where('is_read', false)
            ->where('user_id', '!=', $user->id)
            ->count();

        $averageRating = Review::where('reviewee_id', $user->id)->avg('rating');
        $roundedAverageRating = $averageRating ? round($averageRating) : null;

        return view('profile.profile', compact(
            'items',
            'page',
            'transactions',
            'newMessageCount',
            'roundedAverageRating',
        ));
    }

    public function update(ProfileRequest $profileRequest, AddressRequest $addressRequest)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $profileData = [
            'user_id' => $user->id,
            'image' => null,
        ];

        if ($profileRequest->hasFile('profile_image')) {
            if ($user->profile && $user->profile->image) {
                Storage::disk('public')->delete($user->profile->image);
            }

            $path = $profileRequest->file('profile_image')->store('profile_images', 'public');
            $profileData['image'] = $path;
        }

        if ($user->profile) {
            $user->profile->update($profileData);
        } else {
            $user->profile()->create($profileData);
        }

        if ($user->address) {
            $user->address->update([
                'name' => $addressRequest->name,
                'post_code' => $addressRequest->post_code,
                'address' => $addressRequest->address,
                'building' => $addressRequest->building,
            ]);
        } else {
            $user->address()->create([
                'name' => $addressRequest->name,
                'post_code' => $addressRequest->post_code,
                'address' => $addressRequest->address,
                'building' => $addressRequest->building,
            ]);
        }

        return redirect()->route('mypage');
    }
}
