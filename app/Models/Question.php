<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};
use Illuminate\Database\Eloquent\{Builder, Model, Prunable, SoftDeletes};

class Question extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Prunable;

    protected $fillable = ['question', 'draft', 'created_by'];

    protected $casts = [
        'draft' => 'boolean',

    ];

    /**
     * Get the votes for the question.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Vote>
     */
    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function prunable(): Builder
    {
        return static::where('deleted_at', '<=', now()->subMonth());
    }
}
