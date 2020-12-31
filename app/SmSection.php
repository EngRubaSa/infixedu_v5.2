<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmSection extends Model
{
   //

   public function students()
   {
       return $this->hasMany('App\SmStudent', 'section_id', 'id');
   }
}
