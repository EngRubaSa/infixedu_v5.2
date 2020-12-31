<?php

namespace App\Http\Controllers;

use App\SmExam;
use App\SmClass;
use App\SmStaff;
use App\SmSection;
use App\SmSubject;
use App\YearCheck;
use App\SmExamType;
use App\SmExamSetup;
use App\SmMarkStore;
use App\ApiBaseMethod;
use App\SmResultStore;
use App\SmClassSection;
use App\SmAssignSubject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;


class SmExamController extends Controller
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
        try {
            $exams = SmExam::where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $exams_types = SmExamType::where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->where('active_status',1)->get();
            if (Auth::user()->role_id==1) {
                $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            } else {
                $teacher_info=SmStaff::where('user_id',Auth::user()->id)->first();
                $classes= SmAssignSubject::where('teacher_id',$teacher_info->id)
                ->join('sm_classes','sm_classes.id','sm_assign_subjects.class_id')
                ->where('sm_assign_subjects.academic_id', getAcademicId())
                ->where('sm_assign_subjects.active_status', 1)
                ->where('sm_assign_subjects.school_id',Auth::user()->school_id)
                ->select('sm_classes.id','class_name')
                ->get();
            }
            $subjects = SmSubject::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $sections = SmSection::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            return view('backEnd.examination.exam', compact('exams', 'classes', 'subjects', 'exams_types', 'sections'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function exam_setup($id)
    {
        try {
            $exams = SmExam::where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $exams_types = SmExamType::where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->where('active_status',1)->get();
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
            $subjects = SmSubject::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $sections = SmSection::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $selected_exam_type_id = $id;

            return view('backEnd.examination.exam', compact('exams', 'classes', 'subjects', 'exams_types', 'sections', 'selected_exam_type_id'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function exam_reset()
    {
        try {
            $exams = SmExam::where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            SmExam::query()->truncate();
            $exams_types = SmExamType::where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            SmExamType::query()->truncate();
            $exam_mark_stores = SmMarkStore::where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            SmMarkStore::query()->truncate();
            $exam_results_stores = SmResultStore::where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();

            SmResultStore::query()->truncate();
            SmExamSetup::query()->truncate();
            if (Auth::user()->role_id==1) {
                $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            } else {
                $teacher_info=SmStaff::where('user_id',Auth::user()->id)->first();
                $classes= SmAssignSubject::where('teacher_id',$teacher_info->id)
                ->join('sm_classes','sm_classes.id','sm_assign_subjects.class_id')
                ->where('sm_assign_subjects.academic_id', getAcademicId())
                ->where('sm_assign_subjects.active_status', 1)
                ->where('sm_assign_subjects.school_id',Auth::user()->school_id)
                ->select('sm_classes.id','class_name')
                ->get();
            }
            $subjects = SmSubject::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $sections = SmSection::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            return view('backEnd.examination.exam', compact('exams', 'classes', 'subjects', 'exams_types', 'sections'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate(
            [
                'class_ids' => 'required',
                'subjects_ids' => 'required|array',
                'exams_types' => 'required|array',
                'exam_marks' => "required|numeric|min:0"
            ],
            [
                'class_ids.required' => 'The Class Field is required!',
                'subjects_ids.required' => 'At least one checkbox required!',
                'exams_types.required' => 'At least one checkbox required!',
            ]
        );


        DB::beginTransaction();

        try {
            $sections = SmClassSection::where('class_id', $request->class_ids)->get();
            foreach ($request->exams_types as $exam_type_id) {
                foreach ($sections as $section) {
                    $subject_for_sections = SmAssignSubject::where('class_id', $request->class_ids)->where('section_id', $section->section_id)->get();
                    $eligible_subjects = [];
                    foreach ($subject_for_sections as $subject_for_section) {
                        $eligible_subjects[] = $subject_for_section->subject_id;
                    }
                    foreach ($request->subjects_ids as $subject_id) {
                        if (in_array($subject_id, $eligible_subjects)) {
                            $exam = new SmExam();
                            $exam->exam_type_id = $exam_type_id;
                            $exam->class_id = $request->class_ids;
                            $exam->section_id = $section->section_id;
                            $exam->subject_id = $subject_id;
                            $exam->exam_mark = $request->exam_marks;
                            $exam->created_at = YearCheck::getYear() . '-' . date('m-d h:i:s');
                            $exam->school_id = Auth::user()->school_id;
                            $exam->academic_id = getAcademicId();
                            // return $exam;
                            $exam->save();
                            $exam->toArray();
                            $exam_term_id = $exam->id;
                            $length = count($request->exam_title);
                            for ($i = 0; $i < $length; $i++) {
                                $ex_title = $request->exam_title[$i];
                                $ex_mark = $request->exam_mark[$i];
                                $newSetupExam = new SmExamSetup();
                                $newSetupExam->exam_id = $exam->id;
                                $newSetupExam->class_id = $request->class_ids;
                                $newSetupExam->section_id = $section->section_id;
                                $newSetupExam->subject_id = $subject_id;
                                $newSetupExam->exam_term_id = $exam_type_id;
                                $newSetupExam->exam_title = $ex_title;
                                $newSetupExam->exam_mark = $ex_mark;
                                $newSetupExam->created_at = YearCheck::getYear() . '-' . date('m-d h:i:s');
                                $newSetupExam->school_id = Auth::user()->school_id;
                                $newSetupExam->academic_id = getAcademicId();
                                $result = $newSetupExam->save();
                            } //end loop exam setup loop
                        }
                    }
                }
            }

            DB::commit();
            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            //dd($e->where('academic_id', getAcademicId())->getMessage(), $e->errorInfo);
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $exams_types = SmExamType::get();
            $exam = SmExam::find($id);
            if (Auth::user()->role_id==1) {
                $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            } else {
                $teacher_info=SmStaff::where('user_id',Auth::user()->id)->first();
                $classes= SmAssignSubject::where('teacher_id',$teacher_info->id)
                ->join('sm_classes','sm_classes.id','sm_assign_subjects.class_id')
                ->where('sm_assign_subjects.academic_id', getAcademicId())
                ->where('sm_assign_subjects.active_status', 1)
                ->where('sm_assign_subjects.school_id',Auth::user()->school_id)
                ->select('sm_classes.id','class_name')
                ->get();
            }
  $subjects = SmAssignSubject::where('active_status', 1)->where('class_id', $exam->class_id)->where('section_id', $exam->section_id)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $sections = SmClassSection::where('class_id', $exam->class_id)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $exams = SmExam::where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            return view('backEnd.examination.examEdit', compact('exam', 'exams', 'classes', 'subjects', 'sections', 'exams_types'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function update(Request $request, $id)
    {

        $request->validate([
            'exam_marks' => "required",
        ]);


        DB::beginTransaction();

        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            $exam = SmExam::find($id);
            $exam->exam_mark = $request->exam_marks;
            $exam->created_at = YearCheck::getYear() . '-' . date('m-d h:i:s');
            $exam->save();
            SmExamSetup::where('exam_id', $id)->delete();
            $length = count($request->exam_title);
            for ($i = 0; $i < $length; $i++) {
                $ex_title = $request->exam_title[$i];
                $ex_mark = $request->exam_mark[$i];
                $newSetupExam = new SmExamSetup();
                $newSetupExam->exam_id = $exam->id;
                $newSetupExam->exam_title = $ex_title;
                $newSetupExam->exam_mark = $ex_mark;
                $newSetupExam->created_at = YearCheck::getYear() . '-' . date('m-d h:i:s');
                $newSetupExam->school_id = Auth::user()->school_id;
                $newSetupExam->academic_id = getAcademicId();
                $newSetupExam->save();
            } //end loop exam setup loop
            DB::commit();
            Toastr::success('Operation successful', 'Success');
            return redirect('exam');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function examSetup($id)
    {
        try {
            $exam = SmExam::find($id);
            $exams = SmExam::where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
                if (Auth::user()->role_id==1) {
                $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            } else {
                $teacher_info=SmStaff::where('user_id',Auth::user()->id)->first();
                $classes= SmAssignSubject::where('teacher_id',$teacher_info->id)
                ->join('sm_classes','sm_classes.id','sm_assign_subjects.class_id')
                ->where('sm_assign_subjects.academic_id', getAcademicId())
                ->where('sm_assign_subjects.active_status', 1)
                ->where('sm_assign_subjects.school_id',Auth::user()->school_id)
                ->select('sm_classes.id','class_name')
                ->get();
            }
            $subjects = SmSubject::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $sections = SmSection::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            return view('backEnd.examination.exam_setup', compact('exam', 'exams', 'classes', 'subjects', 'sections'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function examSetupStore(Request $request)
    {
        try {
            $class_id = $request->class;
            $section_id = $request->section;
            $subject_id = $request->subject;
            $exam_term_id = $request->exam_term_id;

            $total_exam_mark = $request->total_exam_mark;
            $totalMark = $request->totalMark;

            if ($total_exam_mark == $totalMark) {
                $length = count($request->exam_title);
                for ($i = 0; $i < $length; $i++) {
                    $ex_title = $request->exam_title[$i];
                    $ex_mark = $request->exam_mark[$i];

                    $newSetupExam = new SmExamSetup();
                    $newSetupExam->class_id = $class_id;
                    $newSetupExam->section_id = $section_id;
                    $newSetupExam->subject_id = $subject_id;
                    $newSetupExam->exam_term_id = $exam_term_id;
                    $newSetupExam->exam_title = $ex_title;
                    $newSetupExam->exam_mark = $ex_mark;
                    $newSetupExam->created_at = YearCheck::getYear() . '-' . date('m-d h:i:s');
                    $newSetupExam->school_id = Auth::user()->school_id;
                    $newSetupExam->academic_id = getAcademicId();
                    $result = $newSetupExam->save();
                    if ($result) {
                        Toastr::success('Operation successful', 'Success');
                        return redirect('exam');
                    } else {
                        Toastr::error('Operation Failed', 'Failed');
                        return redirect()->back();
                    }
                }
            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function destroy(Request $request, $id)
    {
        try {

            DB::beginTransaction();
            try {
                DB::statement('SET FOREIGN_KEY_CHECKS=0;');
                SmExamSetup::where('exam_id', $id)->delete();
                $delete_query = SmExam::destroy($id);
                if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                    if ($delete_query) {
                        return ApiBaseMethod::sendResponse(null, 'Exam has been deleted successfully');
                    } else {
                        return ApiBaseMethod::sendError('Something went wrong, please try again.');
                    }
                } else {
                    if ($delete_query) {
                        DB::commit();
                        Toastr::success('Operation successful', 'Success');
                        return redirect()->back();
                    } else {
                        Toastr::error('Operation Failed', 'Failed');
                        return redirect()->back();
                    }
                }
            } catch (\Illuminate\Database\QueryException $e) {
                Toastr::error('This item already used', 'Failed');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function getClassSubjects(Request $request)
    {
        try {
            $subjects = SmAssignSubject::where('class_id', $request->id)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $subjects = $subjects->groupBy('subject_id');

            $assinged_subjects = [];
            foreach ($subjects as $key => $subject) {
                $assinged_subjects[] = SmSubject::find($key);
            }
            return response()->json($assinged_subjects);
        } catch (\Exception $e) {
            return response()->json("", 404);
        }
    }


    public function subjectAssignCheck(Request $request)
    {
        try {
            $exam = [];
            $assigned_subjects = [];
            foreach ($request->exam_types as $exam_type) {
                $exam = SmExam::where('exam_type_id', $exam_type)->where('class_id', $request->class_id)->where('subject_id', $request->id)->first();

                if ($exam != "") {
                    $exam_title = SmExamType::find($exam_type);

                    $assigned_subjects[] = $exam_title->title;
                }
            }
            return response()->json($assigned_subjects);
        } catch (\Exception $e) {
            return response()->json("", 404);
        }
    }
}