<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Khatma extends Model
{
    protected $table = 'khatma';
    protected $fillable = ['creator_id', 'person_id'];

    public function creator()
    {
        return $this->belongsTo('App\User', 'creator_id');
    }

    public function person()
    {
        return $this->belongsTo('App\Person');
    }

    public function subscribedUsers()
    {
        return $this->belongsToMany('App\User','user_khatma','khatma_id','user_id');
    }
}
