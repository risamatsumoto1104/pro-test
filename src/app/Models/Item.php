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
        'item_image',
    ];

    // 主キー名を変更
    protected $primaryKey = 'item_id';

    // 部分一致検索
    public function scopeKeywordSearch($query, $keyword)
    {
        if (!empty($keyword)) {
            $query->where('item_name', 'like', '%' . $keyword . '%');
        }
    }

    // 主：Item(1)　⇔　従：Like(N.0)
    public function itemLikes()
    {
        return $this->hasMany(Like::class, 'item_id');
    }

    // 主：Item(1)　⇔　従：Comment(N.0)
    public function comments()
    {
        return $this->hasMany(Comment::class, 'item_id');
    }

    // 主：Item(1)　⇔　従：Purchase(1)
    public function purchase()
    {
        return $this->hasOne(Purchase::class, 'item_id');
    }

    // 主：Item(1)　⇔　従：chatMessage(1)
    public function chatMessage()
    {
        return $this->hasOne(ChatMessage::class, 'item_id');
    }

    // 主：Item(1)　⇔　従：rating(1)
    public function rating()
    {
        return $this->hasOne(Rating::class, 'item_id');
    }

    // 主：User(1)　⇔　従：Item(N.0)
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_user_id');
    }

    // 主：Item(N.0)　⇔　従：Category(N.0)、中間テーブル（category_item）
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_item', 'item_id', 'category_id');
    }

    //　主：Item(N.0)　⇔　従：Address(N.0)、中間テーブル（address_item）
    public function addresses()
    {
        return $this->belongsToMany(Address::class, 'address_item', 'item_id', 'address_id');
    }
}
