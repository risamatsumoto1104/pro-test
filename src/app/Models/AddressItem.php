<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddressItem extends Model
{
    use HasFactory;

    protected $table = 'address_item_table'; // 中間テーブル名

    protected $fillable = [
        'item_id', // items テーブルとの関連
        'address_id', // addresses テーブルとの関連
    ];
}
