<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExhibitionRequest;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Item;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->query('page', 'home');
        $query = $request->query('query');

        if (Auth::check()) {
            $items = Item::where('user_id', '!=', Auth::id());
        } else {
            $items = Item::query();
        }

        if (!empty($query)) {
            $items->where('name', 'like', "%{$query}%");
        }

        $items = $items->get();

        $likeItems = collect();
        if (Auth::check() && $page === 'mylist') {
            $likeItems = Like::where('user_id', Auth::id())->with('item');

            if (!empty($query)) {
                $likeItems = $likeItems->whereHas('item', function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%");
                });
            }

            $likeItems = $likeItems->get();
        } elseif ($page === 'mylist' && !Auth::check()) {
            return redirect()->route('login');
        }

        return view('items.index', compact('page', 'items', 'likeItems', 'query'));
    }

    public function detail($id)
    {
        $item = Item::with('categories')->findOrFail($id);

        $likeCount = Like::where('item_id', $id)->count();
        $commentCount = Comment::where('item_id', $id)->count();

        $isLiked = false;

        if (Auth::check()) {
            if (Like::where('user_id', Auth::id())->where('item_id', $id)->exists()) {
                $isLiked = true;
            }
        }

        $comments = Comment::with('user')->where('item_id', $id)->latest()->get();

        return view('items.detail', compact('item', 'likeCount', 'commentCount', 'isLiked', 'comments'));
    }

    public function create()
    {
        $categories = Category::all();

        return view('items.exhibit', compact('categories'));
    }

    public function store(ExhibitionRequest $request)
    {
        $imagePath = $request->file('image')->store('items', 'public');

        $item = Item::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'price' => $request->price,
            'image' => $imagePath,
            'description' => $request->description,
            'condition' => $request->condition,
            'brand' => $request->brand,
        ]);

        $item->categories()->attach($request->categories);

        return redirect()->route('home');
    }

    public function search(Request $request)
    {
        $query = $request->query('query');
        $page = $request->query('page', 'home');

        return redirect()->route('home', compact('query', 'page'));
    }
}
