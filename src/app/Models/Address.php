<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_id',
        'postal_code',
        'address',
        'building'
    ];

    // 主キー名を変更
    protected $primaryKey = 'address_id';

    // ユーザー
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Itemとの関連を定義
    public function items()
    {
        return $this->belongsToMany(Item::class, 'address_item_table', 'address_id', 'item_id');
    }

    // 購入履歴
    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'address_id');
    }
}
