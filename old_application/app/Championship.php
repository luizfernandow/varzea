<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Championship extends Model
{
	use SoftDeletes;
	
    protected $fillable = ['name'];
}
