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

    //　主：user(1)　⇔　従：address(N.0)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    //　主：item(N.0)　⇔　従：address(N.0)、中間テーブル（address_item）
    public function items()
    {
        return $this->belongsToMany(Item::class, 'address_item', 'address_id', 'item_id');
    }

    //　主：address(1)　⇔　従：purchase(N.0)
    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'address_id');
    }
}
