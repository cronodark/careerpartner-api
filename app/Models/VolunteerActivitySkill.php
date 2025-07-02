<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VolunteerActivitySkill extends Model
{
    protected $fillable = [
        'volunteer_activity_id',
        'skill_id',
    ];

    public function volunteerActivity()
    {
        return $this->belongsTo(VolunteerActivity::class);
    }

    public function skill()
    {
        return $this->belongsTo(Skill::class);
    }
}
