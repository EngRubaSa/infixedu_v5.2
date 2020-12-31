<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\SmMarkStore;
class SmResultStore extends Model
{
    
    public function studentInfo(){
    	return $this->belongsTo('App\SmStudent', 'student_id', 'id');
    }
    public function exam(){
        return $this->belongsTo(SmExamType::class, 'exam_type_id');
    }

    public function subject(){
        return $this->belongsTo('App\SmSubject', 'subject_id', 'id');
    }
    public function className(){
        return $this->belongsTo('App\SmClass', 'class_id', 'id');
    }
     public function section()
    {
        return $this->belongsTo('App\SmSection', 'section_id', 'id');
    }
    public static function  GetResultBySubjectId($class_id, $section_id, $subject_id,$exam_id,$student_id){
    	
        try {
            $data = SmMarkStore::where([
                ['class_id',$class_id],
                ['section_id',$section_id],
                ['exam_term_id',$exam_id],
                ['student_id',$student_id],
                ['subject_id',$subject_id]
            ])->get();
            return $data;
        } catch (\Exception $e) {
            $data=[];
            return $data;
        }
    }

    public static function  GetFinalResultBySubjectId($class_id, $section_id, $subject_id,$exam_id,$student_id){
        
        try {
            $data = SmResultStore::where([
                ['class_id',$class_id],
                ['section_id',$section_id],
                ['exam_type_id',$exam_id],
                ['student_id',$student_id],
                ['subject_id',$subject_id]
                ])->first();

                return $data;
        } catch (\Exception $e) {
            $data=[];
            return $data;
        }
    }




}
