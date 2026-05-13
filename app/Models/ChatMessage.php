<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMessage extends Model
{
    protected $fillable = [
        'user_id',
        'user_message',
        'bot_response',
        'message_type',
        'sentiment',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
