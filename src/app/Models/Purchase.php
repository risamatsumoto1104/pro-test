<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    // 購入者
    public function user()
    {
        return $this->belongsTo(User::class, 'buyer_user_id');
    }

    // 商品
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    // 住所
    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id');
    }
}
