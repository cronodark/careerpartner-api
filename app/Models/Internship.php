<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Internship extends Model
{
    protected $fillable = [
        'company_id',
        'title',
        'description',
        'location',
        'image_cover',
        'responsibilities',
        'requirements',
        'status',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function skills()
    {
        return $this->hasMany(InternshipSkill::class);
    }
}
