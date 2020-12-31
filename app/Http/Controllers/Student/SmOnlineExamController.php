<?php

namespace App\Http\Controllers\Student;

use App\SmStudent;
use App\YearCheck;
use App\SmOnlineExam;
use App\ApiBaseMethod;
use App\SmQuestionBank;
use App\SmGeneralSettings;
use Illuminate\Http\Request;
use App\SmQuestionBankMuOption;
use App\SmStudentTakeOnlineExam;
use Illuminate\Support\Facades\DB;
use App\SmOnlineExamQuestionAssign;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use App\SmStudentTakeOnlnExQuesOption;
use App\SmStudentTakeOnlineExamQuestion;

class SmOnlineExamController extends Controller
{

    public function __construct()
    {
        $this->middleware('PM');
        // User::checkAuth();
    }

    public function studentOnlineExam()
    {
        try {
            $time_zone_setup=SmGeneralSettings::join('sm_time_zones','sm_time_zones.id','=','sm_general_settings.time_zone_id')
            ->where('school_id',Auth::user()->school_id)->first();
            date_default_timezone_set($time_zone_setup->time_zone);
            
            $student = Auth::user()->student;
            $now = date('h:i:s');
            // return $now;
            // ->where('start_time', '<', $now)
            $online_exams = SmOnlineExam::where('active_status', 1)->where('academic_id', getAcademicId())->where('status', 1)->where('class_id', $student->class_id)->where('section_id', $student->section_id)->where('school_id', Auth::user()->school_id)->get();
// return getAcademicId();
            foreach ($online_exams as $online_exam) {
                $startTime = strtotime($online_exam->date . ' ' . $online_exam->start_time);
                $endTime = strtotime($online_exam->date . ' ' . $online_exam->end_time);
                $s = SmOnlineExam::find($online_exam->id);

                $now =  strtotime("now");
                if ($startTime <= $now && $now <= $endTime) {
                    $s->is_running  = 1;
                    $s->is_closed   = 0;
                    $s->is_waiting  = 0;
                } elseif ($startTime >= $now && $now <= $endTime) {
                    $s->is_waiting  = 1;
                    $s->is_running  = 0;
                    $s->is_closed   = 0;
                } elseif ($now >= $endTime) {
                    $s->is_closed   = 1;
                    $s->is_running  = 0;
                    $s->is_waiting  = 0;
                }
                $s->save();

                Log::info($s);
            }
            $marks_assigned =  SmStudentTakeOnlineExam::whereIn('online_exam_id', $online_exams->pluck('id')->toArray())->where('academic_id', getAcademicId())
                ->where('student_id', $student->id)->where('status', 2)->where('school_id', Auth::user()->school_id)->pluck('online_exam_id')->toArray();

            return view('backEnd.studentPanel.online_exam', compact('online_exams', 'marks_assigned', 'now','student'));
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function takeOnlineExam($id)
    {
        try {
            $online_exam = SmOnlineExam::find($id);
            $assigned_questions = SmOnlineExamQuestionAssign::where('online_exam_id', $online_exam->id)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();

            return view('backEnd.studentPanel.take_online_exam', compact('online_exam', 'assigned_questions'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function studentOnlineExamSubmit(Request $request)
    {
        // $question_option = 5;
        DB::beginTransaction();

        try {
            $student = Auth::user()->student;

            $take_online_exam = new SmStudentTakeOnlineExam();
            $take_online_exam->online_exam_id = $request->online_exam_id;
            $take_online_exam->student_id = $student->id;
            $take_online_exam->status = 1;
            $take_online_exam->school_id = Auth::user()->school_id;
            $take_online_exam->academic_id =  getAcademicId();
            $take_online_exam->save();
            $take_online_exam->toArray();

            foreach ($request->question_ids as $question_id) {
                $question_bank = SmQuestionBank::find($question_id);
                $trueFalse = 'trueOrFalse_' . $question_id;
                $trueFalse = $request->$trueFalse;

                $suitable_words = 'suitable_words_' . $question_id;
                $suitable_words = $request->$suitable_words;

                $exam_question = new SmStudentTakeOnlineExamQuestion();
                $exam_question->take_online_exam_id = $take_online_exam->id;
                $exam_question->question_bank_id = $question_id;
                $exam_question->trueFalse = $trueFalse;
                $exam_question->suitable_words = $suitable_words;
                $exam_question->school_id = Auth::user()->school_id;
                $exam_question->academic_id =  getAcademicId();
                $exam_question->save();
                $exam_question->toArray();
                if ($question_bank->type == "M") {
                    $question_options = SmQuestionBankMuOption::
                    where('question_bank_id', $question_bank->id)
                    ->where('academic_id', getAcademicId())
                    ->where('school_id', Auth::user()->school_id)
                    ->get();

                    $i = 0;
                    foreach ($question_options as $question_option) {
                        $options = 'options_' . $question_id . '_' . $i++;
                        // $options = $request->$options[$i++];
                        // dd($request->$options);

                        $exam_question_option = new SmStudentTakeOnlnExQuesOption();
                        $exam_question_option->take_online_exam_question_id = $exam_question->id;
                        $exam_question_option->title = $question_option->title;
                        $exam_question_option->school_id = Auth::user()->school_id;
                        $exam_question_option->academic_id =  getAcademicId();
                        if (isset($request->$options)) {
                            $exam_question_option->status = $request->$options;
                        } else {
                            $exam_question_option->status = 0;
                        }
                        $exam_question_option->save();
                    }
                }
            }

            DB::commit();
            Toastr::success('Operation successful', 'Success');
            return redirect('student-online-exam');

            // return redirect('student-online-exam')->with('message-success', 'Answer submitted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
        }
        Toastr::error('Operation Failed', 'Failed');
        return redirect()->back();
        // return redirect()->back()->with('message-danger', 'Something went wrong, please try again');
    }

    public function studentViewResult()
    {
        try {
            $result_views = SmStudentTakeOnlineExam::
            where('active_status', 1)->where('status', 2)
            ->where('academic_id', getAcademicId())
            ->where('student_id', @Auth::user()->student->id)
            ->where('school_id', Auth::user()->school_id)
            ->get();

            return view('backEnd.studentPanel.student_view_result', compact('result_views'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function studentAnswerScript($exam_id, $s_id)
    {
        try {
            $take_online_exam = SmStudentTakeOnlineExam::where('online_exam_id', $exam_id)->where('student_id', $s_id)->where('school_id', Auth::user()->school_id)->first();
            return view('backEnd.examination.online_answer_view_script_modal', compact('take_online_exam'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function studentOnlineExamApi(Request $request, $id)
    {

        try {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {

                $data = [];

                $student = SmStudent::where('user_id', $id)->where('school_id', Auth::user()->school_id)->first();

                $now = date('H:i:s');
                $today = date('Y-m-d');

                $online_exams = SmOnlineExam::where('sm_online_exams.status', '=', 1)
                    ->join('sm_subjects', 'sm_online_exams.class_id', '=', 'sm_subjects.id')
                    ->where('class_id', $student->class_id)
                    ->where('section_id', $student->section_id)
                    ->where('end_time', '>', $now)
                    ->where('date', '=', $today)
                    ->select('sm_online_exams.id as exam_id', 'sm_online_exams.title as exam_title', 'sm_subjects.subject_name', 'sm_online_exams.date', 'sm_online_exams.status as onlineExamStatus', 'sm_online_exams.status as onlineExamTakeStatus')
                    ->where('sm_online_exams.school_id', Auth::user()->school_id)->get();
                $examStatus = '0 = Pending , 1 Published';
                $examTakenStatus = '0 = Take Exam , 1 = Alreday Submitted';
                $data['online_exams'] = $online_exams->toArray();
                $data['online_exams_status'] = $examStatus;
                $data['onlineExamTakenStatus'] = $examTakenStatus;
                return ApiBaseMethod::sendResponse($data, null);
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function chooseExamApi(Request $request, $id)
    {
        try {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {

                $student = SmStudent::where('user_id', $id)->where('school_id', Auth::user()->school_id)->first();

                $student_exams = DB::table('sm_online_exams')
                    ->where('class_id', $student->class_id)
                    ->where('section_id', $student->section_id)
                    ->where('school_id', $student->school_id)
                    ->select('sm_online_exams.title as exam_name', 'id as exam_id')
                    ->where('school_id', Auth::user()->school_id)->get();
                return ApiBaseMethod::sendResponse($student_exams, null);
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function examResultApi(Request $request, $id, $exam_id)
    {
        try {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $student = SmStudent::where('user_id', $id)->where('school_id', Auth::user()->school_id)->first();

                $student_exams = DB::table('sm_online_exams')
                    ->where('class_id', $student->class_id)
                    ->where('section_id', $student->section_id)
                    ->where('school_id', $student->school_id)
                    ->select('sm_online_exams.title as exam_name', 'sm_online_exams.id as exam_id')
                    ->where('school_id', Auth::user()->school_id)->get();
                $exam_result = DB::table('sm_student_take_online_exams')
                    ->join('sm_online_exams', 'sm_online_exams.id', '=', 'online_exam_id')
                    ->join('sm_subjects', 'sm_online_exams.subject_id', '=', 'sm_subjects.id')
                    ->where('sm_student_take_online_exams.student_id', $student->id)
                    ->where('sm_student_take_online_exams.school_id', $student->school_id)
                    ->where('sm_online_exams.id', $exam_id)
                    ->where('sm_online_exams.status', '=', 1)
                    ->where('sm_online_exams.school_id', Auth::user()->school_id)
                    ->select(
                        'sm_online_exams.title as exam_name',
                        'sm_online_exams.id as exam_id',
                        'sm_subjects.subject_name',
                        'sm_student_take_online_exams.total_marks as obtained_marks',
                        'sm_online_exams.percentage as pass_mark_percentage',
                        'sm_student_take_online_exams.total_marks'
                    )
                    ->get();
                //dd($exam_result);
                $gradeArray = [];
                foreach ($exam_result  as $row) {

                    $mark = floor($row->obtained_marks);
                    $grades = DB::table('sm_marks_grades')
                        ->where('percent_from', '<=', $mark)
                        ->where('percent_upto', '>=', $mark)
                        ->select('grade_name')
                        ->where('school_id', Auth::user()->school_id)->first();
                    $gradeArray[] = array(
                        "grade" => $grades->grade_name,
                        "exam_id" => $row->exam_id,
                        "total_marks" => $row->total_marks,
                        "subject_name" => $row->subject_name,
                        "obtained_marks" => $row->obtained_marks,
                        "pass_mark" => $row->pass_mark_percentage,
                        "exam_name" => $row->exam_name
                    );
                }
                $data['student_exams'] = $student_exams->toArray();
                $data['exam_result'] = $gradeArray;
                return ApiBaseMethod::sendResponse($data, null);
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function getGrades(Request $request, $marks)
    {
        try {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $grades = DB::table('sm_marks_grades')
                    ->where('percent_from', '<=', floor($marks))
                    ->where('percent_upto', '>=', floor($marks))
                    ->select('grade_name')
                    ->where('school_id', Auth::user()->school_id)->first();
                return ApiBaseMethod::sendResponse($grades, null);
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}
