<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VolunteerActivity extends Model
{
    protected $fillable = [
        'organization_id',
        'title',
        'description',
        'location',
        'status',
        'image_cover',
        'detail_activity'
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function skills()
    {
        return $this->hasMany(VolunteerActivitySkill::class);
    }
}
