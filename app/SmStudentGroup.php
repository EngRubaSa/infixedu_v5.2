<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmStudentGroup extends Model
{
    public function students(){

        return $this->hasMany(SmStudent::class,'student_group_id','id');
    }
}
