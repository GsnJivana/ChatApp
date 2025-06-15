<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    //
    protected $fillable = ['user_id', 'conversation_id', 'body'];

public function user(): BelongsTo {
    return $this->belongsTo(User::class);
}

public function conversation(): BelongsTo {
    return $this->belongsTo(Conversation::class);
}
}
