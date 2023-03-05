<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Championship extends Model
{
    use SoftDeletes, HasFactory;
    
    protected $fillable = ['name'];
}
