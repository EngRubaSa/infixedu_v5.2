<?php

namespace App\Http\Controllers;

use App\SmClass;
use App\SmStaff;
use App\SmSection;
use App\SmStudent;
use App\YearCheck;
use App\ApiBaseMethod;
use App\SmAssignSubject;
use App\SmStudentAttendance;
use Illuminate\Http\Request;
use App\StudentAttendanceBulk;
use App\SmStudentAttendanceImport;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StudentAttendanceImport;
use Illuminate\Support\Facades\Validator;

class SmStudentAttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('PM');
        // User::checkAuth();
    }

    public function index(Request $request)
    {
        try {
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
            
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse($classes, null);
            }
            return view('backEnd.studentInformation.student_attendance', compact('classes'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function studentSearch(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'class' => 'required',
            'section' => 'required',
            'attendance_date' => 'required'
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
            $date = $request->attendance_date;
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
            $students = SmStudent::where('class_id', $request->class)->where('section_id', $request->section)->where('active_status', 1)->where('academic_id', getAcademicId())
                ->where('school_id', Auth::user()->school_id)->get();

            if ($students->isEmpty()) {
                Toastr::error('No Result Found', 'Failed');
                return redirect('student-attendance');
            }

            $already_assigned_students = [];
            $new_students = [];
            $attendance_type = "";
            foreach ($students as $student) {
                $attendance = SmStudentAttendance::where('student_id', $student->id)
                    ->where('attendance_date', date('Y-m-d', strtotime($request->attendance_date)))
                    ->where('academic_id', getAcademicId())
                    ->where('school_id', Auth::user()->school_id)
                    ->first();
                if ($attendance != "") {
                    $already_assigned_students[] = $attendance;
                    $attendance_type = $attendance->attendance_type;
                } else {
                    $new_students[] = $student;
                }
            }
            $class_id = $request->class;
            $section_id = $request->section;
            $class_info = SmClass::find($request->class);
            $section_info = SmSection::find($request->section);

            $search_info['class_name'] = $class_info->class_name;
            $search_info['section_name'] = $section_info->section_name;
            $search_info['date'] = $request->attendance_date;




            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['classes'] = $classes->toArray();
                $data['date'] = $date;
                $data['class_id'] = $class_id;
                $data['already_assigned_students'] = $already_assigned_students;
                $data['new_students'] = $new_students;
                $data['attendance_type'] = $attendance_type;
                return ApiBaseMethod::sendResponse($data, null);
            }
            return view('backEnd.studentInformation.student_attendance', compact('classes', 'date', 'class_id','section_id', 'date', 'already_assigned_students', 'new_students', 'attendance_type', 'search_info'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function studentAttendanceStore(Request $request)
    {
        $attendance = SmStudentAttendance::where('student_id',$request->student_id)->where('attendance_date',date('Y-m-d', strtotime($request->attendance_date)))->first();

        if ($attendance){
            $attendance->attendance_type = $request->attendance_type;
            $attendance->notes = $request->notes;
            $attendance->save();
        }
        else{
            $attendance = new SmStudentAttendance();
            $attendance->student_id = $request->student_id;
            $attendance->attendance_type = $request->attendance_type;
            $attendance->notes = $request->notes;
            $attendance->attendance_date = date('Y-m-d', strtotime($request->attendance_date));
            $attendance->school_id = Auth::user()->school_id;
            $attendance->academic_id = getAcademicId();
            $attendance->save();
        }


        return response()->json('success');

        /*try {
            foreach ($request->id as $student) {
                $attendance = SmStudentAttendance::where('student_id', $student)->where('attendance_date', date('Y-m-d', strtotime($request->date)))
                    ->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->first();

                if ($attendance) {
                    $attendance->delete();
                }


                $attendance = new SmStudentAttendance();
                $attendance->student_id = $student;
                if (isset($request->mark_holiday)) {
                    $attendance->attendance_type = "H";
                } else {
                    $attendance->attendance_type = $request->attendance[$student];
                    $attendance->notes = $request->note[$student];
                }
                $attendance->attendance_date = date('Y-m-d', strtotime($request->date));
                $attendance->school_id = Auth::user()->school_id;
                $attendance->academic_id = getAcademicId();
                $attendance->save();
            }

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse(null, 'Student attendance been submitted successfully');
            }
            Toastr::success('Operation successful', 'Success');
            return redirect('student-attendance');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }*/
    }


    public function studentAttendanceHoliday(Request $request)
    {
        // return $request;

        $students = SmStudent::where('class_id', $request->class_id)->where('section_id', $request->section_id)->where('active_status', 1)->where('academic_id', getAcademicId())
                ->where('school_id', Auth::user()->school_id)->get();
        // dd($students);
            if ($students->isEmpty()) {
                Toastr::error('No Result Found', 'Failed');
                return redirect('student-attendance');
            }

            if ($request->purpose == "mark") {
                
            
            foreach ($students as $student) {

                $attendance = SmStudentAttendance::where('student_id', $student->id)
                    ->where('attendance_date', date('Y-m-d', strtotime($request->attendance_date)))
                    ->where('academic_id', getAcademicId())
                    ->where('school_id', Auth::user()->school_id)
                    ->first();
                if (!empty($attendance)) {
                    $attendance->delete();
                }else{
                    $attendance = new SmStudentAttendance();
                    $attendance->attendance_type= "H";
                    $attendance->notes= "Holiday";
                    $attendance->attendance_date = date('Y-m-d', strtotime($request->attendance_date));
                    $attendance->student_id = $student->id;
                    $attendance->academic_id = getAcademicId();
                    $attendance->school_id = Auth::user()->school_id;
                    $attendance->save();
                }
            }
        }elseif($request->purpose == "unmark"){
            foreach ($students as $student) {

                $attendance = SmStudentAttendance::where('student_id', $student->id)
                    ->where('attendance_date', date('Y-m-d', strtotime($request->attendance_date)))
                    ->where('academic_id', getAcademicId())
                    ->where('school_id', Auth::user()->school_id)
                    ->first();
                if (!empty($attendance)) {
                    $attendance->delete();
                }
            }
        }
            
            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
    }

    public function studentAttendanceImport()
    {

        try {
            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            return view('backEnd.studentInformation.student_attendance_import', compact('classes'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }

    }

    public function downloadStudentAtendanceFile()
    {

        try {
            $studentsArray = ['admission_no', 'class_id', 'section_id', 'attendance_date', 'in_time', 'out_time'];

            return Excel::create('student_attendance_sheet', function ($excel) use ($studentsArray) {
                $excel->sheet('student_attendance_sheet', function ($sheet) use ($studentsArray) {
                    $sheet->fromArray($studentsArray);
                });
            })->download('xlsx');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }

    }

    // public function studentAttendanceBulkStore(Request $request){


    //     $request->validate([
    //         'attendance_date' => 'required',
    //         'file' => 'required'
    //     ]);
    //     $file_type=strtolower($request->file->getClientOriginalExtension());
    //     if ($file_type<>'csv' && $file_type<>'xlsx' && $file_type<>'xls') {
    //         Toastr::warning('The file must be a file of type: xlsx, csv or xls', 'Warning');
    //         return redirect()->back();
    //     }else{
    //     // try{
    //     $max_admission_id = SmStudent::where('school_id',Auth::user()->school_id)->max('admission_no');
    //     // $path = $request->file('file')->getRealPath();
    //     // $data = Excel::load($path)->get();
    //     $path = $request->file('file');

    //     Excel::import(new StudentAttendanceImport, $request->file('file'), 's3', \Maatwebsite\Excel\Excel::XLSX);
    //     $data = StudentAttendanceBulk::get();

    //     // return $request;
    //     // if ($data->count()) {
    //     if (!empty($data)) {
    //         $class_sections = [];
    //         foreach ($data as $key => $value) {
    //             if(date('d/m/Y', strtotime($request->attendance_date)) == date('d/m/Y', strtotime($value->attendance_date))){
    //                 $class_sections[] = $value->class_id.'-'.$value->section_id;
    //             }
    //         }
    //         // return $request;
    //         DB::beginTransaction();


    //         $all_student_ids = [];
    //         $present_students = [];
    //         foreach(array_unique($class_sections) as $value){

    //             $class_section = explode('-', $value);
    //             $students = SmStudent::where('class_id', $class_section[0])->where('section_id', $class_section[1])->where('school_id',Auth::user()->school_id)->get();

    //             foreach($students as $student){
    //                 StudentAttendanceBulk::where('student_id', $student->id)->where('attendance_date', date('Y-m-d', strtotime($request->attendance_date)))->delete();
    //                 $all_student_ids[] = $student->id;
    //             }

    //         }

    //         try {
    //             foreach ($data as $key => $value) {

    //                 if ($value != "") {
    //                     if(date('d/m/Y', strtotime($request->attendance_date)) == date('d/m/Y', strtotime($value->attendance_date))){
    //                         $student = SmStudent::select('id')->where('admission_no', $value->admission_no)->where('school_id',Auth::user()->school_id)->first();
    //                         if($student != ""){
    //                             $present_students[] = $student->id;
    //                             $import = new SmStudentAttendanceImport();
    //                             $import->student_id = $student->id;
    //                             $import->attendance_date = date('Y-m-d', strtotime($request->attendance_date));
    //                             $import->attendance_type = $value->attendance_type;
    //                             $import->in_time = $value->in_time;
    //                             $import->out_time = $value->out_time;
    //                             $import->school_id = Auth::user()->school_id;
    //                             $import->academic_id = getAcademicId();
    //                             $import->save();
    //                         }
    //                     }

    //                 }

    //             }


    //             foreach ($all_student_ids as $all_student_id) {
    //                 if(!in_array($all_student_id, $present_students)){
    //                     $import = new SmStudentAttendanceImport();
    //                     $import->student_id = $all_student_id;
    //                     $import->attendance_type = $value->attendance_type;
    //                     $import->attendance_date = date('Y-m-d', strtotime($request->attendance_date));
    //                     $import->school_id = Auth::user()->school_id;
    //                     $import->academic_id = getAcademicId();
    //                     $import->save();
    //                 }
    //             }


    //         } catch (\Exception $e) {
    //             dd($e->getMessage());
    //             DB::rollback();
    //             Toastr::error('Operation Failed 1', 'Failed');
    //             return redirect()->back();
    //         }


    //         DB::commit();
    //         Toastr::success('Operation successful', 'Success');
    //         return redirect()->back();
    //     }
    //     // }catch (\Exception $e) {
    //     //    Toastr::error('Operation Failed 2', 'Failed');
    //     //    return redirect()->back();
    //     // }
    // }
    // }

    public function studentAttendanceBulkStore(Request $request)
    {


        $request->validate([
            'attendance_date' => 'required',
            'file' => 'required',
            'class' => 'required',
            'section' => 'required',
        ]);
        $file_type = strtolower($request->file->getClientOriginalExtension());
        if ($file_type <> 'csv' && $file_type <> 'xlsx' && $file_type <> 'xls') {
            Toastr::warning('The file must be a file of type: xlsx, csv or xls', 'Warning');
            return redirect()->back();
        } else {
            try {
                $max_admission_id = SmStudent::where('school_id', Auth::user()->school_id)->max('admission_no');
                $path = $request->file('file')->getRealPath();

                Excel::import(new StudentAttendanceImport($request->class,$request->section), $request->file('file'), 's3', \Maatwebsite\Excel\Excel::XLSX);
                $data = StudentAttendanceBulk::get();

                // return $data;

                if (!empty($data)) {
                    $class_sections = [];
                    foreach ($data as $key => $value) {
                        if (date('d/m/Y', strtotime($request->attendance_date)) == date('d/m/Y', strtotime($value->attendance_date))) {
                            $class_sections[] = $value->class_id . '-' . $value->section_id;
                        }
                    }
                    // return $request;
                    DB::beginTransaction();


                    $all_student_ids = [];
                    $present_students = [];
                    foreach (array_unique($class_sections) as $value) {

                        $class_section = explode('-', $value);
                        $students = SmStudent::where('class_id', $class_section[0])->where('section_id', $class_section[1])->where('school_id', Auth::user()->school_id)->get();

                        foreach ($students as $student) {
                            StudentAttendanceBulk::where('student_id', $student->id)->where('attendance_date', date('Y-m-d', strtotime($request->attendance_date)))
                                ->delete();
                            $all_student_ids[] = $student->id;
                        }

                    }


                    try {
                        foreach ($data as $key => $value) {
                            if ($value != "") {

                                if (date('d/m/Y', strtotime($request->attendance_date)) == date('d/m/Y', strtotime($value->attendance_date))) {
                                    $student = SmStudent::select('id')->where('id', $value->student_id)->where('school_id', Auth::user()->school_id)->first();


                                    // return $student;

                                    if ($student != "") {
                                        // SmStudentAttendance
                                        $attendance_check = SmStudentAttendance::where('student_id', $student->id)
                                            ->where('attendance_date', date('Y-m-d', strtotime($value->attendance_date)))->first();
                                        if ($attendance_check) {
                                            $attendance_check->delete();
                                        }
                                        $present_students[] = $student->id;
                                        $import = new SmStudentAttendance();
                                        $import->student_id = $student->id;
                                        $import->attendance_date = date('Y-m-d', strtotime($value->attendance_date));
                                        $import->attendance_type = $value->attendance_type;
                                        $import->notes = $value->note;
                                        $import->school_id = Auth::user()->school_id;
                                        $import->academic_id = getAcademicId();
                                        $import->save();
                                    }
                                } else {
                                    // Toastr::error('Attendance Date not Matched', 'Failed');
                                    $bulk = StudentAttendanceBulk::where('student_id', $value->student_id)->delete();
                                }

                            }

                        }


                        // foreach ($all_student_ids as $all_student_id) {
                        //     if(!in_array($all_student_id, $present_students)){
                        //         $attendance_check=SmStudentAttendance::where('student_id',$all_student_id)->where('attendance_date',date('Y-m-d', strtotime($value->attendance_date)))->first();
                        //         if ($attendance_check) {
                        //            $attendance_check->delete();
                        //         }
                        //         $import = new SmStudentAttendance();
                        //         $import->student_id = $all_student_id;
                        //         $import->attendance_type = 'A';
                        //         $import->in_time = '';
                        //         $import->out_time = '';
                        //         $import->attendance_date = date('Y-m-d', strtotime($request->attendance_date));
                        //         $import->school_id = Auth::user()->school_id;
                        //         $import->academic_id = getAcademicId();
                        //         $import->save();

                        //         $bulk= StudentAttendanceBulk::where('student_id',$all_student_id)->delete();
                        //     }
                        // }


                    } catch (\Exception $e) {
                        // dd($e->getMessage());
                        DB::rollback();
                        Toastr::error('Operation Failed', 'Failed');
                        return redirect()->back();
                    }
                    DB::commit();
                    Toastr::success('Operation successful', 'Success');
                    return redirect()->back();
                }
            } catch (\Exception $e) {
                dd($e);
                Toastr::error('Operation Failed2', 'Failed');
                return redirect()->back();
            }
        }
    }
}