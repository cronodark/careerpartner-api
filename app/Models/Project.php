<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'talent_id',
        'title',
        'image',
        'link',
        'year',
    ];

    public function talent()
    {
        return $this->belongsTo(Talent::class);
    }
}
