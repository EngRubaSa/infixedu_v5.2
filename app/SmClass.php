<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class SmClass extends Model
{


    public function section_name()
    {
        return $this->belongsTo('App\SmClassSection', 'id', '');
    }

    public function classSection()
    {
        return $this->hasMany('App\SmClassSection', 'class_id');
    }

    public function sectionName()
    {
        return $this->belongsTo('App\SmSection', 'section_id');
    }

    public function sections()
    {
        return $this->hasMany('App\SmSection', 'id', 'section_id');
    }

    public function classSections()
    {
        return $this->hasMany('App\SmClassSection', 'class_id', 'id')->where('school_id', Auth::user()->school_id)
            ->where('academic_id', getAcademicId());
    }
    public function groupclassSections()
    {
        return $this->hasMany('App\SmClassSection', 'class_id', 'id')->where('school_id', Auth::user()->school_id)
            ->where('academic_id', getAcademicId())->groupBy('section_id');
    }
    public function students()
    {
        return $this->hasMany('App\SmStudent', 'user_id', 'id');
    }

    public function subjects()
    {
        return $this->hasMany(SmAssignSubject::class, 'class_id');
    }

    public function routineUpdates()
    {
        return $this->hasMany(SmClassRoutineUpdate::class, 'class_id')->where('active_status', 1);
    }
}