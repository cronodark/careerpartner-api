<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Talent extends Model
{

    protected $table = 'talents';

    protected $fillable = [
        'user_id',
        'current_education',
        'major',
        'interests',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function skills()
    {
        return $this->hasMany(UserSkill::class);
    }
}
