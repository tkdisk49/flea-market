<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    const STATUS_AVAILABLE = 1;
    const STATUS_SOLD = 2;

    const CONDITION_GOOD = 1;
    const CONDITION_NO_MAJOR_DAMAGE = 2;
    const CONDITION_SOME_DAMAGE = 3;
    const CONDITION_BAD = 4;

    protected $fillable = [
        'user_id',
        'name',
        'price',
        'image',
        'description',
        'condition',
        'brand',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_item', 'item_id', 'category_id');
    }
}
