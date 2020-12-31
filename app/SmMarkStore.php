<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmMarkStore extends Model
{
    public function className(){
        return $this->belongsTo('App\SmClass', 'class_id', 'id');
    }
     public function section()
    {
        return $this->belongsTo('App\SmSection', 'section_id', 'id');
    }
 
    public static function get_mark_by_part($student_id, $exam_id, $class_id, $section_id, $subject_id,$exam_setup_id){
    	
        try {
            $getMark= SmMarkStore::where([
                ['student_id',$student_id], 
                ['exam_term_id',$exam_id], 
                ['class_id',$class_id], 
                ['section_id',$section_id], 
                ['exam_setup_id',$exam_setup_id], 
                ['subject_id',$subject_id]
            ])->first();
            if(!empty($getMark)){
                $output= $getMark->total_marks;
            }else{
                $output= '0';
            }

            return $output;
        } catch (\Exception $e) {
            $data=[];
            return $data;
        }
    }

    public static function is_absent_check($student_id, $exam_id, $class_id, $section_id, $subject_id){
        
        try {
            $getMark= SmMarkStore::where([
                ['student_id',$student_id], 
                ['exam_term_id',$exam_id], 
                ['class_id',$class_id], 
                ['section_id',$section_id], 
                ['subject_id',$subject_id]
            ])->first();
            if(!empty($getMark)){
                $output= $getMark->is_absent;
            }else{
                $output= '0';
            }
            return $output;
        } catch (\Exception $e) {
            $data=[];
            return $data;
        }
    }

     public static function teacher_remarks($student_id, $exam_id, $class_id, $section_id, $subject_id){
        
        $getMark= SmMarkStore::where([
            ['student_id',$student_id], 
            ['exam_term_id',$exam_id], 
            ['class_id',$class_id], 
            ['section_id',$section_id], 
            ['subject_id',$subject_id]
        ])->first();

        if($getMark != ""){
            $output= $getMark->teacher_remarks;
        }else{
            $output= '';
        }

        return $output;
    }

    public static function allMarksArray($exam_id, $class_id, $section_id, $subject_id)
    {
        $all_student_marks = [];

        $marks = SmResultStore::where('class_id', $class_id)->where('section_id', $section_id)->where('subject_id', $subject_id)->where('exam_type_id', $exam_id)->get();

        foreach($marks as $mark){
            $all_student_marks[] = $mark->total_marks;
        }


        return $all_student_marks;

    }
}
