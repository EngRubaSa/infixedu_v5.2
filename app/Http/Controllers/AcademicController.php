<?php

namespace App\Http\Controllers;

use App\SmClass;
use App\SmStaff;
use App\SmSection;
use App\SmStudent;
use App\SmSubject;
use App\YearCheck;
use App\SmFeesAssign;
use App\SmFeesMaster;
use App\ApiBaseMethod;
use App\SmFeesPayment;
use App\SmClassRoutine;
use App\SmAssignSubject;
use Illuminate\Http\Request;
use App\SmAssignClassTeacher;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class AcademicController extends Controller
{
    public function __construct()
    {
        $this->middleware('PM');
        // // User::checkAuth();
    }

    public function classRoutine()
    {

        try {
            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            return view('backEnd.academics.class_routine', compact('classes'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function classRoutineCreate()
    {
        try {
            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            return view('backEnd.academics.class_routine_create', compact('classes'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function assignSubject(Request $request)
    {

        try {
            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse($classes, null);
            }
            return view('backEnd.academics.assign_subject', compact('classes'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function assigSubjectCreate(Request $request)
    {

        try {
            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse($classes, null);
            }
            return view('backEnd.academics.assign_subject_create', compact('classes'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function ajaxSubjectDropdown(Request $request)
    {

        try {
            $staff_info = SmStaff::where('user_id', Auth::user()->id)->first();
            if (Auth::user()->role_id == 1) {
                $class_id = $request->class;
                $allSubjects = SmAssignSubject::where([['section_id', '=', $request->id], ['class_id', $class_id]])->where('school_id', Auth::user()->school_id)->get();

                $subjectsName = [];
                foreach ($allSubjects as $allSubject) {
                    $subjectsName[] = SmSubject::find($allSubject->subject_id);
                }
            } else {
                $class_id = $request->class;
                $allSubjects = SmAssignSubject::where([['section_id', '=', $request->id], ['class_id', $class_id], ['teacher_id', $staff_info->id]])->where('school_id', Auth::user()->school_id)->get();

                $subjectsName = [];
                foreach ($allSubjects as $allSubject) {
                    $subjectsName[] = SmSubject::find($allSubject->subject_id);
                }
            }

            return response()->json([$subjectsName]);
        } catch (\Exception $e) {
            return Response::json(['error' => 'Error msg'], 404);
        }
    }

    public function assignSubjectSearch(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
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
        try {

          
           
         $assign_subjects = SmAssignSubject::where('class_id', $request->class)->where('section_id', $request->section)->where('school_id', Auth::user()->school_id)->get();

            $subjects = SmSubject::where('active_status', 1)->where('school_id', Auth::user()->school_id)->where('academic_id', getAcademicId())->get();
            $teachers = SmStaff::where('active_status', 1)->where('role_id', 4)->where('school_id', Auth::user()->school_id)->get();
            $class_id = $request->class;
            $section_id = $request->section;

            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['classes'] = $classes->toArray();
                $data['assign_subjects'] = $assign_subjects->toArray();
                $data['teachers'] = $teachers->toArray();
                $data['subjects'] = $subjects->toArray();
                $data['class_id'] = $class_id;
                $data['section_id'] = $section_id;
                return ApiBaseMethod::sendResponse($data, null);
            }

            return view('backEnd.academics.assign_subject_create', compact('classes', 'assign_subjects', 'teachers', 'subjects', 'class_id', 'section_id'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function assignSubjectAjax(Request $request)
    {

        try {
            $subjects = SmSubject::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $teachers = SmStaff::where('active_status', 1)->where('role_id', 4)->where('school_id', Auth::user()->school_id)->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['subjects'] = $subjects->toArray();
                $data['teachers'] = $teachers->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }
            return response()->json([$subjects, $teachers]);
        } catch (\Exception $e) {
            return Response::json(['error' => 'Error msg'], 404);
        }
    }

    public function assignSubjectStore(Request $request)
    {
         
        try {
            if ($request->update == 0) {
                $i = 0;
                if (isset($request->subjects)) {
                    foreach ($request->subjects as $subject) {
                        if ($subject != "") {
                            $assign_subject = new SmAssignSubject();
                            $assign_subject->class_id = $request->class_id;
                            $assign_subject->school_id = Auth::user()->school_id;
                            $assign_subject->section_id = $request->section_id;
                            $assign_subject->subject_id = $subject;
                            $assign_subject->teacher_id = $request->teachers[$i];
                            $assign_subject->created_at = YearCheck::getYear() . '-' . date('m-d h:i:s');
                            $assign_subject->academic_id = getAcademicId();
                            $assign_subject->save();
                            $i++;
                        }
                    }
                }
            } elseif ($request->update == 1) {

                
                $assign_subjects = SmAssignSubject::where('class_id', $request->class_id)->where('section_id', $request->section_id)->delete();
                $i = 0;
                if (! empty($request->subjects)) {
                    
                    foreach ($request->subjects as $subject) {
                            
                        if (!empty($subject)) {
                            $assign_subject = new SmAssignSubject();
                            $assign_subject->class_id = $request->class_id;
                            $assign_subject->section_id = $request->section_id;
                            $assign_subject->subject_id = $subject;
                            $assign_subject->teacher_id = $request->teachers[$i];
                            $assign_subject->created_at = YearCheck::getYear() . '-' . date('m-d h:i:s');
                            $assign_subject->academic_id = getAcademicId();
                            $assign_subject->school_id = Auth::user()->school_id;
                            $result =  $assign_subject->save();
                             
                            
                            $i++;
                        }
                    }
                }
            }


            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse(null, 'Record Updated Successfully');
            }
            Toastr::success('Operation successful', 'Success');
            return redirect()->back();

            // return redirect()->back()->with('message-success', 'Record Updated Successfully');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function assignSubjectFind(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
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


        try {
            $assign_subjects = SmAssignSubject::where('class_id', $request->class)->where('section_id', $request->section)->where('school_id', Auth::user()->school_id)->get();
            $subjects = SmSubject::where('active_status', 1)->where('school_id', Auth::user()->school_id)->get();
            $teachers = SmStaff::where('active_status', 1)->where('role_id', 4)->where('school_id', Auth::user()->school_id)->get();
            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();

            if ($assign_subjects->count() == 0) {
                if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                    return ApiBaseMethod::sendError('No Result Found');
                }
                Toastr::error('No Result Found', 'Failed');
                return redirect()->back();
                // return redirect()->back()->with('message-danger', 'No Result Found');
            } else {
                $class_id = $request->class;

                if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                    $data = [];
                    $data['classes'] = $classes->toArray();
                    $data['assign_subjects'] = $assign_subjects->toArray();
                    $data['teachers'] = $teachers->toArray();
                    $data['subjects'] = $subjects->toArray();
                    $data['class_id'] = $class_id;
                    return ApiBaseMethod::sendResponse($data, null);
                }
                return view('backEnd.academics.assign_subject', compact('classes', 'assign_subjects', 'teachers', 'subjects', 'class_id'));
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function ajaxSelectSubject(Request $request)
    {


        try {
            $subject_all = SmAssignSubject::where('class_id', '=', $request->class)->where('section_id', $request->section)->distinct('subject_id')->where('school_id', Auth::user()->school_id)->get();
            $students = [];
            foreach ($subject_all as $allSubject) {
                $students[] = SmSubject::find($allSubject->subject_id);
            }

            return response()->json([$students]);
        } catch (\Exception $e) {
            return Response::json(['error' => 'Error msg'], 404);
        }
    }

    public function assignRoutineSearch(Request $request)
    {
        $request->validate([
            'class' => 'required',
            'section' => 'required',
            'subject' => 'required'
        ]);


        try {
            $class_id = $request->class;
            $section_id = $request->section;
            $subject_id = $request->subject;
            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $class_routine = SmClassRoutine::where('class_id', $request->class)->where('section_id', $request->section)->where('subject_id', $request->subject)->first();
            if ($class_routine == "") {
                $class_routine = "hello";
            }

            return view('backEnd.academics.class_routine_create', compact('class_routine', 'class_id', 'section_id', 'subject_id', 'classes'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function assignRoutineStore(Request $request)
    {


        try {
            $check_assigned = $class_routine = SmClassRoutine::where('class_id', $request->class_id)->where('section_id', $request->section_id)->where('subject_id', $request->subject_id)->delete();
            // if($check_assigned != ""){
            $class_routine = new SmClassRoutine();
            $class_routine->class_id = $request->class_id;
            $class_routine->section_id = $request->section_id;
            $class_routine->subject_id = $request->subject_id;

            $class_routine->monday_start_from = $request->monday_start_from;
            $class_routine->monday_end_to = $request->monday_end_to;
            $class_routine->monday_room_id = $request->monday_room;

            $class_routine->tuesday_start_from = $request->tuesday_start_from;
            $class_routine->tuesday_end_to = $request->tuesday_end_to;
            $class_routine->tuesday_room_id = $request->tuesday_room;

            $class_routine->wednesday_start_from = $request->wednesday_start_from;
            $class_routine->wednesday_end_to = $request->wednesday_end_to;
            $class_routine->wednesday_room_id = $request->wednesday_room;

            $class_routine->thursday_start_from = $request->thursday_start_from;
            $class_routine->thursday_end_to = $request->thursday_end_to;
            $class_routine->thursday_room_id = $request->thursday_room;

            $class_routine->friday_start_from = $request->friday_start_from;
            $class_routine->friday_end_to = $request->friday_end_to;
            $class_routine->friday_room_id = $request->friday_room;

            $class_routine->saturday_start_from = $request->saturday_start_from;
            $class_routine->saturday_end_to = $request->saturday_end_to;
            $class_routine->saturday_room_id = $request->saturday_room;

            $class_routine->sunday_start_from = $request->sunday_start_from;
            $class_routine->sunday_end_to = $request->sunday_end_to;
            $class_routine->sunday_room_id = $request->sunday_room;

            $class_routine->academic_id = getAcademicId();
            $class_routine->save();
            // }else{

            // }
            Toastr::success('Operation successful', 'Success');
            return redirect('class-routine');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function classRoutineReportSearch(Request $request)
    {
        $request->validate([
            'class' => 'required',
            'section' => 'required'
        ]);

        try {

            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();

            $class_routines = SmClassRoutine::where('class_id', $request->class)->where('section_id', $request->section)->where('school_id', Auth::user()->school_id)->get();
            $class_id = $request->class;
            return view('backEnd.academics.class_routine', compact('class_routines', 'classes', 'class_id'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function classReport(Request $request)
    {

        try {

            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse($classes, null);
            }
            return view('backEnd.reports.class_report', compact('classes'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function classReportSearch(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'class' => 'required'
        ]);

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $class = SmClass::where('id', $request->class)->first();

            if ($request->section != "") {
                $section = SmSection::where('id', $request->section)->first();
            } else {
                $section = '';
            }

            $students = SmStudent::query();
            $students->where('active_status', 1);
            if ($request->section != "") {
                $students->where('section_id', $request->section);
            }
            $students->where('class_id', $request->class);
            $students = $students->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();


            $assign_subjects = SmAssignSubject::query();
            $assign_subjects->where('active_status', 1);
            if ($request->section != "") {
                $assign_subjects->where('section_id', $request->section);
            }
            $assign_subjects->where('class_id', $request->class);
            $assign_subjects = $assign_subjects->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();


            $assign_class_teacher = SmAssignClassTeacher::query();
            $assign_class_teacher->where('active_status', 1);
            if ($request->section != "") {
                $assign_class_teacher->where('section_id', $request->section);
            }
            $assign_class_teacher->where('class_id', $request->class);
            $assign_class_teacher = $assign_class_teacher->first();
            if ($assign_class_teacher != "") {
                $assign_class_teachers = $assign_class_teacher->classTeachers->first();
            } else {
                $assign_class_teachers = '';
            }


            $total_collection = 0;
            $total_assign = 0;
            foreach ($students as $student) {
                $fees_assigns = SmFeesAssign::where("student_id", $student->id)->where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
                foreach ($fees_assigns as $fees_assign) {
                    $fees_masters = SmFeesMaster::where('id', $fees_assign->fees_master_id)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
                    foreach ($fees_masters as $fees_master) {
                        $total_collection = $total_collection + SmFeesPayment::where('student_id', $student->id)->where('fees_type_id', $fees_master->fees_type_id)->sum('amount');
                    }
                }

                foreach ($fees_assigns as $fees_assign) {
                    $fees_master = SmFeesMaster::where('id', $fees_assign->fees_master_id)->first();
                    $total_assign = $total_assign + $fees_master->amount;
                }
            }


            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['classes'] = $classes->toArray();
                $data['students'] = $students->toArray();
                $data['assign_subjects'] = $assign_subjects;
                $data['assign_class_teachers'] = $assign_class_teachers;
                $data['total_collection'] = $total_collection;
                $data['total_assign'] = $total_assign;
                $data['class'] = $class;
                $data['section'] = $section;
                return ApiBaseMethod::sendResponse($data, null);
            }

            return view('backEnd.reports.class_report', compact('classes', 'students', 'assign_subjects', 'assign_class_teachers', 'total_collection', 'total_assign', 'class', 'section'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}