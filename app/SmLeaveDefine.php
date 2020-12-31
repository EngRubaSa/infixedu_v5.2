<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class SmLeaveDefine extends Model
{
    public function role(){
    	return $this->belongsTo('Modules\RolePermission\Entities\InfixRole', 'role_id', 'id');
    }

    public function leaveType(){
    	return $this->belongsTo('App\SmLeaveType', 'type_id', 'id');
    }

    public function leaveRequests(){
    	return $this->hasMany(SmLeaveRequest::class, 'leave_define_id');
    }

    public function getremainingDaysAttribute()
	{
        $diff_in_days =0;
        foreach($this->leaveRequests as $leave){
            $to = Carbon::parse( $leave->leave_from);
		    $from = Carbon::parse( $leave->leave_to);
		    $diff_in_days += $to->diffInDays($from);
        }
		return $diff_in_days;
	}
}
