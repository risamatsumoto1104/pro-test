<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryItem extends Model
{
    use HasFactory;

    // 中間テーブル名
    protected $table = 'category_item';

    protected $fillable = [
        'item_id',
        'category_id',
    ];
}
