<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    // 主キー名を変更
    protected $primaryKey = 'addresses_id';

    // ユーザー
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // 購入履歴
    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'address_id');
    }
}
