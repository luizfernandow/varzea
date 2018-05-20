<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Racer extends Model
{
	use SoftDeletes;

	protected $fillable = ['name', 'birthdate', 'weight'];


	 /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
}
