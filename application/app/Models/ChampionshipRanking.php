<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChampionshipRanking extends Model
{
    use HasFactory;

    protected $fillable = ['racer_id', 'points'];

    public $timestamps = false;

    public function racer(): BelongsTo
    {
        return $this->belongsTo(Racer::class);
    }
}
