<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_user_id',
        'item_name',
        'brand_name',
        'price',
        'description',
        'condition',
        'item_image'
    ];

    // 主キー名を変更
    protected $primaryKey = 'item_id';

    public function getIsSoldAttribute()
    {
        return $this->sold_at !== null;
    }

    public function scopeKeywordSearch($query, $keyword)
    {
        if (!empty($keyword)) {
            $query->where('item_name', 'like', '%' . $keyword . '%');
        }
    }

    // 出品者
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_user_id');
    }

    // 「商品」対「カテゴリー」（多対多）
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_item', 'item_id', 'category_id');
    }

    // 「商品」対「いいね」（1対多）
    public function likes()
    {
        return $this->hasMany(Like::class, 'item_id');
    }

    // 「商品」対「コメント」（1対多）
    public function comments()
    {
        return $this->hasMany(Comment::class, 'item_id');
    }

    // 購入履歴
    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'item_id');
    }

    // 「商品」対「送付先」（多対多）
    public function addresses()
    {
        return $this->belongsToMany(Address::class, 'address_item', 'item_id', 'address_id');
    }
}
