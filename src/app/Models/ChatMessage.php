<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    // 主キー名を変更
    protected $primaryKey = 'chat_id';

    protected $fillable = [
        'sender_id',
        'item_id',
        'sender_role',
        'message',
    ];

    //　主：User(1)　⇔　従：ChatMessage(N.0)
    public function sender()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // 主：Item(1)　⇔　従：chatMessage(N)
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
