<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmExamSetup extends Model
{
    public function className(){
        return $this->belongsTo('App\SmClass', 'class_id', 'id');
    }
     public function section()
    {
        return $this->belongsTo('App\SmSection', 'section_id', 'id');
    }
}
