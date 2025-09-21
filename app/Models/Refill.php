<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Refill extends Model
{
    use HasFactory;

    protected $table = 'refills';

    protected $fillable = [
        'user_id',
        'type',           // 'free', 'ad', 'paid'
        'refilled_for',   // dia que o refil cobre
        'used_at',        // quando foi usado (pode ser null)
    ];

    protected $casts = [
        'refilled_for' => 'date',
        'used_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
