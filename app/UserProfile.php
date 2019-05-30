<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'phone', 'user_id', 'organisation_id', "consant_flag"
    ];

    /**
     * A user has a profile
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    
    /**
     * A user has a organisation
     */
    public function organisation()
    {
        return $this->belongsTo('App\Organisation');
    }
}
