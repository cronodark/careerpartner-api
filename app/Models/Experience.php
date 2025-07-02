<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    protected $fillable = [
        'talent_id',
        'description',
    ];

    public function talent()
    {
        return $this->belongsTo(Talent::class);
    }
}
