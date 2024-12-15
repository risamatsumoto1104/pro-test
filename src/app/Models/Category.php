<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'content'
    ];

    // 主キー名を変更
    protected $primaryKey = 'category_id';

    // Itemとの関連を定義
    public function items()
    {
        return $this->belongsToMany(Item::class, 'category_item_table', 'category_id', 'item_id');
    }
}
