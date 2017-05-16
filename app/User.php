<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'gender', 'date_of_birth', 'phone', 'code', 'confirmed', 'organization'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    /**
     * Dates attributes
     *
     * @var array
     */
    protected $dates = ['date_of_birth'];

    public function cases()
    {
        return $this->hasMany('App\Person');
    }

    public function created_khatmas()
    {
        return $this->hasMany('App\Khatma', 'creator_id');
    }

    public function khatma()
    {
        return $this->hasMany('App\Part', 'khatma_id');
    }

    public function parts()
    {
        return $this->hasMany('App\Part', 'person_id');
    }

    public function subscribedKhatma()
    {
        return $this->belongsToMany('App\Khatma','user_khatma','user_id','khatma_id');
    }
    
}
