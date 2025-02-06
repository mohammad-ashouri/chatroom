<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property-read User $user_1
 * @property-read User $user_2
 * @property-read Chat[] $chats
 */
class PrivateChat extends Model
{
    use SoftDeletes;

    /**
     * Get the user 1.
     *
     * @return BelongsTo<User, $this>
     */
    public function user1(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_1');
    }

    /**
     * Get the user 2.
     *
     * @return BelongsTo<User, $this>
     */
    public function user2(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_2');
    }

    /**
     * Get the chats for the room.
     *
     * @return HasMany<PrivateChatMessages, $this>
     */
    public function chats(): HasMany
    {
        return $this->hasMany(PrivateChatMessages::class);
    }
}
