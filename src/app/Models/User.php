<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    // 出品商品
    public function items()
    {
        return $this->hasMany(Item::class, 'seller_user_id');
    }

    // いいね
    public function likes()
    {
        return $this->hasMany(Like::class, 'user_id');
    }

    // コメント
    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id');
    }

    // プロフィール
    public function profile()
    {
        return $this->hasOne(Profile::class, 'user_id');
    }

    // 住所
    public function addresses()
    {
        return $this->hasMany(Address::class, 'user_id');
    }

    // 購入履歴
    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'buyer_user_id');
    }
}
