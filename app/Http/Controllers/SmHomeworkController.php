<?php

namespace App\Http\Controllers;
use App\SmClass;
use App\SmStaff;
use App\SmParent;
use App\SmStudent;
use App\YearCheck;
use App\SmHomework;
use App\ApiBaseMethod;
use App\SmClassSection;
use App\SmNotification;
use App\SmAssignSubject;
use App\SmGeneralSettings;
use App\SmHomeworkStudent;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class SmHomeworkController extends Controller
{
    public function __construct()
	{
        $this->middleware('PM');
        // User::checkAuth();
	}

    public function homeworkList(Request $request)
    {
        try {
            set_time_limit(900);
            $homeworkLists = SmHomework::where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
        if (Auth::user()->role_id==1) {
            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
        } else {
                $teacher_info=SmStaff::where('user_id',Auth::user()->id)->first();
               $classes= SmAssignSubject::where('teacher_id',$teacher_info->id)->join('sm_classes','sm_classes.id','sm_assign_subjects.class_id')
               ->where('sm_assign_subjects.academic_id', getAcademicId())
               ->where('sm_assign_subjects.active_status', 1)
               ->where('sm_assign_subjects.school_id',Auth::user()->school_id)
               ->distinct ()
               ->select('sm_classes.id','class_name')
               ->get();
        }
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['homeworkLists'] = $homeworkLists->toArray();
                $data['classes'] = $classes->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }
            return view('backEnd.homework.homeworkList', compact('classes'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function searchHomework(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'class_id' => "required",
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

            $homeworkLists = SmHomework::query();
            $homeworkLists->where('active_status', 1);
            $homeworkLists->where('class_id', $request->class_id);

            if ($request->section_id != "") {
                $homeworkLists->where('section_id', $request->section_id);
            }
            if ($request->subject_id != "") {
                $homeworkLists->where('subject_id', $request->subject_id);
            }

            $homeworkLists = $homeworkLists->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();

            $classes = SmClass::where('active_status', '=', '1')->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['homeworkLists'] = $homeworkLists->toArray();
                $data['classes'] = $classes->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }
            return view('backEnd.homework.homeworkList', compact('homeworkLists', 'classes'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function addHomework()
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
               ->distinct ()
               ->select('sm_classes.id','class_name')
               ->get();
        }
            return view('backEnd.homework.addHomework', compact('classes'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function saveHomeworkData(Request $request)
    {
        $request->validate([
            'class_id' => "required",
            'section_id' => "required",
            'subject_id' => "required",
            'homework_date' => "required",
            'submission_date' => "required",
            'marks' => "required|integer|min:0",
            'description' => "required",
            'homework_file' => "sometimes|nullable|mimes:pdf,doc,docx,txt,jpg,jpeg,png,mp4,ogx,oga,ogv,ogg,webm,mp3",
        ]);

        try {
            $fileName = "";
            if ($request->file('homework_file') != "") {
                $file = $request->file('homework_file');
                $fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                $file->move('public/uploads/homework/', $fileName);
                $fileName = 'public/uploads/homework/' . $fileName;
            }

            $homeworks = new SmHomework();
            $homeworks->class_id = $request->class_id;
            $homeworks->section_id = $request->section_id;
            $homeworks->subject_id = $request->subject_id;
            $homeworks->homework_date = date('Y-m-d', strtotime($request->homework_date));
            $homeworks->submission_date = date('Y-m-d', strtotime($request->submission_date));
            $homeworks->marks = $request->marks;
            $homeworks->description = $request->description;
            $homeworks->file = $fileName;
            $homeworks->created_by = Auth()->user()->id;
            $homeworks->school_id = Auth::user()->school_id;
            $homeworks->academic_id = getAcademicId();
            $results = $homeworks->save();

            $students = SmStudent::where('class_id', $request->class_id)->where('section_id', $request->section_id)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            foreach ($students as $student) {
                $notification = new SmNotification;
                $notification->user_id = $student->user_id;
                $notification->role_id = 2;
                $notification->date = date('Y-m-d');
                $notification->message = 'New Homework assigned';
                $notification->school_id = Auth::user()->school_id;
                $notification->academic_id = getAcademicId();
                $notification->save();

                $parent = SmParent::find($student->parent_id);
                        $notidication = new SmNotification();
                        $notidication->role_id = 3;
                        $notidication->message = "New homework assigned for your child";
                        $notidication->date = date('Y-m-d');
                        $notidication->user_id = $parent->user_id;
                        $notidication->academic_id = getAcademicId();
                        $notidication->save();
            }

            if ($results) {
                Toastr::success('Operation successful', 'Success');
                return redirect('homework-list');
            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function evaluationHomework(Request $request, $class_id, $section_id, $homework_id)
    {

        try {
           
            $homeworkDetails = SmHomework::where('class_id', $class_id)->where('section_id', $section_id)->where('id', $homework_id)
                ->where('academic_id', getAcademicId())->first();
            $students = SmStudent::where('class_id', $class_id)->where('section_id', $section_id)->where('active_status', 1)->where('academic_id', getAcademicId())
                ->where('school_id',Auth::user()->school_id)->get();
            return view('backEnd.homework.evaluationHomework', compact('homeworkDetails', 'students', 'homework_id'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function saveHomeworkEvaluationData(Request $request)
    {

        try {
            if (!$request->student_id) {
                Toastr::error('Their are no students selected', 'Failed');
                return redirect()->back();
                // return redirect()->back()->with('message-success', 'Their are no students selected');
            } else {
                $student_idd = count($request->student_id);

                if ($student_idd > 0) {
                    for ($i = 0; $i < $student_idd; $i++) {

                         if (checkAdmin()) {
                            SmHomeworkStudent::where('student_id', $request->student_id[$i])->where('homework_id', $request->homework_id)->delete();
                        }else{
                             SmHomeworkStudent::where('student_id', $request->student_id[$i])->where('homework_id', $request->homework_id)->where('school_id',Auth::user()->school_id)->delete();
                        }

                        $homeworkstudent = new SmHomeworkStudent();
                        $homeworkstudent->homework_id = $request->homework_id;
                        $homeworkstudent->student_id = $request->student_id[$i];
                        $homeworkstudent->marks = $request->marks[$i];
                        $homeworkstudent->teacher_comments = $request->teacher_comments[$request->student_id[$i]];
                        $homeworkstudent->complete_status = $request->homework_status[$request->student_id[$i]];
                        $homeworkstudent->created_by = Auth()->user()->id;
                        $homeworkstudent->school_id = Auth::user()->school_id;
                        $results = $homeworkstudent->save();
                    }

                    $homeworks = SmHomework::find($request->homework_id);
                    $homeworks->evaluation_date = date('Y-m-d', strtotime($request->evaluation_date));
                    $homeworks->updated_by = Auth()->user()->id;
                    $resultss = $homeworks->update();
                }

                if ($results) {
                    Toastr::success('Operation successful', 'Success');
                    return redirect()->back();
                }

            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function evaluationReport(Request $request)
    {

        try {
            // $homeworkLists = SmHomework::where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
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
            return view('backEnd.reports.evaluation', compact('classes'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function searchEvaluation(Request $request)
    {
        $request->validate([
            'subject_id' => "required",
        ]);

        try {
            $homeworkLists = SmHomework::where('active_status', '=', '1')->where('subject_id', '=', $request->subject_id)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
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
            return view('backEnd.reports.evaluation', compact('homeworkLists', 'classes'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function viewEvaluationReport($homework_id)
    {

        try {
            $homeworkDetails = SmHomework::where('id', $homework_id)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->first();
            $homework_students = SmHomeworkStudent::where('homework_id', $homework_id)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();

            // foreach($students as $student){
            //   $allStudents[] = SmStudent::select('full_name', 'admission_no')->where('id', $student->student_id)->where('academic_id', getAcademicId())->first();
            // }

            return view('backEnd.reports.viewEvaluationReport', compact('homeworkDetails', 'homework_students'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function homeworkEdit($id)
    {

        try {
            // $homeworkList = SmHomework::find($id);
             if (checkAdmin()) {
                $homeworkList = SmHomework::find($id);
            }else{
                $homeworkList = SmHomework::where('id',$id)->where('school_id',Auth::user()->school_id)->first();
            }
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
            $sections = SmClassSection::where('class_id', '=', $homeworkList->class_id)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();

            $subjects = SmAssignSubject::where('class_id', $homeworkList->class_id)->where('section_id', $homeworkList->section_id)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();

            return view('backEnd.homework.homeworkEdit', compact('homeworkList', 'classes', 'sections', 'subjects'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function homeworkUpdate(Request $request)
    {
        $request->validate([
            'class_id' => "required",
            'section_id' => "required",
            'subject_id' => "required",
            'homework_date' => "required",
            'submission_date' => "required",
            'marks' => "required|integer|min:0",
            'description' => "required",
            'homework_file' => "sometimes|nullable|mimes:pdf,doc,docx,txt,jpg,jpeg,png,mp4,ogx,oga,ogv,ogg,webm,mp3",
        ]);

        try {

            $fileName = "";
            if ($request->file('homework_file') != "") {
                $file = $request->file('homework_file');
                $fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                $file->move('public/uploads/homework/', $fileName);
                $fileName = 'public/uploads/homework/' . $fileName;
            }

             if (checkAdmin()) {
                $homeworks = SmHomework::find($request->id);
            }else{
                $homeworks = SmHomework::where('id',$request->id)->where('school_id',Auth::user()->school_id)->first();
            }
            $homeworks->class_id = $request->class_id;
            $homeworks->section_id = $request->section_id;
            $homeworks->subject_id = $request->subject_id;
            $homeworks->homework_date = date('Y-m-d', strtotime($request->homework_date));
            $homeworks->submission_date = date('Y-m-d', strtotime($request->submission_date));
            $homeworks->marks = $request->marks;
            $homeworks->description = $request->description;
            if ($fileName != "") {
                $homeworks->file = $fileName;
            }
            $results = $homeworks->save();

            if ($results) {
                Toastr::success('Operation successful', 'Success');
                return redirect('homework-list');
            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function homeworkDelete($id)
    {
        try{
        $tables = \App\tableList::getTableList('homework_id', $id);

        try {
            if ($tables==null) {
                // $homework = SmHomework::find($id);
                if (checkAdmin()) {
                $homework = SmHomework::find($id);
            }else{
                $homework = SmHomework::where('id',$id)->where('school_id',Auth::user()->school_id)->first();
            }
                Session::put('path', $homework);
                $result = SmHomework::destroy($id);
                if ($result) {
                    $data = Session::get('path');
                    if ($data->file != "") {
                        $path = url('/') . '/public/uploads/homework/' . $homework->file;
                        if (file_exists($path)) {}
                    }
                    Toastr::success('Operation successful', 'Success');
                    return redirect('homework-list');
                } else {
                    Toastr::error('Operation Failed', 'Failed');
                    return redirect()->back();
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
        } catch (\Exception $e) {
            //dd($e->getMessage(), $e->errorInfo);
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}