<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EducationHistory extends Model
{
    protected $table = 'education_histories';

    protected $fillable = [
        'talent_id',
        'institution_name',
        'field_of_study',
        'start_year',
        'end_year',
    ];

    public function talent()
    {
        return $this->belongsTo(Talent::class);
    }
}
