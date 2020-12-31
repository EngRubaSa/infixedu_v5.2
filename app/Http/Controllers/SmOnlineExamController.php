<?php

namespace App\Http\Controllers;
use App\SmClass;
use App\SmStaff;
use App\SmParent;
use App\SmSection;
use App\SmStudent;
use App\SmSubject;
use App\YearCheck;
use App\SmOnlineExam;
use App\ApiBaseMethod;
use App\SmNotification;
use App\SmQuestionBank;
use App\SmAssignSubject;
use App\SmOnlineExamMark;
use App\SmGeneralSettings;
use Illuminate\Http\Request;
use App\SmOnlineExamQuestion;
use App\SmStudentTakeOnlineExam;
use Illuminate\Support\Facades\DB;
use App\SmOnlineExamQuestionAssign;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use App\SmOnlineExamQuestionMuOption;
use Illuminate\Support\Facades\Validator;

class SmOnlineExamController extends Controller
{

    public function __construct()
	{
        $this->middleware('PM');
        // User::checkAuth();
	}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $time_zone_setup=SmGeneralSettings::join('sm_time_zones','sm_time_zones.id','=','sm_general_settings.time_zone_id')
        ->where('school_id',Auth::user()->school_id)->first();
        date_default_timezone_set($time_zone_setup->time_zone);
        try{
            $online_exams = SmOnlineExam::where('status', '!=', 2)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
             if (Auth::user()->role_id==1) {
                $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            } else {
                $teacher_info=SmStaff::where('user_id',Auth::user()->id)->first();
               $classes= SmAssignSubject::where('teacher_id',$teacher_info->id)->join('sm_classes','sm_classes.id','sm_assign_subjects.class_id')
               ->where('sm_assign_subjects.academic_id', getAcademicId())
               ->where('sm_assign_subjects.active_status', 1)
               ->where('sm_assign_subjects.school_id',Auth::user()->school_id)
               ->select('sm_classes.id','class_name')
               ->get();
            }
            $sections = SmSection::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            $subjects = SmSubject::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();

            $present_date_time = date("Y-m-d H:i:s");
            $present_time = date("H:i:s");

            return view('backEnd.examination.online_exam', compact('online_exams', 'classes', 'sections', 'subjects', 'present_date_time','present_time'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'class' => 'required',
            'section' => 'required',
            'subject' => 'required',
            'date' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'percentage' => 'required',
            'instruction' => 'required'
        ]);
        DB::beginTransaction();
        try{
            $date = strtotime($request->date);
            $newformat = date('Y-m-d', $date);
            $online_exam = new SmOnlineExam();
            $online_exam->title = $request->title;
            $online_exam->class_id = $request->class;
            $online_exam->section_id = $request->section;
            $online_exam->subject_id = $request->subject;
            $online_exam->date = date('Y-m-d', strtotime($request->date));
            $online_exam->start_time = date('H:i:s', strtotime($request->start_time));
            $online_exam->end_time = date('H:i:s', strtotime($request->end_time));
            $online_exam->end_date_time = date('Y-m-d H:i:s', strtotime($request->date . ' ' . $request->end_time));
            $online_exam->percentage = $request->percentage;
            $online_exam->instruction = $request->instruction;
            $online_exam->status = 0;
            $online_exam->school_id = Auth::user()->school_id;
            $online_exam->academic_id = getAcademicId();
            $result = $online_exam->save();
            if ($result) {
                // $data = SmStudent::where('class_id', $request->class)->where('section_id', $request->section)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
                // if (!empty($data)) {
                //     foreach ($data as $key => $value) {
                //         $notify = new SmNotification();
                //         $notify->date = date('Y-m-d', strtotime($request->date));
                //         $notify->message = 'Online exam - ' . $request->title;
                //         $notify->url = 'student-online-exam';
                //         $notify->user_id = $value->id;
                //         $notify->role_id = $value->role_id;
                //         $notify->created_by = Auth::user()->id;
                //         $notify->school_id = $value->school_id;
                //         $notify->save();
                //     }
                // }
                DB::commit();
                Toastr::success('Operation successful', 'Success');
                return redirect()->back();
            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        $time_zone_setup=SmGeneralSettings::join('sm_time_zones','sm_time_zones.id','=','sm_general_settings.time_zone_id')
        ->where('school_id',Auth::user()->school_id)->first();
        date_default_timezone_set($time_zone_setup->time_zone);

        try{
            $online_exams = SmOnlineExam::where('school_id',Auth::user()->school_id)->get();
            
             if (Auth::user()->role_id==1) {
                $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            } else {
                $teacher_info=SmStaff::where('user_id',Auth::user()->id)->first();
               $classes= SmAssignSubject::where('teacher_id',$teacher_info->id)->join('sm_classes','sm_classes.id','sm_assign_subjects.class_id')
               ->where('sm_assign_subjects.academic_id', getAcademicId())
               ->where('sm_assign_subjects.active_status', 1)
               ->where('sm_assign_subjects.school_id',Auth::user()->school_id)
               ->select('sm_classes.id','class_name')
               ->get();
            }
            $sections = SmSection::where('school_id',Auth::user()->school_id)->get();
            $subjects = SmSubject::where('school_id',Auth::user()->school_id)->get();
            // $online_exam = SmOnlineExam::find($id);
             if (checkAdmin()) {
                $online_exam = SmOnlineExam::find($id);
            }else{
                $online_exam = SmOnlineExam::where('id',$id)->where('school_id',Auth::user()->school_id)->first();
            }

            $present_date_time = date("Y-m-d H:i:s");
            $present_time = date("H:i:s");

            return view('backEnd.examination.online_exam', compact('online_exams', 'classes', 'sections', 'subjects', 'online_exam', 'present_date_time','present_time'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'class' => 'required',
            'section' => 'required',
            'subject' => 'required',
            'date' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'percentage' => 'required',
            'instruction' => 'required'
        ]);
        try{
            $date = strtotime($request->date);
            $newformat = date('Y-m-d', $date);
            // $online_exam = SmOnlineExam::find($id);
             if (checkAdmin()) {
                $online_exam = SmOnlineExam::find($id);
            }else{
                $online_exam = SmOnlineExam::where('id',$id)->where('school_id',Auth::user()->school_id)->first();
            }

            $online_exam->title = $request->title;
            $online_exam->class_id = $request->class;
            $online_exam->section_id = $request->section;
            $online_exam->subject_id = $request->subject;
            $online_exam->date = date('Y-m-d', strtotime($request->date));
            $online_exam->start_time = date('H:i:s', strtotime($request->start_time));
            $online_exam->end_time = date('H:i:s', strtotime($request->end_time));
            $online_exam->end_date_time = date('Y-m-d H:i:s', strtotime($request->date . ' ' . $request->end_time));
            $online_exam->percentage = $request->percentage;
            $online_exam->instruction = $request->instruction;

            $result = $online_exam->save();
            if ($result) {
                Toastr::success('Operation successful', 'Success');
                return redirect('online-exam');
            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function section()
    {

        try{
            $id = $_GET['id'];
            return response()->json(['response' => 'This is get method']);
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

  public function delete(Request $request)
    {
           ;
        try{
        $id_key = 'online_exam_id';
        $id = $request->online_exam_id;

        $tables = \App\tableList::getTableList($id_key, $id);
      

        try {
            if ($tables==null) {
                // $delete_query = SmOnlineExam::destroy($request->id);
                 if (checkAdmin()) {
                        $delete_query = SmOnlineExam::destroy($request->id);
                    }else{
                        $delete_query = SmOnlineExam::where('id',$request->id)->where('school_id',Auth::user()->school_id)->delete();
                    }

                if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                    if ($delete_query) {
                        return ApiBaseMethod::sendResponse(null, 'Online exam been deleted successfully');
                    } else {
                        return ApiBaseMethod::sendError('Something went wrong, please try again.');
                    }
                } else {
                    if ($delete_query) {
                        Toastr::success('Operation successful', 'Success');
                        return redirect()->back();
                    } else {
                        Toastr::error('Operation Failed', 'Failed');
                        return redirect()->back();
                    }
                }
            } else {
                $msg = 'This data already used in  : ' . $tables . ' Please remove those data first';
                Toastr::error($msg, 'Failed');
                return redirect()->back();
            }


        } catch (\Illuminate\Database\QueryException $e) {
            $msg = 'This data already used in  : ' . $tables . ' Please remove those data first';
            Toastr::error($msg, 'Failed');
            return redirect()->back();
            }
        }catch (\Exception $e) {
            //dd($e->where('academic_id', getAcademicId())->getMessage(), $e->errorInfo);
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function manageOnlineExamQuestion($id)
    {

        try{
            // $online_exam = SmOnlineExam::find($id);
             if (checkAdmin()) {
                $online_exam = SmOnlineExam::find($id);
            }else{
                $online_exam = SmOnlineExam::where('id',$id)->where('school_id',Auth::user()->school_id)->first();
            }
            $question_banks = SmQuestionBank::where('class_id', $online_exam->class_id)->where('section_id', $online_exam->section_id)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();

            $assigned_questions = SmOnlineExamQuestionAssign::where('online_exam_id', $id)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            $already_assigned = [];
            foreach ($assigned_questions as $assigned_question) {
                $already_assigned[] = $assigned_question->question_bank_id;
            }


            return view('backEnd.examination.manage_online_exam', compact('online_exam', 'question_banks', 'already_assigned'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function manageOnlineExamQuestionStore(Request $request)
    {


        try{
            if ($request->question_type != 'M') {
                $online_question = new SmOnlineExamQuestion();
                $online_question->online_exam_id = $request->online_exam_id;
                $online_question->type = $request->question_type;
                $online_question->mark = $request->mark;
                $online_question->title = $request->question_title;
                $online_question->school_id = Auth::user()->school_id;
                $online_question->academic_id = getAcademicId();
                if ($request->question_type == "F") {
                    $online_question->suitable_words = $request->suitable_words;
                } elseif ($request->question_type == "T") {
                    $online_question->trueFalse = $request->trueOrFalse;
                }
                $result = $online_question->save();
                if ($result) {
                    Toastr::success('Operation successful', 'Success');
                    return redirect()->back();
                } else {
                    Toastr::error('Operation Failed', 'Failed');
                    return redirect()->back();
                }
            } else {

                DB::beginTransaction();

                try {
                    $online_question = new SmOnlineExamQuestion();
                    $online_question->online_exam_id = $request->online_exam_id;
                    $online_question->type = $request->question_type;
                    $online_question->mark = $request->mark;
                    $online_question->title = $request->question_title;
                    $online_question->school_id = Auth::user()->school_id;
                    $online_question->academic_id = getAcademicId();
                    $online_question->save();
                    $online_question->toArray();
                    $i = 0;
                    if (isset($request->option)) {
                        foreach ($request->option as $option) {
                            $i++;
                            $option_check = 'option_check_' . $i;
                            $online_question_option = new SmOnlineExamQuestionMuOption();
                            $online_question_option->online_exam_question_id = $online_question->id;
                            $online_question_option->title = $option;
                            $online_question_option->school_id = Auth::user()->school_id;
                            $online_question_option->academic_id = getAcademicId();
                            if (isset($request->$option_check)) {
                                $online_question_option->status = 1;
                            } else {
                                $online_question_option->status = 0;
                            }
                            $online_question_option->save();
                        }
                    }
                    DB::commit();
                    Toastr::success('Operation successful', 'Success');
                    return redirect()->back();
                } catch (\Exception $e) {
                    DB::rollBack();
                }
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }
    public function onlineExamPublish($id)
    {

        try{
         $time_zone_setup=SmGeneralSettings::join('sm_time_zones','sm_time_zones.id','=','sm_general_settings.time_zone_id')
        ->where('school_id',Auth::user()->school_id)->first();
        date_default_timezone_set($time_zone_setup->time_zone);
            $present_date_time = date("Y-m-d H:i:s");

            // $publish = SmOnlineExam::find($id);
             if (checkAdmin()) {
                $publish = SmOnlineExam::find($id);
            }else{
                $publish = SmOnlineExam::where('id',$id)->where('school_id',Auth::user()->school_id)->first();
            }
            $class_id = $publish->class_id;
            $section_id = $publish->section_id;
            if ($present_date_time > $publish->end_date_time) {
                Toastr::error('Please update exam time', 'Failed');
                return redirect()->back();
            }
            $publish->status = 1;
            $publish->save();

            $students = SmStudent::where('class_id', $class_id)->where('section_id', $section_id)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();

            foreach ($students as $student) {
                $notification = new SmNotification;
                $notification->user_id = $student->user_id;
                $notification->role_id = 2;
                $notification->date = date('Y-m-d');
                $notification->message = 'New online exam published';
                $notification->school_id = Auth::user()->school_id;
                $notification->academic_id = getAcademicId();
                $notification->save();

                $parent = SmParent::find($student->parent_id);
                        $notidication = new SmNotification();
                        $notidication->role_id = 3;
                        $notidication->message = "New online exam published for your child";
                        $notidication->date = date('Y-m-d');
                        $notidication->user_id = $parent->user_id;
                        $notidication->academic_id = getAcademicId();
                        $notidication->save();
            }

            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function onlineExamPublishCancel($id)
    {

        try{
            // $publish = SmOnlineExam::find($id);
             if (checkAdmin()) {
                $publish = SmOnlineExam::find($id);
            }else{
                $publish = SmOnlineExam::where('id',$id)->where('school_id',Auth::user()->school_id)->first();
            }
            $publish->status = 3;
            $publish->save();
            Toastr::error('Exam Expired', 'Failed');
            return redirect()->back();
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function onlineQuestionEdit($id, $type, $examId)
    {

        try{
            $online_exam_question = SmOnlineExamQuestion::find($id);
            return view('backEnd.examination.online_exam_question_edit', compact('id', 'type', 'examId', 'online_exam_question'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function onlineExamQuestionEdit(Request $request)
    {

        try{
            if ($request->question_type != 'M') {
                // $online_question = SmOnlineExamQuestion::find($request->id);
                 if (checkAdmin()) {
                        $online_question = SmOnlineExam::find($request->id);
                    }else{
                        $online_question = SmOnlineExam::where('id',$request->id)->where('school_id',Auth::user()->school_id)->first();
                    }
                $online_question->online_exam_id = $request->online_exam_id;
                $online_question->type = $request->question_type;
                $online_question->mark = $request->mark;
                $online_question->title = $request->question_title;
                if ($request->question_type == "F") {
                    $online_question->suitable_words = $request->suitable_words;
                } elseif ($request->question_type == "T") {
                    $online_question->trueFalse = $request->trueOrFalse;
                }
                $result = $online_question->save();
                if ($result) {
                    Toastr::success('Operation successful', 'Success');
                    return redirect()->back();
                } else {
                    Toastr::error('Operation Failed', 'Failed');
                    return redirect()->back();
                }
            } else {

                DB::beginTransaction();

                try {
                    // $online_question = SmOnlineExamQuestion::find($request->id);
                    if (checkAdmin()) {
                        $online_question = SmOnlineExamQuestion::find($request->id);
                    }else{
                        $online_question = SmOnlineExamQuestion::where('id',$request->id)->where('school_id',Auth::user()->school_id)->first();
                    }
                    $online_question->online_exam_id = $request->online_exam_id;
                    $online_question->type = $request->question_type;
                    $online_question->mark = $request->mark;
                    $online_question->title = $request->question_title;
                    $online_question->save();
                    $online_question->toArray();

                    SmOnlineExamQuestionMuOption::where('online_exam_question_id', $online_question->id)->delete();

                    $i = 0;
                    if (isset($request->option)) {
                        foreach ($request->option as $option) {
                            $i++;
                            $option_check = 'option_check_' . $i;
                            $online_question_option = new SmOnlineExamQuestionMuOption();
                            $online_question_option->online_exam_question_id = $online_question->id;
                            $online_question_option->title = $option;
                            $online_question_option->school_id = Auth::user()->school_id;
                            $online_question_option->academic_id = getAcademicId();
                            if (isset($request->$option_check)) {
                                $online_question_option->status = 1;
                            } else {
                                $online_question_option->status = 0;
                            }
                            $online_question_option->save();
                        }
                    }
                    DB::commit();
                    Toastr::success('Operation successful', 'Success');
                    return redirect()->back();
                } catch (\Exception $e) {
                    DB::rollBack();
                }
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function onlineExamQuestionDelete(Request $request)
    {

        try{
            // $online_exam_question = SmOnlineExamQuestion::find($request->id);
            if (checkAdmin()) {
                        $online_exam_question = SmOnlineExamQuestion::find($request->id);
                    }else{
                        $online_exam_question = SmOnlineExamQuestion::where('id',$request->id)->where('school_id',Auth::user()->school_id)->first();
                    }
            if ($online_exam_question->type == "M") {
                SmOnlineExamQuestionMuOption::where('online_exam_question_id', $online_exam_question->id)->delete();
                $online_exam_question->delete();
            } else {
                $online_exam_question->delete();
            }
            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function onlineExamMarksRegister($id)
    {


        try{
            // $online_exam_question = SmOnlineExam::find($id);
             if (checkAdmin()) {
                        $online_exam_question = SmOnlineExam::find($id);
                    }else{
                        $online_exam_question = SmOnlineExam::where('id',$id)->where('school_id',Auth::user()->school_id)->first();
                    }
            $students = SmStudent::where('class_id', $online_exam_question->class_id)->where('section_id', $online_exam_question->section_id)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            $present_students = [];
            foreach ($students as $student) {
                $take_exam = SmStudentTakeOnlineExam::where('student_id', $student->id)->where('online_exam_id', $online_exam_question->id)->first();
                if ($take_exam != "") {
                    $present_students[] = $student->id;
                }
            }

            return view('backEnd.examination.online_exam_marks_register', compact('online_exam_question', 'students', 'present_students'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function onlineExamMarksStore(Request $request)
    {

        try{
            // SmOnlineExamMark::where('exam_id', $request->exam_id)->delete();
             if (checkAdmin()) {
                       SmOnlineExamMark::where('exam_id', $request->exam_id)->delete();
                    }else{
                       SmOnlineExamMark::where('exam_id', $request->exam_id)->where('school_id',Auth::user()->school_id)->delete();
                    }
            $counter = 0;
            foreach ($request->students as $student) {
                $counter++;

                $marks = 'marks_' . $counter;
                $abs = 'abs_' . $counter;

                $online_mark = new SmOnlineExamMark();
                $online_mark->exam_id = $request->exam_id;
                $online_mark->subject_id = $request->subject_id;
                $online_mark->student_id = $student;
                $online_mark->school_id = Auth::user()->school_id;
                $online_mark->academic_id = getAcademicId();
                if (isset($request->$abs)) {
                    $online_mark->abs = $request->$abs;
                } else {
                    $online_mark->marks = $request->$marks;
                    $online_mark->abs = 0;
                }
                $online_mark->save();
            }
            Toastr::success('Operation successful', 'Success');
            return redirect('online-exam');
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }
    public function onlineExamResult($id)
    {
        try{
            // $online_exam_question = SmOnlineExam::find($id);
             if (checkAdmin()) {
                $online_exam_question = SmOnlineExam::find($id);
            }else{
                $online_exam_question = SmOnlineExam::where('id',$id)->where('school_id',Auth::user()->school_id)->first();
            }
            $students = SmStudent::where('class_id', $online_exam_question->class_id)->where('section_id', $online_exam_question->section_id)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            $present_students = [];
            foreach ($students as $student) {
                $take_exam = SmStudentTakeOnlineExam::where('student_id', $student->id)->where('online_exam_id', $online_exam_question->id)->first();
                if ($take_exam != "") {
                    $present_students[] = $student->id;
                }
            }
            $total_marks = 0;
            foreach ($online_exam_question->assignQuestions as $assignQuestion) {
                $total_marks = $total_marks + $assignQuestion->questionBank->marks;
            }
            return view('backEnd.examination.online_exam_result_view', compact('online_exam_question', 'students', 'present_students', 'total_marks'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }



    public function onlineExamQuestionAssign(Request $request)
    {

        try{
            SmOnlineExamQuestionAssign::where('online_exam_id', $request->online_exam_id)->delete();
             if (checkAdmin()) {
               SmOnlineExamQuestionAssign::where('online_exam_id', $request->online_exam_id)->delete();
            }else{
               SmOnlineExamQuestionAssign::where('online_exam_id', $request->online_exam_id)->where('school_id',Auth::user()->school_id)->delete();
            }
            if (isset($request->questions)) {
                foreach ($request->questions as $question) {
                    $assign = new SmOnlineExamQuestionAssign();
                    $assign->online_exam_id = $request->online_exam_id;
                    $assign->question_bank_id = $question;
                    $assign->school_id = Auth::user()->school_id;
                    $assign->academic_id = getAcademicId();
                    $assign->save();
                }
                Toastr::success('Operation successful', 'Success');
                return redirect('online-exam');
            }
            Toastr::error('No question is assigned', 'Failed');
            return redirect()->back();
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function viewOnlineQuestionModal($id)
    {

        try{
            // $question_bank = SmQuestionBank::find($id);
            if (checkAdmin()) {
                $question_bank = SmQuestionBank::find($id);
            }else{
                $question_bank = SmQuestionBank::where('id',$id)->where('school_id',Auth::user()->school_id)->first();
            }
            return view('backEnd.examination.online_eaxm_question_view_modal', compact('question_bank'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function onlineExamMarking($exam_id, $s_id)
    {

        try{
            $take_online_exam = SmStudentTakeOnlineExam::where('online_exam_id', $exam_id)->where('student_id', $s_id)->where('academic_id', getAcademicId())->first();
            return view('backEnd.examination.online_answer_marking', compact('take_online_exam'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function onlineExamMarkingStore(Request $request)
    {


        try{
            $online_take_exam_mark = SmStudentTakeOnlineExam::where('online_exam_id', $request->online_exam_id)->where('student_id', $request->student_id)->where('academic_id', getAcademicId())->first();
            $total_marks = 0;
            if (isset($request->marks)) {
                foreach ($request->marks as $mark) {
                    $question_marks = SmQuestionBank::select('marks')->where('id', $mark)->where('academic_id', getAcademicId())->first();
                    $total_marks = $total_marks + $question_marks->marks;
                }
            }
            $online_take_exam_mark->total_marks = $total_marks;
            $online_take_exam_mark->status = 2;
            $online_take_exam_mark->save();
            Toastr::success('Operation successful', 'Success');
            return redirect('online-exam-marks-register/' . $request->online_exam_id);
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function onlineExamReport(Request $request)
    {
        try {
            
            $exams = SmOnlineExam::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['exams'] = $exams->toArray();
                $data['classes'] = $classes->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }
            return view('backEnd.reports.online_exam_report', compact('exams', 'classes'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }




    public function onlineExamReportSearch(Request $request)
    {

        $input = $request->all();
        $validator = Validator::make($input, [
            'exam' => 'required',
            'class' => 'required',
            'section' => 'required'
        ]);
        

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try{
            date_default_timezone_set("Asia/Dhaka");
            $present_date_time = date("Y-m-d H:i:s");

            // $online_exam_question = SmOnlineExam::find($request->exam);
            if (checkAdmin()) {
                $online_exam_question = SmOnlineExam::find($request->exam);
            }else{
                $online_exam_question = SmOnlineExam::where('id',$request->exam)->where('school_id',Auth::user()->school_id)->first();
            }
            $students = SmStudent::where('class_id', $request->class)->where('section_id', $request->section)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();

            $online_exam = SmOnlineExam::where('class_id', $request->class)->where('section_id', $request->section)->where('id', $request->exam)->where('end_date_time', '<', $present_date_time)->where('status', 1)->first();


            if ($students->count() == 0 && $online_exam == "") {
                Toastr::error('No Result Found', 'Failed');
                return redirect('online-exam-report');
            }
            $present_students = [];
            foreach ($students as $student) {
                $take_exam = SmStudentTakeOnlineExam::where('student_id', $student->id)->where('online_exam_id', $online_exam_question->id)->first();
                if ($take_exam != "") {
                    $present_students[] = $student->id;
                }
            }
            $total_marks = 0;
            foreach ($online_exam_question->assignQuestions as $assignQuestion) {
                $total_marks = $total_marks + $assignQuestion->questionBank->marks;
            }
            $exams = SmOnlineExam::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            
             if (Auth::user()->role_id==1) {
                $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            } else {
                $teacher_info=SmStaff::where('user_id',Auth::user()->id)->first();
               $classes= SmAssignSubject::where('teacher_id',$teacher_info->id)->join('sm_classes','sm_classes.id','sm_assign_subjects.class_id')
               ->where('sm_assign_subjects.academic_id', getAcademicId())
               ->where('sm_assign_subjects.active_status', 1)
               ->where('sm_assign_subjects.school_id',Auth::user()->school_id)
               ->select('sm_classes.id','class_name')
               ->get();
            }
            $class_id = $request->class;
            $exam_id = $request->exam;
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['online_exam_question'] = $online_exam_question;
                $data['students'] = $students->toArray();
                $data['present_students'] = $present_students;
                $data['total_marks'] = $total_marks;
                $data['exams'] = $exams->toArray();
                $data['classes'] = $classes->toArray();
                $data['class_id'] = $class_id;
                $data['exam_id'] = $exam_id;
                return ApiBaseMethod::sendResponse($data, null);
            }
            $clas = SmClass::find($request->class);
            $sec = SmSection::find($request->section);
            return view('backEnd.reports.online_exam_report', compact('online_exam_question', 'students', 'present_students', 'total_marks', 'exams', 'classes', 'class_id', 'exam_id','clas','sec'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }
}