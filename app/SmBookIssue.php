<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmBookIssue extends Model
{
    public function books()
    {
    	return $this->belongsTo('App\SmBook', 'book_id', 'id');
    }

    public function member()
    {
    	return $this->belongsTo(SmLibraryMember::class, 'member_id', 'id');
    }

    public function getmemberDetailsAttribute()
    {
        $full_name = '';
        if($this->member->member_type == 2)
             $full_name = $this->member->studentDetails->full_name;
        else
             $full_name = $this->member->staffDetails->full_name;
        return $full_name;
    }

}