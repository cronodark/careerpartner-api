<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSkill extends Model
{
    protected $fillable = [
        'talent_id',
        'skill_id',
        'proficiency',
        'years_of_experience',
    ];

    public function talent()
    {
        return $this->belongsTo(Talent::class);
    }

    public function skill()
    {
        return $this->belongsTo(Skill::class);
    }
}
