<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BadgeRequirement extends Model
{
    protected $fillable = ['badge_id', 'type', 'target'];

    public function badge()
    {
        return $this->belongsTo(Badge::class);
    }
}
