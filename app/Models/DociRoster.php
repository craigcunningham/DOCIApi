<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DociRoster extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @type array
     */
    protected $guarded = [];
    protected $table = "docilineup";
    public $timestamps = false;
}