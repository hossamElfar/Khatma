<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    protected $table = 'parts';

    protected $fillable = ['name_en', 'name_ar', 'number_of_part', 'start_page', 'end_page', 'current_page', 'taken', 'person_id', 'khatma_id'];

    public function person()
    {
        return $this->belongsTo('App\Person', 'person_id');
    }

    public function khatma()
    {
        return $this->belongsTo('App\Khatma', 'khatma_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
