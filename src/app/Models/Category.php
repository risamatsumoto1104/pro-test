<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // 主キー名を変更
    protected $primaryKey = 'category_id';

    // アイテム
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_item');
    }
}
