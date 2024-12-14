<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    // 主キー名を変更
    protected $primaryKey = 'purchase_id';

    // 購入者
    public function buyer()
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
