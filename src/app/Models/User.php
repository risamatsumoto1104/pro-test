<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // 主キー名を変更
    protected $primaryKey = 'user_id';

    // 出品商品
    public function items()
    {
        return $this->hasMany(Item::class, 'seller_user_id');
    }

    // いいね
    public function likes()
    {
        return $this->hasMany(Like::class, 'user_id');
    }

    // コメント
    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id');
    }

    // プロフィール
    public function profile()
    {
        return $this->hasOne(Profile::class, 'user_id');
    }

    // 住所
    public function addresses()
    {
        return $this->hasMany(Address::class, 'user_id');
    }

    // 購入履歴
    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'buyer_user_id');
    }
}
