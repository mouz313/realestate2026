<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Activity extends Model
{
    protected $fillable = [
        'causer_type', 'causer_id', 'subject_type', 'subject_id',
        'event', 'description', 'properties',
    ];

    protected function casts(): array
    {
        return [
            'properties' => 'json',
        ];
    }

    public function causer(): MorphTo
    {
        return $this->morphTo();
    }

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }
}
