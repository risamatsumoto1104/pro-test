<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    // 主キー名を変更
    protected $primaryKey = 'rating_id';

    protected $fillable = [
        'evaluator_id',
        'item_id',
        'evaluator_role',
        'rating',
    ];

    //　主：User(1)　⇔　従：Rating(N.0)
    public function evaluator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // 主：Item(1)　⇔　従：rating(1)
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
