<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class PrivateChatMessages extends Model
{
    use SoftDeletes;

    /**
     * @property int $id
     * @property-read PrivateChat $private_chat_id
     * @property-read string $message
     * @property-read User $user
     * @property-read Carbon|null $created_at
     * @property-read Carbon|null $updated_at
     * @property-read Carbon|null $deleted_at
     */

    /**
     * Get the user who sent the chat.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
