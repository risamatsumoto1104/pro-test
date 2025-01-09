<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\CustomVerifyEmailNotification;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;


    // 主キー名を変更
    protected $primaryKey = 'user_id';

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // メール認証用の通知をカスタマイズ
    public function sendEmailVerificationNotification()
    {
        $this->notify(new CustomVerifyEmailNotification());
    }

    // 出品商品
    public function items()
    {
        return $this->hasMany(Item::class, 'seller_user_id');
    }

    // いいね
    public function userLikes()
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
