<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmTemporaryMeritlist extends Model
{
    function className(){
        return $this->belongsTo('App\SmClass','class_id','id');
    }
    public function studentinfo(){
        return $this->belongsTo('App\SmStudent', 'student_id', 'id');
    }
}
