<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmFeesType extends Model
{
    public function fessGroup(){
        return $this->belongsTo('App\SmFeesGroup','fees_group_id');
    }
}
