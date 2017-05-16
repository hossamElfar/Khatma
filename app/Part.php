<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    protected $table = 'parts';

    public function person()
    {
        return $this->belongsTo('App\User', 'person_id');
    }

    public function khatma()
    {
        return $this->belongsTo('App\Khatma','khatma_id');
    }
    
}
