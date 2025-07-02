<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Interest extends Model
{
    protected $fillable = [
        'talent_id',
        'name',
    ];

    public function talent()
    {
        return $this->belongsTo(Talent::class);
    }
}
