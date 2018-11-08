<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DociOwner extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @type array
     */
    //protected $fillable = [$2];
    protected $table = "dociowner";
    public $timestamps = false;
    
    public function team()
    {
        return $this->hasOne('App\Models\DociTeam');
    }
}
