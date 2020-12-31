<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmExamAttendance extends Model
{
    public function examAttendanceChild(){
    	return $this->hasMany('App\SmExamAttendanceChild', 'exam_attendance_id', 'id');
    }
    public function className(){
        return $this->belongsTo('App\SmClass', 'class_id', 'id');
    }
     public function section()
    {
        return $this->belongsTo('App\SmSection', 'section_id', 'id');
    }
}
