<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'buyer_user_id',
        'item_id',
        'address_id',
        'payment_method'
    ];

    // 主キー名を変更
    protected $primaryKey = 'purchase_id';

    // 主：User(1)　⇔　従：Purchase(N.0)
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_user_id');
    }

    //　主：Item(1)　⇔　従：Purchase(1)
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    //　主：address(1)　⇔　従：purchase(N.0)
    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id');
    }
}
