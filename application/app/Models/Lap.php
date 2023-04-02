<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Lap extends Model
{
    protected $fillable = ['race_id', 'racer_id', 'time'];

    public function racer(): BelongsTo
    {
        return $this->belongsTo(Racer::class);
    }

    public function race(): BelongsTo
    {
        return $this->belongsTo(Race::class);
    }
}
