<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Organisation extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'organisation_name'
    ];
    
    /**
     * An Organisation has many Users
     */
    public function users()
    {
        return $this->hasMany('App\User');
    }
}
