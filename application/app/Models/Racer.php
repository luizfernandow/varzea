<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

final class Racer extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = ['name', 'birthdate', 'weight'];


    protected $casts = [
        'deleted_at' => 'datetime',
    ];
}
