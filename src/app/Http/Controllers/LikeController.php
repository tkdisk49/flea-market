<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggleLike($id)
    {
        $like = Like::where('user_id', Auth::id())->where('item_id', $id)->first();

        if ($like) {
            $like->delete();
        } else {
            Like::create([
                'user_id' => Auth::id(),
                'item_id' => $id,
            ]);
        }

        return redirect()->route('detail', ['id' => $id]);
    }
}
