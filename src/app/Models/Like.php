<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    // 主キー名を変更
    protected $primaryKey = 'like_id';

    // ユーザー
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // 商品
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
