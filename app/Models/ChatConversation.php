<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stancl\VirtualColumn\VirtualColumn;

class ChatConversation extends Model
{
    use HasFactory;
    use SoftDeletes;
    use VirtualColumn;

    protected $fillable = ['title'];

    protected $appends = ['channel_name'];

    /**
     * @return string[]
     */
    public static function getCustomColumns(): array
    {
        return [
            'id',
            'title',
            'created_at',
            'updated_at',
            'deleted_at',
        ];
    }

    /**
     * @return HasMany
     */
    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'chat_conversation_id');
    }


    public function latestMessages()
    {
        return $this->hasMany(ChatMessage::class, 'chat_conversation_id')->latest()->limit(10);
    }

    /**
     * @return string|null
     */
    public function getChannelNameAttribute(): ?string
    {
        $tenant = tenant();

        return $tenant
            ? 'tenant_' . $tenant->id . '_chat_' . $this->id
            : null;
    }
}
