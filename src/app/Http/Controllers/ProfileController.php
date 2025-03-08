<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Http\Requests\ProfileRequest;
use App\Models\Address;
use App\Models\Item;
use App\Models\Profile;
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

        if ($page === 'sell') {
            $items = $user->items;
        } elseif ($page === 'buy') {
            $items = Item::whereHas('purchases', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->get();
        } else {
            return redirect()->route('mypage', ['page' => 'sell']);
        }

        return view('profile.profile', compact('items', 'page'));
    }

    public function update(ProfileRequest $profileRequest, AddressRequest $addressRequest)
    {
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
