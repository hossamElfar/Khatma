<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $table = 'cases';
    protected $dates = ['date_of_birth', 'date_of_death'];
    protected $fillable = ['name', 'date_of_birth', 'date_of_death', 'user_id', 'description', 'pp', 'field', 'organization'];

    public function created_by()
    {
        return $this->belongsTo('App\User');
    }

    public function khatma()
    {
        return $this->hasMany('App\khatma')->first();
    }

    public function parts()
    {
        return $this->hasMany('App\Part','person_id');
    }
}
