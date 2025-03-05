<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(CommentRequest $request, $id)
    {
        Comment::create([
            'user_id' => Auth::id(),
            'item_id' => $id,
            'content' => $request->content,
        ]);

        return redirect()->route('detail', ['id' => $id]);
    }
}
