<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // アイテム
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_item');
    }
}
