<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmStudentCategory extends Model
{
    public function students(){

        return $this->hasMany(SmStudent::class,'student_category_id','id');
    }
}
