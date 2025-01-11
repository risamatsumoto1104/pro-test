<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_id',
        'content'
    ];

    // 主キー名を変更
    protected $primaryKey = 'comment_id';

    // 主：User(1)　⇔　従：Comment(N.0)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // 主：Item(1)　⇔　従：Comment(N.0)
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    // user_idを通して、Profileよりprofile_imageを取得
    public function userProfile()
    {
        return $this->belongsTo(Profile::class, 'user_id', 'user_id');
    }
}
