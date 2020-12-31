<?php

namespace App\Http\Controllers;
use App\SmClass;
use App\SmStaff;
use App\SmStudent;
use App\SmSubject;
use App\YearCheck;
use App\SmClassSection;
use App\SmAssignSubject;
use Illuminate\Http\Request;
use App\SmClassOptionalSubject;
use App\SmOptionalSubjectAssign;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SmOptionalSubjectAssignController extends Controller
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
    public function assignOptionalSubject(Request $request)
    {
        Toastr::error('Operation Failed', 'Failed');
        return redirect()->back();
    }
    public function index(Request $request)
    {
        // return $request;
        // try {
            $classes = SmClass::join('sm_class_optional_subject','sm_class_optional_subject.class_id','=','sm_classes.id')
            ->where('sm_classes.academic_id', getAcademicId())
            ->where('sm_classes.school_id',Auth::user()->school_id)
            ->select('sm_classes.*')
            ->get();
            $classes = SmClass::where('academic_id', getAcademicId())->get();
            $sections = SmClassSection::where('school_id',Auth::user()->school_id)->get();
            $assign_subjects = SmAssignSubject::where('school_id',Auth::user()->school_id)->get();
            $subjects = SmSubject::where('active_status', 1)->where('school_id',Auth::user()->school_id)->get();
            $teachers = SmStaff::where('active_status', 1)->where('role_id', 4)->where('school_id',Auth::user()->school_id)->get();
            // return $subjects;
            return view('backEnd.academics.assign_optional_subject', compact('classes','sections','assign_subjects','subjects','teachers'));
        // } catch (\Illuminate\Database\QueryException $e) {
        //     Toastr::error('Ops! Something went wrong !', 'Failed');
        //     //dd($e->getMessage());
        //     return redirect()->back();
        // }
    }

    public function assignOptionalSubjectSearch(Request $request)
    {
        // return $request;
        $input = $request->all();
        $validator = Validator::make($input, [
            'class' => 'required',
            'section' => 'required'
        ]);

        // try{
            $students = SmStudent::where('class_id', $request->class)->where('section_id', $request->section)->where('school_id',Auth::user()->school_id)->get();

            $assign_subjects = DB::table('sm_assign_subjects')
            ->select( 'sm_assign_subjects.*',  'sm_subjects.subject_name')
            ->leftjoin('sm_subjects', 'sm_subjects.id', '=', 'sm_assign_subjects.subject_id')
            ->where('sm_assign_subjects.class_id','=', $request->class)
            ->where('sm_assign_subjects.school_id',Auth::user()->school_id)->get();


            $subject_id=$request->subject;
            $subject_info=SmSubject::where('id','=',$request->subject)->first();
            $subjects = SmSubject::where('active_status', 1)->where('school_id',Auth::user()->school_id)->get();
            $teachers = SmStaff::where('active_status', 1)->where('role_id', 4)->where('school_id',Auth::user()->school_id)->get();
            $class_id = $request->class;
            $section_id = $request->section;

            $classes = SmClass::join('sm_class_optional_subject','sm_class_optional_subject.class_id','=','sm_classes.id')
            ->where('sm_classes.academic_id', getAcademicId())->select('sm_classes.*')->where('sm_classes.school_id',Auth::user()->school_id)->get();
            //return $classes;
            return view('backEnd.academics.assign_optional_subject', compact('classes', 'assign_subjects', 'teachers', 'subjects', 'class_id', 'section_id', 'students','subject_id','subject_info'));
        // }catch (\Exception $e) {
        //    Toastr::error('Operation Failed', 'Failed');
        //    return redirect()->back();
        // }
    }
        public function assignOptionalSubjectStore(Request $request){
        
            if ($request->student_id) {
            try {
                if ($request->student_id != '') {
                foreach ($request->student_id as  $student) {
                    $student_info=SmStudent::where('id','=',$student)->first();
                    $optional_subject=SmOptionalSubjectAssign::where('student_id','=',$student)->where('session_id','=',$student_info->session_id)->first();

                    if ($optional_subject!='') {
                    $optional_subject=SmOptionalSubjectAssign::find($optional_subject->id);
                    $optional_subject->subject_id=$request->subject_id;
                    $optional_subject->updated_by=Auth::user()->id;
                    $optional_subject->academic_id = getAcademicId();
                    $optional_subject->save();
                    } else {
                        $optional_subject=new SmOptionalSubjectAssign();
                        $optional_subject->student_id=$student;
                        $optional_subject->subject_id=$request->subject_id;
                        $optional_subject->session_id=$student_info->session_id;
                        $optional_subject->created_by=Auth::user()->id;
                        $optional_subject->school_id = Auth::user()->school_id;
                        $optional_subject->academic_id = getAcademicId();

                        $optional_subject->save();
                    }

                }
                
                Toastr::success('Operation successful', 'Success');
                return redirect('optional-subject');
                } 
            
            } catch (\Throwable $th) {
                // dd($th);
                Toastr::error('Operation Failed', 'Failed');
                return redirect('optional-subject');
                }
            }else{
                Toastr::error('Pease select Student', 'Failed');
                return redirect('optional-subject');
            }

        }
    public function optionalSetup(Request $request){

        try{
            $classes=SmClass::where('active_status',1)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            $class_optionals=SmClassOptionalSubject::join('sm_classes','sm_classes.id','=','sm_class_optional_subject.class_id')
            ->select('sm_class_optional_subject.*','class_name')
            ->where('sm_class_optional_subject.school_id',Auth::user()->school_id)->get();
            return view('backEnd.systemSettings.optional_subject_setup',compact('classes','class_optionals'));
        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function optionalSetupStore(Request $request){
        $request->validate([
            'class' => "required|array",
            'gpa_above' => "required",
        ]);
        try {
              foreach ($request->class as  $value) {
                    $optional_check=SmClassOptionalSubject::where('class_id','=',$value)->first();
                    if ($optional_check=='') {
                        $class_optional=new SmClassOptionalSubject();
                        $class_optional->class_id=$value;
                        $class_optional->gpa_above=$request->gpa_above;
                        $class_optional->school_id=Auth::user()->school_id;
                        $class_optional->created_by=Auth::user()->id;
                        $class_optional->updated_by=Auth::user()->id;
                        $class_optional->academic_id = getAcademicId();
                        $class_optional->save();
                    } else {
                        $class_optional=SmClassOptionalSubject::where('class_id','=',$value)->first();
                        $class_optional->gpa_above=$request->gpa_above;
                        $class_optional->school_id=Auth::user()->school_id;
                        $class_optional->created_by=Auth::user()->id;
                        $class_optional->updated_by=Auth::user()->id;
                        $class_optional->academic_id = getAcademicId();
                        $class_optional->update();
                    }
                }
            Toastr::success('Operation successful', 'Success');
            return redirect('optional-subject-setup');
        } catch (\Throwable $th) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function optionalSetupDelete($id){

        try {
           $class_optional=SmClassOptionalSubject::findOrfail($id);
           $class_optional->delete();
           Toastr::success('Operation successful', 'Success');
           return redirect()->back();
        } catch (\Throwable $th) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function optionalSetupEdit($id){

        try {
           $editData=SmClassOptionalSubject::findOrfail($id);
           $classes=SmClass::where('active_status',1)->where('school_id',Auth::user()->school_id)->where('sm_classes.academic_id', getAcademicId())->get();
        //    return $classes;
           $class_optionals=SmClassOptionalSubject::join('sm_classes','sm_classes.id','=','sm_class_optional_subject.class_id')
           ->select('sm_class_optional_subject.*','class_name')
           ->where('sm_class_optional_subject.school_id',Auth::user()->school_id)->get();
           return view('backEnd.systemSettings.optional_subject_setup',compact('classes','class_optionals','editData'));
        } catch (\Throwable $th) {
            // dd($th);
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}