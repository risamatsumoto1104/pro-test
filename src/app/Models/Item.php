<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    public function getIsSoldAttribute()
    {
        return $this->sold_at !== null;
    }

    // 出品者
    public function user()
    {
        return $this->belongsTo(User::class, 'seller_user_id');
    }

    // カテゴリー
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_item');
    }

    // いいね
    public function likes()
    {
        return $this->hasMany(Like::class, 'item_id');
    }

    // コメント
    public function comments()
    {
        return $this->hasMany(Comment::class, 'item_id');
    }

    // 購入履歴
    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'item_id');
    }
}
