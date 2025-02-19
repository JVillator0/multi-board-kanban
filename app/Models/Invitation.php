<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'status',
        'user_id',
        'board_id',
    ];

    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'board_id' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function guest(): BelongsTo
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }

    public function board(): BelongsTo
    {
        return $this->belongsTo(Board::class);
    }
}
