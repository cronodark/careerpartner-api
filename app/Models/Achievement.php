<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    protected $fillable = [
        'talent_id',
        'title',
        'nomination',
        'year',
    ];

    public function talent()
    {
        return $this->belongsTo(Talent::class);
    }
}
