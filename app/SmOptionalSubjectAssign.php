<?php

namespace App; 
use Illuminate\Database\Eloquent\Model;

class SmOptionalSubjectAssign extends Model
{

    public static function is_optional_subject( $student_id, $subject_id){
        // $result = SmOptionalSubjectAssign::where('student_id',$student_id )->where('subject_id',$subject_id)->first();
        // if($result){
        //     return TRUE;
        // }else{
        //     return FALSE; 
        // }

        try {
            $result = SmOptionalSubjectAssign::where('student_id',$student_id )->where('subject_id',$subject_id)->first();
            if($result){
                return TRUE;
            }else{
                return FALSE; 
            }
        } catch (\Exception $e) {
            return FALSE; 
        }

    }
    
}
