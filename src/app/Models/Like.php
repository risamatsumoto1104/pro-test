<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_id'
    ];

    // 主キー名を変更
    protected $primaryKey = 'like_id';

    // 主：User(1)　⇔　従：Like(N.0)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // 主：Item(1)　⇔　従：Like(N.0)
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
