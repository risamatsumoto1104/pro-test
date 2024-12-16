<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryItem extends Model
{
    use HasFactory;

    protected $table = 'category_item'; // 中間テーブル名

    protected $fillable = [
        'item_id', // items テーブルとの関連
        'category_id', // categories テーブルとの関連
    ];
}
