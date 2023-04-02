<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Championship extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = ['name'];

    public function races(): HasMany
    {
        return $this->hasMany(Race::class);
    }

    public function ranking(): HasMany
    {
        return $this->hasMany(ChampionshipRanking::class)->orderBy('points', 'desc');
    }
}
