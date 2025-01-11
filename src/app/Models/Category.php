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

    // 主：Item(N.0)　⇔　従：Category(N.0)、中間テーブル（category_item）
    public function items()
    {
        return $this->belongsToMany(Item::class, 'category_item', 'category_id', 'item_id');
    }
}
