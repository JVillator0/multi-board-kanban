<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'task_id',
        'user_id',
    ];

    protected $casts = [
        'id' => 'integer',
        'task_id' => 'integer',
        'user_id' => 'integer',
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function board(): HasOneThrough
    {
        return $this->hasOneThrough(Board::class, Task::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
