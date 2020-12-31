<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentAttendanceBulk extends Model
{
    protected $fillable  = ['attendance_date','in_time','out_time','attendance_type','note','student_id','class_id','section_id'];
}