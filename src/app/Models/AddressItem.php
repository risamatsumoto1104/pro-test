<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddressItem extends Model
{
    use HasFactory;

    // 中間テーブル名
    protected $table = 'address_item';

    protected $fillable = [
        'item_id',
        'address_id',
    ];
}
