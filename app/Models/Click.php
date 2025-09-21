<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Click extends Model
{
    protected $fillable = [
        'user_id',
        'clicked_at',
        'type', // 'click' ou 'refil'
    ];

    public $timestamps = false; // se você não usa created_at / updated_at

    protected $casts = [
        'clicked_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
