<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Project extends Model
{
    protected $fillable = [
        'talent_id',
        'title',
        'image',
        'link',
        'year',
    ];

    public function getImageAttribute(?string $value): ?string
    {
        if ($value) {

            if (Storage::disk('public')->exists($value)) {
                return Storage::disk('public')->url($value);
            }
        }
        return "";
    }

    public function talent()
    {
        return $this->belongsTo(Talent::class);
    }
}
