<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $fillable = [
        'name',
        'logo',
        'contact_email',
        'contact_phone',
        'status',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function volunteerActivities()
    {
        return $this->hasMany(VolunteerActivity::class);
    }
}
