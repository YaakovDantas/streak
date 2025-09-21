<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    protected $fillable = ['name', 'slug', 'icon_path'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_badges')->withTimestamps()->withPivot('earned_at');
    }

    public function requirement()
    {
        return $this->hasOne(BadgeRequirement::class);
    }

}
