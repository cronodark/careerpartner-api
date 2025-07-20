<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LearningPath extends Model
{
    protected $fillable = [
        'title',
        'url',
        'is_done',
        'source',
        'talent_id'
    ];

    public function talent()
    {
        return $this->belongsTo(Talent::class);
    }
}
