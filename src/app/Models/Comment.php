<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    // 主キー名を変更
    protected $primaryKey = 'comment_id';

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

    // profile_imageを取得
    public function userProfile()
    {
        return $this->belongsTo(Profile::class, 'user_id', 'user_id');
    }
}
