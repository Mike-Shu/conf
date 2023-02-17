<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Stancl\VirtualColumn\VirtualColumn;
use Staudenmeir\EloquentEagerLimit\HasEagerLimit;

class ChatMessage extends Model
{
    use HasFactory;
    use VirtualColumn;
    use HasEagerLimit;

    protected $fillable = [
        'chat_conversation_id',
        'user_id',
        'text',
    ];

    /**
     * @return string[]
     */
    public static function getCustomColumns(): array
    {
        return [
            'id',
            'user_id',
            'chat_conversation_id',
            'text',
            'created_at',
            'updated_at',
        ];
    }

    /**
     * @return BelongsTo
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(ChatConversation::class);
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
