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

    //　主：User(1)　⇔　従：Item(N.0)
    public function items()
    {
        return $this->hasMany(Item::class, 'seller_user_id');
    }

    //　主：User(1)　⇔　従：Like(N.0)
    public function userLikes()
    {
        return $this->hasMany(Like::class, 'user_id');
    }

    //　主：User(1)　⇔　従：Comment(N.0)
    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id');
    }

    //　主：User(1)　⇔　従：Profile(1)
    public function profile()
    {
        return $this->hasOne(Profile::class, 'user_id');
    }

    //　主：User(1)　⇔　従：Purchase(N.0)
    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'buyer_user_id');
    }

    //　主：User(1)　⇔　従：Address(N.0)
    public function addresses()
    {
        return $this->hasMany(Address::class, 'user_id');
    }

    //　主：User(1)　⇔　従：ChatMessage(N.0)
    public function chatMessages()
    {
        return $this->hasMany(ChatMessage::class, 'sender_id');
    }

    //　主：User(1)　⇔　従：Rating(N.0)
    public function ratings()
    {
        return $this->hasMany(Rating::class, 'evaluator_id');
    }
}
