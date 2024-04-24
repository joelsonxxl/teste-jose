<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vote extends Model
{
    use HasFactory;

    protected $fillable = ['like', 'unlike', 'user_id', 'question_id'];

    /**
     * Get the user that owns the vote.
     *
     * @return BelongsTo<User, Vote>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the question associated with the vote.
     *
     * @return BelongsTo<Question, Vote>
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
