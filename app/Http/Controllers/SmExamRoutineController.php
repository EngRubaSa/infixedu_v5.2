<?php

namespace App\Http\Controllers;

use App\SmExam;
use App\SmClass;
use App\SmStaff;
use App\SmParent;
use App\SmHoliday;
use App\SmSection;
use App\SmStudent;
use App\SmSubject;
use App\YearCheck;
use App\SmExamType;
use App\SmClassRoom;
use App\SmClassTime;
use App\ApiBaseMethod;
use App\SmAcademicYear;
use App\SmExamSchedule;
use App\SmNotification;
use App\SmAssignSubject;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;

class SmExamRoutineController extends Controller
{

    public function __construct()
	{
        $this->middleware('PM');
        // User::checkAuth();
	}

    public function examSchedule()
    {

        try {
            $exam_types = SmExamType::where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
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
            return view('backEnd.examination.exam_schedule', compact('classes', 'exam_types'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function examScheduleCreate()
    {

        // try {
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
            $exams = SmExam::where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            $exam_types = SmExamType::where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            return view('backEnd.examination.exam_schedule_create', compact('classes', 'exams', 'exam_types'));
        // } catch (\Exception $e) {
        //     Toastr::error('Operation Failed', 'Failed');
        //     return redirect()->back();
        // }
    }

    public function examScheduleSearch(Request $request)
    {
        $request->validate([
            'exam' => 'required',
            'class' => 'required',
            'section' => 'required',
        ]);

        try {
            $assign_subjects = SmAssignSubject::where('class_id', $request->class)->where('section_id', $request->section)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            if ($assign_subjects->count() == 0) {
                Toastr::success('No Subject Assigned. Please assign subjects in this class.', 'Success');
                return redirect('exam-schedule-create');
            }

            // foreach($assign_subjects as $assign_subject){
            //     $exam_setups = SmExamSetup::where('exam_term_id', $request->exam)->where('class_id', $request->class)->where('section_id', $request->section)->where('subject_id', $assign_subject->subject_id)->where('school_id',Auth::user()->school_id)->first();
            //     if($exam_setups == ""){
            //         return redirect('exam-schedule-create')->with('message-danger', 'Not exam setup yet, Please setup exam for the class & section.');
            //     }
            // }
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
            $exams = SmExam::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            $class_id = $request->class;
            $section_id = $request->section;
            $exam_id = $request->exam;

            $exam_types = SmExamType::where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();

            $exam_periods = SmClassTime::where('type', 'exam')->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();

            return view('backEnd.examination.exam_schedule_create', compact('classes', 'exams', 'assign_subjects', 'class_id', 'section_id', 'exam_id', 'exam_types', 'exam_periods'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function addExamRoutineModal($subject_id, $exam_period_id, $class_id, $section_id, $exam_term_id)
    {

        try {
            $rooms = SmClassRoom::where('active_status', 1)->where('school_id',Auth::user()->school_id)->get();
            return view('backEnd.examination.add_exam_routine_modal', compact('subject_id', 'exam_period_id', 'class_id', 'section_id', 'exam_term_id', 'rooms'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function checkExamRoutinePeriod(Request $request)
    {

        try {
            $exam_period_check = SmExamSchedule::where('class_id', $request->class_id)->where('section_id', $request->section_id)->where('exam_period_id', $request->exam_period_id)->where('exam_term_id', $request->exam_term_id)->where('date', date('Y-m-d', strtotime($request->date)))->where('school_id',Auth::user()->school_id)->first();

            return response()->json(['exam_period_check' => $exam_period_check]);
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function EditExamRoutineModal($subject_id, $exam_period_id, $class_id, $section_id, $exam_term_id, $assigned_id)
    {

        try {
            $rooms = SmClassRoom::where('active_status', 1)->where('school_id',Auth::user()->school_id)->get();
            $assigned_exam = SmExamSchedule::find($assigned_id);
            return view('backEnd.examination.add_exam_routine_modal', compact('subject_id', 'exam_period_id', 'class_id', 'section_id', 'exam_term_id', 'rooms', 'assigned_exam'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function deleteExamRoutineModal($assigned_id)
    {

        try {
            return view('backEnd.examination.delete_exam_routine', compact('assigned_id'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function deleteExamRoutine($assigned_id)
    {

        try {
            $exam_routine = SmExamSchedule::find($assigned_id);

            $class_id = $exam_routine->class_id;
            $section_id = $exam_routine->section_id;
            $exam_term_id = $exam_routine->exam_term_id;

            $result = $exam_routine->delete();

            if ($result) {
                Toastr::success('Exam routine has been deleted successfully', 'Success');
                return redirect('exam-routine-view/' . $class_id . '/' . $section_id . '/' . $exam_term_id);
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function addExamRoutineStore(Request $request)
    {

        DB::statement('SET FOREIGN_KEY_CHECKS=0');


  try{
            $valid = SmExamSchedule::where(['exam_period_id'=>$request->exam_period_id,'date'=> date('Y-m-d', strtotime($request->date)),'room_id' => $request->room ])->where('school_id',Auth::user()->school_id)->first();
            if (!is_null($valid)) {
                Toastr::error('Exam schedule already assigned!', 'Failed');
                return redirect()->back();
                // return redirect()->back()->with('message-danger', 'Exam schedule already assigned!');
            }

        $valid = SmExamSchedule::where(['exam_period_id'=>$request->exam_period_id,'date'=> date('Y-m-d', strtotime($request->date)),'room_id' => $request->room ])->where('school_id',Auth::user()->school_id)->first();
        if (!is_null($valid)) {
            Toastr::success('Exam schedule already assigned!', 'Success');
            return redirect()->back();
        }

        if ($request->assigned_id == "") {

            $exam_routine = new SmExamSchedule();
            $exam_routine->class_id = $request->class_id;
            $exam_routine->section_id = $request->section_id;
            $exam_routine->subject_id = $request->subject_id;

            $exam_routine->exam_period_id = $request->exam_period_id;
            $exam_routine->exam_term_id = $request->exam_term_id;
            $exam_routine->room_id = $request->room;
            $exam_routine->date = date('Y-m-d', strtotime($request->date));
            $exam_routine->school_id = Auth::user()->school_id;
            $exam_routine->academic_id = getAcademicId();
            $result = $exam_routine->save();


            $students = SmStudent::where('class_id', $request->class_id)->where('section_id', $request->section_id)->where('active_status', 1)->get();
                    foreach ($students as $key => $student) {
                        $notidication = new SmNotification();
                        $notidication->role_id = $student->role_id;
                        $notidication->message = "New exam scheduled";
                        $notidication->date = date('Y-m-d');
                        $notidication->user_id = $student->user_id;
                        $notidication->academic_id = getAcademicId();
                        $notidication->save();

                        $parent = SmParent::find($student->parent_id)->first();

                        $notidication = new SmNotification();
                        $notidication->role_id = 3;
                        $notidication->message = "New exam scheduled for your child";
                        $notidication->date = date('Y-m-d');
                        $notidication->user_id = $parent->user_id;
                        $notidication->academic_id = getAcademicId();
                        $notidication->save();
                    }



            Toastr::success('Exam routine has been assigned successfully', 'Success');
        } else {

            $exam_routine = SmExamSchedule::find($request->assigned_id);
            $exam_routine->class_id = $request->class_id;
            $exam_routine->section_id = $request->section_id;
            $exam_routine->subject_id = $request->subject_id;
            $exam_routine->exam_period_id = $request->exam_period_id;
            $exam_routine->exam_term_id = $request->exam_term_id;
            $exam_routine->room_id = $request->room;
            $exam_routine->date = date('Y-m-d', strtotime($request->date));
            $result = $exam_routine->save();

            $students = SmStudent::where('class_id', $request->class_id)->where('section_id', $request->section_id)->where('active_status', 1)->get();
            foreach ($students as $key => $student) {
                $notidication = new SmNotification();
                $notidication->role_id = $student->role_id;
                $notidication->message = "Exam Schedule updated";
                $notidication->date = date('Y-m-d');
                $notidication->user_id = $student->user_id;
                $notidication->academic_id = getAcademicId();
                $notidication->save();

                $parent = SmParent::find($student->parent_id)->first();

                $notidication = new SmNotification();
                $notidication->role_id = 3;
                $notidication->message = "Your child's exam schedule updated";
                $notidication->date = date('Y-m-d');
                $notidication->user_id = $parent->user_id;
                $notidication->academic_id = getAcademicId();
                $notidication->save();
            }
            Toastr::success('Exam routine has been updated successfully', 'Success');
        }


            $class_id = $request->class_id;
            $section_id = $request->section_id;
            $exam_term_id = $request->exam_term_id;



            if ($result) {
                return redirect('exam-routine-view/' . $class_id . '/' . $section_id . '/' . $exam_term_id);
            }
       }catch (\Exception $e) {
           
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
         }
    }

    public function examRoutineView($class_id, $section_id, $exam_term_id)
    {

        try {
            $assign_subjects = SmAssignSubject::where('class_id', $class_id)->where('section_id', $section_id)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();

            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            $exams = SmExam::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();

            $exam_id = $exam_term_id;

            $exam_types = SmExamType::where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            $exam_periods = SmClassTime::where('type', 'exam')->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            $rooms = SmClassRoom::where('active_status', 1)->where('school_id',Auth::user()->school_id)->get();
            return view('backEnd.examination.exam_schedule_create', compact('classes','rooms','exams', 'assign_subjects', 'class_id', 'section_id', 'exam_id', 'exam_types', 'exam_periods'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function checkExamRoutineDate(Request $request)
    {

        try {
            if ($request->assigned_id == "") {
                $check_date = SmExamSchedule::where('class_id', $request->class_id)->where('section_id', $request->section_id)->where('exam_term_id', $request->exam_term_id)->where('date', date('Y-m-d', strtotime($request->date)))->where('exam_period_id', $request->exam_period_id)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            } else {
                $check_date = SmExamSchedule::where('id', '!=', $request->assigned_id)->where('class_id', $request->class_id)->where('section_id', $request->section_id)->where('exam_term_id', $request->exam_term_id)->where('date', date('Y-m-d', strtotime($request->date)))->where('exam_period_id', $request->exam_period_id)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            }

            $holiday_check = SmHoliday::where('from_date', '<=', date('Y-m-d', strtotime($request->date)))->where('to_date', '>=', date('Y-m-d', strtotime($request->date)))->where('school_id',Auth::user()->school_id)->first();

            if ($holiday_check != "") {
                $from_date = date('jS M, Y', strtotime($holiday_check->from_date));
                $to_date = date('jS M, Y', strtotime($holiday_check->to_date));
            } else {
                $from_date = '';
                $to_date = '';
            }

            return response()->json([$check_date, $holiday_check, $from_date, $to_date]);
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function examScheduleReportSearch(Request $request)
    {
        $request->validate([
            'exam' => 'required',
            'class' => 'required',
            'section' => 'required',
        ]);
        // $InputExamId = $request->exam;
        // $InputClassId = $request->class;
        // $InputSectionId = $request->section;

        try {
            $assign_subjects = SmAssignSubject::where('class_id', $request->class)->where('section_id', $request->section)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();

            if ($assign_subjects->count() == 0) {
                Toastr::error('No Subject Assigned. Please assign subjects in this class.', 'Failed');
                return redirect()->back();
                // return redirect('exam-schedule-create')->with('message-danger', 'No Subject Assigned. Please assign subjects in this class.');
            }

            $assign_subjects = SmAssignSubject::where('class_id', $request->class)->where('section_id', $request->section)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();

            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            $exams = SmExam::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            $class_id = $request->class;
            $section_id = $request->section;
            $exam_id = $request->exam;

            $exam_types = SmExamType::where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            $exam_periods = SmClassTime::where('type', 'exam')->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();

            $exam_schedules = SmExamSchedule::where('exam_term_id', $exam_id)->where('class_id', $request->class)->where('section_id', $request->section)->where('school_id',Auth::user()->school_id)->get();

            $exam_dates = [];
            foreach ($exam_schedules as $exam_schedule) {
                $exam_dates[] = $exam_schedule->date;
            }

            $exam_dates = array_unique($exam_dates);

            return view('backEnd.examination.exam_schedule_new', compact('classes', 'exams', 'assign_subjects', 'class_id', 'section_id', 'exam_id', 'exam_types', 'exam_periods', 'exam_dates'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function compareByTimeStamp($time1, $time2)
    {

        try {
            if (strtotime($time1) < strtotime($time2)) {
                return 1;
            } else if (strtotime($time1) > strtotime($time2)) {
                return -1;
            } else {
                return 0;
            }

        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function examScheduleReportSearchOld(Request $request)
    {
        $request->validate([
            'exam' => 'required',
            'class' => 'required',
            'section' => 'required',
        ]);

        try {
            $assign_subjects = SmAssignSubject::where('class_id', $request->class)->where('section_id', $request->section)->where('school_id',Auth::user()->school_id)->get();

            if ($assign_subjects->count() == 0) {
                Toastr::success('No Subject Assigned. Please assign subjects in this class.', 'Success');
                return redirect('exam-schedule-create');
            }

            $assign_subjects = SmAssignSubject::where('class_id', $request->class)->where('section_id', $request->section)->where('school_id',Auth::user()->school_id)->get();

            $classes = SmClass::where('active_status', 1)->where('school_id',Auth::user()->school_id)->get();
            $exams = SmExam::where('active_status', 1)->where('school_id',Auth::user()->school_id)->get();
            $class_id = $request->class;
            $section_id = $request->section;
            $exam_id = $request->exam;

            $exam_types = SmExamType::all();
            $exam_periods = SmClassTime::where('type', 'exam')->where('school_id',Auth::user()->school_id)->get();

            return view('backEnd.examination.exam_schedule', compact('classes', 'exams', 'assign_subjects', 'class_id', 'section_id', 'exam_id', 'exam_types', 'exam_periods'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function examSchedulePrint(Request $request)
    { 

        try {
            $assign_subjects = SmAssignSubject::where('class_id', $request->class_id)->where('section_id', $request->section_id)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            $exam_periods = SmClassTime::where('type', 'exam')->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
              $academic_year=SmAcademicYear::find(getAcademicId());
            // $customPaper = array(0, 0, 700.00, 1500.80);
            $pdf = PDF::loadView(
                'backEnd.examination.exam_schedult_print',
                [
                    'assign_subjects' => $assign_subjects,
                    'exam_periods' => $exam_periods,
                    'class_id' => $request->class_id,
                    'academic_year' => $academic_year,

                    'section_id' => $request->section_id,
                    'exam_id' => $request->exam_id,

                ]
            )->setPaper('A4', 'landscape');
            // return $section;
            return $pdf->stream('EXAM_SCHEDULE.pdf');
        } catch (\Exception $e) {
            // dd($e);
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function examRoutineReport(Request $request)
    {

        try {
            $exam_types = SmExamType::where('school_id',Auth::user()->school_id)->where('academic_id', getAcademicId())->get();
            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {

                return ApiBaseMethod::sendResponse($exam_types, null);
            }
            return view('backEnd.reports.exam_routine_report', compact('classes', 'exam_types'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function examRoutineReportSearch(Request $request)
    {

        try {
            $exam_types = SmExamType::where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            $exam_periods = SmClassTime::where('type', 'exam')->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            $exam_routines = SmExamSchedule::where('exam_term_id', $request->exam)->orderBy('date', 'ASC')->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            $exam_routines = $exam_routines->groupBy('date');

            $exam_term_id = $request->exam;

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['exam_types'] = $exam_types->toArray();
                $data['exam_routines'] = $exam_routines->toArray();
                $data['exam_periods'] = $exam_periods->toArray();
                $data['exam_term_id'] = $exam_term_id;
                return ApiBaseMethod::sendResponse($data, null);
            }

            return view('backEnd.reports.exam_routine_report', compact('exam_types', 'exam_routines', 'exam_periods', 'exam_term_id'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function examRoutineReportSearchPrint($exam_id)
    {

        try {
            $exam_types = SmExamType::where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            $exam_periods = SmClassTime::where('type', 'exam')->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            $exam_routines = SmExamSchedule::where('exam_term_id', $exam_id)->orderBy('date', 'ASC')->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            $exam_routines = $exam_routines->groupBy('date');
            $academic_year = SmAcademicYear::find(getAcademicId());
           

            $exam_term_id = $exam_id;

            $pdf = PDF::loadView(
                'backEnd.reports.exam_routine_report_print',
                [
                    'exam_types' => $exam_types,
                    'exam_routines' => $exam_routines,
                    'exam_periods' => $exam_periods,
                    'exam_term_id' => $exam_term_id,
                    'academic_year'=>$academic_year
                ]
            )->setPaper('A4', 'landscape');
            return $pdf->stream('exam_routine.pdf');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }

    }
}