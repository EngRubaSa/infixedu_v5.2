<?php

namespace App;

use App\SmExamSetup;
use App\SmStudent;
use App\SmMarkStore;
use App\SmMarksGrade;
use App\SmOptionalSubjectAssign;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class SmAssignSubject extends Model
{
    public function subject()
    {
        return $this->belongsTo('App\SmSubject', 'subject_id', 'id');
    }

    public function results()
    {
        return $this->hasMany(SmResultStore::class, 'subject_id', 'subject_id');
    }
    public function resultBySubject()
    {
        return $this->hasMany(SmResultStore::class, 'subject_id', 'subject_id')->where('section_id', $this->section_id)
            ->where('class_id', $this->class_id);
    }

    public function className()
    {
        return $this->belongsTo('App\SmClass', 'class_id', 'id');
    }

    public function teacher()
    {
        return $this->belongsTo('App\SmStaff', 'teacher_id', 'id');
    }

    public function section()
    {
        return $this->belongsTo('App\SmSection', 'section_id', 'id');
    }

    public function examSetups()
    {
        return $this->hasMany(SmExamSetup::class, 'class_id', 'class_id')->where('class_id', $this->class_id)
            ->where('section_id', $this->section_id);
    }

    public function markBySubject()
    {
        return $this->hasMany(SmMarkStore::class, 'subject_id', 'subject_id')->where('section_id', $this->section_id)
            ->where('class_id', $this->class_id);
    }
    public function exam()
    {
        return $this->hasOne(SmExam::class,'subject_id','subject_id');
    }

    public function examSchedule()
    {
        return $this->hasMany(SmExamSchedule::class,'subject_id','subject_id')
            ->where('class_id', $this->class_id)->where('section_id', $this->section_id);
    }

    public static function getNumberOfPart($subject_id, $class_id, $section_id, $exam_term_id)
    {
        try {
            $results = SmExamSetup::where([
                ['class_id', $class_id],
                ['subject_id', $subject_id],
                ['section_id', $section_id],
                ['exam_term_id', $exam_term_id],
            ])->get();
            return $results;
        } catch (\Exception $e) {
            $data = [];
            return $data;
        }
    }

    public static function getNumberOfPartStudent($subject_id, $class_id, $section_id, $exam_term_id)
    {
        try {
            $results = SmExamSetup::where([
                ['class_id', $class_id],
                ['subject_id', $subject_id],
                ['section_id', $section_id],
                ['exam_term_id', $exam_term_id]
            ])->get();
            return $results;
        } catch (\Exception $e) {
            $data = [];
            return $data;
        }
    }

    public static function getMarksOfPart($student_id, $subject_id, $class_id, $section_id, $exam_term_id)
    {
        try {
            $results = SmMarkStore::where([
                ['student_id', $student_id],
                ['class_id', $class_id],
                ['subject_id', $subject_id],
                ['section_id', $section_id],
                ['exam_term_id', $exam_term_id],
            ])->get();
            return $results;
        } catch (\Exception $e) {
            $data = [];
            return $data;
        }
    }

    public static function getSumMark($student_id, $subject_id, $class_id, $section_id, $exam_term_id)
    {
        // $student_info=SmStudent::findOrfail($student_id);
        // $optional_subject=SmOptionalSubjectAssign::where('student_id','=',$student_id)->where('session_id','=', $student_info->session_id)->first();

        // if ($optional_subject!='') {
        //     # code...
        // } else {
        //     # code...
        // }
        
        try {
            $results = SmMarkStore::where([
                ['student_id', $student_id],
                ['class_id', $class_id],
                ['subject_id', $subject_id],
                ['section_id', $section_id],
                ['exam_term_id', $exam_term_id],
            ])->sum('total_marks');
            return $results;
        } catch (\Exception $e) {
            $data = [];
            return $data;
        }
    }

    public static function getHighestMark($subject_id, $class_id, $section_id, $exam_term_id)
    {
        try {
            $results = DB::table('sm_mark_stores')
                ->select('student_id', DB::raw('SUM(total_marks) as total_amount'))
                ->where([
                    ['class_id', $class_id],
                    ['subject_id', $subject_id],
                    ['section_id', $section_id],
                    ['exam_term_id', $exam_term_id]
                ])
                ->groupBy('student_id')
                ->get();
            $totalMark = [];
            foreach ($results as $result) {
                $totalMark[] = $result->total_amount;
            }
            return max($totalMark);
            $results = SmMarkStore::groupBy('student_id')
                ->selectRaw('sum(total_marks) as sum, student_id')
                ->where([
                    ['class_id', $class_id],
                    ['subject_id', $subject_id],
                    ['section_id', $section_id],
                    ['exam_term_id', $exam_term_id],
                ])
                ->select('sum', 'student_id');
            return $results;
        } catch (\Exception $e) {
            $data = [];
            return $data;
        }
    }

    public static function getSubjectMark($subject_id, $class_id, $section_id, $exam_term_id)
    {
        try {
            $results = SmExamSetup::where([
                ['class_id', $class_id],
                ['subject_id', $subject_id],
                ['section_id', $section_id],
                ['exam_term_id', $exam_term_id],
            ])->sum('exam_mark');
            return $results;
        } catch (\Exception $e) {
            $data = [];
            return $data;
        }
    }


    public static function get_student_result($student_id, $subject_id, $class_id, $section_id, $exam_term_id, $optional_subject_id, $optional_subject_setup)
    {
        try {
            $this_student_failed = 0;
            $total_gpa_point = 0;
            $student_info = SmStudent::where('id', '=', $student_id)->first();
            $optional_subject = SmOptionalSubjectAssign::where('student_id', '=', $student_info->id)->where('session_id', '=', $student_info->session_id)->first();
            $subjects = SmAssignSubject::where([['class_id', $class_id], ['section_id', $section_id]])->get();
            $assign_subjects = SmAssignSubject::where([['class_id', $class_id], ['section_id', $section_id]])->get();
            foreach ($subjects as $row) {
                $subject_id = $row->subject_id;
                $total_mark = SmAssignSubject::getSumMark($student_id, $subject_id, $class_id, $section_id, $exam_term_id);
                $mark_grade = SmMarksGrade::where([['percent_from', '<=', $total_mark], ['percent_upto', '>=', $total_mark]])->first();
                $optional_subject_id = '';
                if (!empty($optional_subject)) {
                    $optional_subject_id = $optional_subject->subject_id;
                }
                if ($subject_id == $optional_subject_id) {

                    // return $optional_subject_id;
                    if ($mark_grade->gpa < $optional_subject_setup->gpa_above) {
                        $total_gpa_point = $total_gpa_point + 0;
                        if ($mark_grade->gpa < 1) {
                            $this_student_failed = 1;
                        }
                    } else {
                        $optional_mark_grade = $mark_grade->gpa - $optional_subject_setup->gpa_above;
                        $total_gpa_point = $total_gpa_point + $optional_mark_grade;
                        if ($mark_grade->gpa < 1) {
                            $this_student_failed = 1;
                        }
                    }
                } else {
                    $total_gpa_point = $total_gpa_point + $mark_grade->gpa;
                    if ($mark_grade->gpa < 1) {
                        $this_student_failed = 1;
                    }
                }
            }
            if ($this_student_failed != 1) {
                if ($optional_subject_id != '') {
                    $number_of_subject = count($assign_subjects);
                    $number_of_subject = $number_of_subject - 1;
                    if ($total_gpa_point != 0 && $number_of_subject != "") {
                        $final_result = number_format($total_gpa_point / $number_of_subject, 2, '.', ' ');
                        return $final_result;
                    } else {
                        return '0.00';
                    }
                } else {
                    $number_of_subject = count($assign_subjects);

                    if ($total_gpa_point != 0 && $number_of_subject != "") {
                        $final_result = number_format($total_gpa_point / $number_of_subject, 2, '.', ' ');
                        return $final_result;
                    } else {
                        return '0.00';
                    }
                }
            } else {
                return '0.00';
            }
        } catch (\Exception $e) {
            $data = [];
            return $data;
        }
    }

    public static function get_student_result_without_optional($student_id, $subject_id, $class_id, $section_id, $exam_term_id, $optional_subject_id, $optional_subject_setup)
    {
        try {
            $this_student_failed = 0;
            $total_gpa_point = 0;
            $student_info = SmStudent::where('id', '=', $student_id)->first();
            $optional_subject = SmOptionalSubjectAssign::where('student_id', '=', $student_info->id)->where('session_id', '=', $student_info->session_id)->first();

            $subjects = SmAssignSubject::where([['class_id', $class_id], ['section_id', $section_id]])->get();
            $assign_subjects = SmAssignSubject::where([['class_id', $class_id], ['section_id', $section_id]])->get();
            foreach ($subjects as $row) {
                $subject_id = $row->subject_id;
                $total_mark = SmAssignSubject::getSumMark($student_id, $subject_id, $class_id, $section_id, $exam_term_id);
                $mark_grade = SmMarksGrade::where([['percent_from', '<=', $total_mark], ['percent_upto', '>=', $total_mark]])->first();
                $optional_subject_id = '';
                if (!empty($optional_subject)) {
                    $optional_subject_id = $optional_subject->subject_id;
                }
                $total_gpa_point = $total_gpa_point + $mark_grade->gpa;
                if ($mark_grade->gpa < 1) {
                    $this_student_failed = 1;
                }
            }
            if ($this_student_failed != 1) {
                if ($optional_subject_id != '') {

                    $number_of_subject = count($assign_subjects);
                    if ($total_gpa_point != 0 && $number_of_subject != "") {
                        $final_result = number_format($total_gpa_point / $number_of_subject, 2, '.', ' ');
                        return $final_result;
                    } else {
                        return '0.00';
                    }

                } else {
                    $number_of_subject = count($assign_subjects);

                    if ($total_gpa_point != 0 && $number_of_subject != "") {
                        $final_result = number_format($total_gpa_point / $number_of_subject, 2, '.', ' ');
                        return $final_result;
                    } else {
                        return '0.00';
                    }
                }

            } else {
                return '0.00';
            }
        } catch (\Exception $e) {
            $data = [];
            return $data;
        }
    }

    public static function subjectPosition($subject_id, $class_id, $custom_result)
    {

        $students = SmStudent::where('class_id', $class_id)->get();

        $subject_mark_array = [];
        foreach ($students as $student) {
            $subject_marks = 0;

            $first_exam_mark = SmMarkStore::where('student_id', $student->id)->where('class_id', $class_id)->where('subject_id', $subject_id)->where('exam_term_id', $custom_result->exam_term_id1)->sum('total_marks');

            $subject_marks = $subject_marks + $first_exam_mark / 100 * $custom_result->percentage1;

            $second_exam_mark = SmMarkStore::where('student_id', $student->id)->where('class_id', $class_id)->where('subject_id', $subject_id)->where('exam_term_id', $custom_result->exam_term_id2)->sum('total_marks');

            $subject_marks = $subject_marks + $second_exam_mark / 100 * $custom_result->percentage2;

            $third_exam_mark = SmMarkStore::where('student_id', $student->id)->where('class_id', $class_id)->where('subject_id', $subject_id)->where('exam_term_id', $custom_result->exam_term_id3)->sum('total_marks');

            $subject_marks = $subject_marks + $third_exam_mark / 100 * $custom_result->percentage3;

            $subject_mark_array[] = round($subject_marks);


        }

        arsort($subject_mark_array);

        $position_array = [];
        foreach ($subject_mark_array as $position_mark) {
            $position_array[] = $position_mark;
        }


        return $position_array;

    }
}
