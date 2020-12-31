<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmLeaveType extends Model
{
    public function leaveDefines()
    {
        return $this->hasMany(SmLeaveDefine::class,'type_id');
    }
}
