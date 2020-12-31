<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class SmStudentAttendance extends Model
{
    protected $table = "sm_student_attendances";

    public function studentInfo()
    {
        return $this->belongsTo('App\SmStudent', 'student_id', 'id');
    }
    public function scopemonthAttendances($query,$month)
    {
        return $query->whereMonth('attendance_date', $month);
    }
}
