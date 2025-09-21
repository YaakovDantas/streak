<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function streak()
    {
        return $this->hasOne(Streak::class);
    }

    // Relacionamento com streaks
    public function streaks()
    {
        return $this->hasMany(Streak::class);
    }

    // Retorna o streak mais recente (para o dashboard, por ex.)
    public function latestStreak()
    {
        return $this->hasOne(Streak::class)->latestOfMany();
    }

    public function clicks()
    {
        return $this->hasMany(Click::class);
    }

    public function refils()
    {
        return $this->hasMany(Refill::class);
    }

    public function refillBalance()
    {
        return $this->hasOne(UserRefillBalance::class);
    }

    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'user_badges')->withTimestamps()->withPivot('earned_at');
    }

    public function getStreakCount(): int
    {
        return $this->streak->current_streak ?? 0;
    }


}
