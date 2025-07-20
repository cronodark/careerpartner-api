<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Talent extends Model
{

    protected $table = 'talents';

    protected $fillable = [
        'user_id',
        'current_education',
        'goal_career',
        'description',
        'expected_salary',
        'date_of_birth',
        'job_opportunity'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function skills()
    {
        return $this->hasMany(UserSkill::class);
    }

    public function experiences()
    {
        return $this->hasMany(Experience::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function achievements()
    {
        return $this->hasMany(Achievement::class);
    }

    public function interests()
    {
        return $this->hasMany(Interest::class);
    }

    public function educationHistories()
    {
        return $this->hasMany(EducationHistory::class);
    }

    public function learningPaths()
    {
        return $this->hasMany(LearningPath::class);
    }
}
