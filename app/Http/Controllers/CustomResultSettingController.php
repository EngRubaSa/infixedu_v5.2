<?php

namespace App\Http\Controllers;

use Throwable;
use App\SmExam;
use App\SmClass;
use App\SmSection;
use App\SmStudent;
use App\YearCheck;
use App\SmExamType;
use App\ApiBaseMethod;
use App\SmAssignSubject;
use App\SmGeneralSettings;
use App\CustomResultSetting;
use Illuminate\Http\Request;
use App\SmCustomTemporaryResult;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Debug\Exception\FatalThrowableError;

class CustomResultSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('PM');
        // User::checkAuth();
    }

    public function index()
    {

        try {
            $exams = SmExamType::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $custom_settings = DB::table('custom_result_settings as crs')
                ->join('sm_exam_types AS e1', 'crs.exam_term_id1', '=', 'e1.id')
                ->join('sm_exam_types AS e2', 'crs.exam_term_id2', '=', 'e2.id')
                ->join('sm_exam_types AS e3', 'crs.exam_term_id3', '=', 'e3.id')
                ->select('e1.title as exam_1', 'e2.title as exam_2', 'e3.title as exam_3', 'crs.*')
                ->where('crs.academic_id', getAcademicId())
                ->where('e1.school_id', Auth::user()->school_id)
                ->get();
                $system_setting= SmGeneralSettings::where('school_id',Auth::user()->school_id)->first();

                $check_exist= CustomResultSetting::where('academic_year','=',$system_setting->session_id)->first();
            // return $custom_settings;
            if ($custom_settings->count() < 1) {

                return view('backEnd.systemSettings.custom_result_setting_add', compact('custom_settings', 'exams'));
            }
            return view('backEnd.systemSettings.custom_result_settings', compact('custom_settings', 'exams'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function store(request $request)
    {

        // return $request;
        $request->validate([
            'exam_term1' => "required",
            'exam_term2' => "required",
            'exam_term3' => "required",

            'percentage_ex_1' => "required",
            'percentage_ex_2' => "required",
            'percentage_ex_3' => "required",
        ]);

        try {
            $system_setting = SmGeneralSettings::where('school_id', Auth::user()->school_id)->first();
            $system_setting = $system_setting->session_id;

            $check_exist = CustomResultSetting::where('academic_year', '=', $system_setting)->where('school_id', Auth::user()->school_id)->first();
            if ($check_exist == '') {
                $custom_setting = new CustomResultSetting();
                $custom_setting->exam_term_id1 = $request->exam_term1;
                $custom_setting->exam_term_id2 = $request->exam_term2;
                $custom_setting->exam_term_id3 = $request->exam_term3;
                $custom_setting->percentage1 = $request->percentage_ex_1;
                $custom_setting->percentage2 = $request->percentage_ex_2;
                $custom_setting->percentage3 = $request->percentage_ex_3;
                $custom_setting->academic_year = $system_setting;
                $custom_setting->school_id = Auth::user()->school_id;
                $custom_setting->created_at = YearCheck::getYear() . '-' . date('m-d h:i:s');
                $custom_setting->academic_id = getAcademicId();
                $custom_setting->save();
            } else {
                $custom_setting = CustomResultSetting::where('academic_year', '=', $system_setting)->where('school_id', Auth::user()->school_id)->first();
                $custom_setting->exam_term_id1 = $request->exam_term1;
                $custom_setting->exam_term_id2 = $request->exam_term2;
                $custom_setting->exam_term_id3 = $request->exam_term3;
                $custom_setting->percentage1 = $request->percentage_ex_1;
                $custom_setting->percentage2 = $request->percentage_ex_2;
                $custom_setting->percentage3 = $request->percentage_ex_3;
                $custom_setting->academic_year = $system_setting;
                $custom_setting->school_id = Auth::user()->school_id;
                $custom_setting->created_at = YearCheck::getYear() . '-' . date('m-d h:i:s');
                $custom_setting->academic_id = getAcademicId();
                $custom_setting->save();
            }

            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function edit($id)
    {

        try {
            $result_setting = CustomResultSetting::where('id', $id)->first();
            $exams = SmExamType::where('active_status', 1)->where('school_id', Auth::user()->school_id)->get();
            $custom_settings = DB::table('custom_result_settings as crs')
                ->join('sm_exam_types AS e1', 'crs.exam_term_id1', '=', 'e1.id')
                ->join('sm_exam_types AS e2', 'crs.exam_term_id2', '=', 'e2.id')
                ->join('sm_exam_types AS e3', 'crs.exam_term_id3', '=', 'e3.id')
                ->where('crs.school_id', Auth::user()->school_id)
                ->select('e1.title as exam_1', 'e2.title as exam_2', 'e3.title as exam_3', 'crs.*')
                ->get();

            return view('backEnd.systemSettings.custom_result_setting_add', compact('custom_settings', 'exams', 'result_setting'));
        } catch (\Exception $e) {
            Toastr::error('Data not found', 'Failed');
            return redirect()->back();
        }
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'exam_term1' => "required",
            'exam_term2' => "required",
            'exam_term3' => "required",

            'percentage_ex_1' => "required",
            'percentage_ex_2' => "required",
            'percentage_ex_3' => "required",
        ]);


        try {
            $custom_setting = CustomResultSetting::findOrfail($id);
            $custom_setting->exam_term_id1 = $request->exam_term1;
            $custom_setting->exam_term_id2 = $request->exam_term2;
            $custom_setting->exam_term_id3 = $request->exam_term3;
            $custom_setting->percentage1 = $request->percentage_ex_1;
            $custom_setting->percentage2 = $request->percentage_ex_2;
            $custom_setting->percentage3 = $request->percentage_ex_3;
            $custom_setting->created_at = YearCheck::getYear() . '-' . date('m-d h:i:s');
            $custom_setting->academic_id = getAcademicId();
            $custom_setting->update();

            Toastr::success('Operation successful', 'Success');
            return redirect('custom-result-setting');
        } catch (\Exception $e) {

            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function delete($id)
    {
        try {
            $result_setting = CustomResultSetting::findOrfail($id);
            $result_setting->delete();
            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    // START REPORT GENERATION

    // http://localhost/infix/101.infixedu.v4/api/custom-merit-list?exam_term=1&class=1&section=1
    // http://localhost/infix/101.infixedu.v4/api/custom-progress-card?exam_term=1&class=1&section=1&student=1&subject=3
    // http://localhost/infix/101.infixedu.v4/api/student-final-result?exam_term=1&class=1&section=1&student=1

    public function meritListReportIndex(Request $request)
    {
        try {
            $exams = SmExamType::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['exams'] = $exams->toArray();
                $data['classes'] = $classes->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }
            return view('backEnd.reports.custom_merit_list_report', compact('exams', 'classes'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }




    function getPercentageFromExam($id, $key)
    {
        try {
            $system_setting = SmGeneralSettings::find(1);
            $system_setting = $system_setting->session_id;
            $custom_result_setup = CustomResultSetting::where('academic_year', $system_setting)->first();
            // return 0.25;
            // return gettype($custom_result_setup->percentage1);
            if ($key == 0) {
                $percentage = $custom_result_setup->percentage1;
                return $custom_result_setup->percentage1 * .01;
            } elseif ($key == 1) {
                $percentage = $custom_result_setup->percentage2;
                return $custom_result_setup->percentage2 * .01;
            } elseif ($key == 2) {
                $percentage = $custom_result_setup->percentage3;
                return $custom_result_setup->percentage3 * .01;
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    function getGradeNameFromGPA($marks)
    {
        try {
            $marks_gpa = DB::table('sm_marks_grades')->where('from', '<=', $marks)->where('up', '>=', $marks)->where('school_id', Auth::user()->school_id)->first();
            return $marks_gpa->grade_name;
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function meritListReport(Request $request)
    {

        $request->validate([
            'class' => 'required',
            'section' => 'required'
        ]);

        try {
            $iid = time();
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            // if ($request->method() == 'POST') {
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
            $InputExamId = $request->exam;
            $InputClassId = $request->class;
            $InputSectionId = $request->section;

            $class          = SmClass::find($InputClassId);
            $section        = SmSection::find($InputSectionId);

            $class_name = $class->class_name;

            $result_archive = [];

            $assigned_exam = SmExam::where('class_id', $InputClassId)->where('section_id', $InputSectionId)->select('exam_type_id')->DISTINCT()->get();
            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $eligible_students       = SmStudent::where('class_id', $InputClassId)->where('section_id', $InputSectionId)->where('academic_id', getAcademicId())->where('active_status',1)->get();
            $eligible_subjects       = SmAssignSubject::where('class_id', $InputClassId)->where('section_id', $InputSectionId)->where('academic_id', getAcademicId())->get();
            $student_yearly_result = [];
            $result_archive = [];


            $student_result = [];
            foreach ($eligible_students as  $student) {
                $store = SmCustomTemporaryResult::where('student_id',  $student->id)->first();
                if ($store == null) {
                    $store = new SmCustomTemporaryResult();
                }

                $store->student_id = $student->id;
                $store->admission_no = $student->admission_no;
                $store->full_name = $student->full_name;
                $store->school_id = Auth::user()->school_id;

                $resultk49 = 0;
                $term_count = 1;
                foreach ($assigned_exam as $key => $exam_term) {
                    $term_result = CustomResultSetting::termResult($exam_term->exam_type_id, $InputClassId, $InputSectionId, $student->id, $eligible_subjects->count());
                    $custom_term = 'term' . $term_count;
                    $custom_gpa = 'gpa' . $term_count;
                    $store->$custom_term  = $exam_term->exam_type_id;
                    $store->$custom_gpa  = number_format((float) $term_result, 2, '.', '');
                    $var = ($term_result * $this->getPercentageFromExam($exam_term->exam_type_id, $key));
                    $resultk49 = $resultk49 + $var;
                    $term_count ++;
                }
                $store->final_result  = number_format((float) $resultk49, 2, '.', '');
                $store->final_grade  = $this->getGradeNameFromGPA(number_format((float) $resultk49, 2, '.', ''));
                // return $store->final_grade;
                $store->save();
            }

            $customresult = SmCustomTemporaryResult::orderBy('final_result', 'DESC')->where('school_id', Auth::user()->school_id)->get();
            $assign_subjects = SmAssignSubject::where('class_id', $class->id)->where('section_id', $section->id)->where('academic_id', getAcademicId())->get();
            //    return $customresult;
            $system_setting = SmGeneralSettings::where('school_id', Auth::user()->school_id)->first();
            $system_setting = $system_setting->session_id;
            $custom_result_setup = CustomResultSetting::where('academic_year', $system_setting)->first();

            if (!empty($custom_result_setup)) {

                return view('backEnd.reports.custom_merit_list_report', compact('customresult', 'iid', 'classes', 'section', 'class_name', 'assign_subjects', 'InputClassId',  'InputSectionId', 'custom_result_setup'));
            } else {
                Toastr::warning('Please Complete Custom Setup', 'Warning');
                return redirect()->back();
            }
        } catch (Exception $e) {
            
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        } catch (\Exception $e) {
            // dd($e);
            // return $e;
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        } catch (\Exception $e) {
            
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function meritListReportPrint(Request $request, $class, $section)
    {


        try {
            $iid = time();
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            if ($request->method() == 'GET') {
                $input = $request->all();

                $InputClassId = $class;
                $InputSectionId = $section;

                $class          = SmClass::find($InputClassId);
                $section        = SmSection::find($InputSectionId);
                $class_name =$class ->class_name;



                $result_archive = [];

                $assigned_exam = SmExam::where('class_id', $InputClassId)->where('section_id', $InputSectionId)->select('exam_type_id')->DISTINCT()->get();
                $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
                $eligible_students       = SmStudent::where('class_id', $InputClassId)->where('section_id', $InputSectionId)->where('academic_id', getAcademicId())->get();
                $eligible_subjects       = SmAssignSubject::where('class_id', $InputClassId)->where('section_id', $InputSectionId)->where('academic_id', getAcademicId())->get();
                $student_yearly_result = [];
                $result_archive = [];


                $student_result = [];
                foreach ($eligible_students as  $student) {
                    $store = SmCustomTemporaryResult::where('student_id',  $student->id)->first();
                    if ($store == null) {
                        $store = new SmCustomTemporaryResult();
                    }

                    $store->student_id = $student->id;
                    $store->admission_no = $student->admission_no;
                    $store->full_name = $student->full_name;
                    $store->school_id = Auth::user()->school_id;

                    $resultk49 = 0;
                    $term_count = 1;
                    foreach ($assigned_exam as $key => $exam_term) {

                        $term_result = CustomResultSetting::termResult($exam_term->exam_type_id, $InputClassId, $InputSectionId, $student->id, $eligible_subjects->count());
                        $custom_term = 'term' . $term_count;
                        $custom_gpa = 'gpa' . $term_count;
                        $store->$custom_term  = $exam_term->exam_type_id;
                        $store->$custom_gpa  = number_format((float) $term_result, 2, '.', '');

                        $resultk49 = $resultk49 + ($term_result * $this->getPercentageFromExam($exam_term->exam_type_id, $key));



                        $term_count++;
                    }
                    $store->final_result  = number_format((float) $resultk49, 2, '.', '');
                    $store->final_grade  = $this->getGradeNameFromGPA(number_format((float) $resultk49, 2, '.', ''));
                    // return $store->final_grade;
                    $store->academic_id = getAcademicId();
                    $store->save();
                }

                $customresult = SmCustomTemporaryResult::orderBy('final_result', 'DESC')->where('school_id', Auth::user()->school_id)->get();
                $assign_subjects = SmAssignSubject::where('class_id', $class->id)->where('section_id', $section->id)->where('academic_id', getAcademicId())->get();
                $system_setting = SmGeneralSettings::where('school_id', Auth::user()->school_id)->first();
                $system_setting = $system_setting->session_id;
                $custom_result_setup = CustomResultSetting::where('academic_year', $system_setting)->first();
                return view('backEnd.reports.custom_merit_list_report_print', compact('customresult', 'iid', 'classes', 'section', 'class_name', 'assign_subjects', 'InputClassId',  'InputSectionId', 'custom_result_setup'));

            

            }
        } catch (\Exception $e) {
  
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function progressCardReportIndex(Request $request)
    {
        try {
            $exams = SmExam::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['routes'] = $exams->toArray();
                $data['assign_vehicles'] = $classes->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }

            return view('backEnd.reports.custom_progress_card_report', compact('exams', 'classes'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function progressCardReport(Request $request)
    {

        // return $request;
        $input = $request->all();
        $validator = Validator::make($input, [
            'class' => 'required',
            'section' => 'required',
            'student' => 'required'
        ]);

        $input_class = $input['class'];
        $input_section = $input['section'];
        $input_student = $input['student'];
        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        try {
            $exams = SmExam::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $class = SmClass::findOrfail($request->class);
            $section = SmClass::findOrfail($request->section);
            $studentDetails = SmStudent::where('sm_students.id', '=', $request->student)
                ->join('sm_academic_years', 'sm_academic_years.id', '=', 'sm_students.session_id')
                ->join('sm_classes', 'sm_classes.id', '=', 'sm_students.class_id')
                ->join('sm_sections', 'sm_sections.id', '=', 'sm_students.section_id')
                ->first();
            $system_setting = SmGeneralSettings::where('school_id', Auth::user()->school_id)->first();
            $system_setting = $system_setting->session_id;
            $custom_result_setup = CustomResultSetting::where('academic_year', $system_setting)->first();
            $assign_subjects = SmAssignSubject::where('class_id', $class->id)->where('section_id', $section->id)->where('sm_assign_subjects.academic_id', getAcademicId())->join('sm_subjects', 'sm_subjects.id', '=', 'sm_assign_subjects.subject_id')->get();
            $assigned_exam = SmExam::where('class_id', $class->id)->where('section_id', $section->id)->select('exam_type_id', 'title')->join('sm_exam_types', 'sm_exam_types.id', '=', 'sm_exams.exam_type_id')->DISTINCT()->get();

            if ($assigned_exam->count() != 3) {
                Toastr::error('Result not found for this class, At least you have to complete 3 terms results', 'Failed');
                return redirect()->back();
            }

            return view('backEnd.reports.custom_progress_card_report', compact('exams', 'classes', 'studentDetails', 'assign_subjects', 'assigned_exam', 'custom_result_setup', 'input_section', 'input_class', 'input_student'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function progressCardReportPrint(Request $request)
    {

        // return $request;
        $input = $request->all();
        $validator = Validator::make($input, [
            'class' => 'required',
            'section' => 'required',
            'student' => 'required'
        ]);

        $input_class = $input['class'];
        $input_section = $input['section'];
        $input_student = $input['student'];
        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        try {
            $exams = SmExam::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $class = SmClass::findOrfail($request->class);
            $section = SmSection::findOrfail($request->section);
            $studentDetails = SmStudent::where('sm_students.id', '=', $request->student)
                ->join('sm_academic_years', 'sm_academic_years.id', '=', 'sm_students.session_id')
                ->join('sm_classes', 'sm_classes.id', '=', 'sm_students.class_id')
                ->join('sm_sections', 'sm_sections.id', '=', 'sm_students.section_id')
                ->first();
            $system_setting = SmGeneralSettings::where('school_id', Auth::user()->school_id)->first();
            $system_setting = $system_setting->session_id;
            $custom_result_setup = CustomResultSetting::where('academic_year', $system_setting)->first();
            $assign_subjects = SmAssignSubject::where('class_id', $class->id)->where('section_id', $section->id)->where('sm_assign_subjects.academic_id', getAcademicId())->join('sm_subjects', 'sm_subjects.id', '=', 'sm_assign_subjects.subject_id')->get();
            $assigned_exam = SmExam::where('class_id', $class->id)->where('section_id', $section->id)->select('exam_type_id', 'title')->join('sm_exam_types', 'sm_exam_types.id', '=', 'sm_exams.exam_type_id')->DISTINCT()->get();

            if ($assigned_exam->count() != 3) {
                Toastr::error('Result not found for this class', 'Failed');
                return redirect()->back();
            }

            return view('backEnd.reports.custom_progress_card_print', compact('exams', 'classes', 'studentDetails', 'assign_subjects', 'assigned_exam', 'custom_result_setup', 'input_section', 'input_class', 'input_student', 'class', 'section'));

        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }

        $input = $request->all();
        $validator = Validator::make($input, [
            'class' => 'required',
            'section' => 'required',
            'student' => 'required'
        ]);

        $input_class = $input['class'];
        $input_section = $input['section'];
        $input_student = $input['student'];
        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        try {
            $exams = SmExam::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $class = SmClass::findOrfail($request->class);
            $section = SmSection::findOrfail($request->section);
            $studentDetails = SmStudent::where('sm_students.id', '=', $request->student)
                ->join('sm_academic_years', 'sm_academic_years.id', '=', 'sm_students.session_id')
                ->join('sm_classes', 'sm_classes.id', '=', 'sm_students.class_id')
                ->join('sm_sections', 'sm_sections.id', '=', 'sm_students.section_id')
                ->first();
            $system_setting = SmGeneralSettings::where('school_id', Auth::user()->school_id)->first();
            $system_setting = $system_setting->session_id;
            $custom_result_setup = CustomResultSetting::where('academic_year', $system_setting)->first();
            $assign_subjects = SmAssignSubject::where('class_id', $class->id)->where('section_id', $section->id)->where('sm_assign_subjects.academic_id', getAcademicId())->join('sm_subjects', 'sm_subjects.id', '=', 'sm_assign_subjects.subject_id')->get();
            $assigned_exam = SmExam::where('class_id', $class->id)->where('section_id', $section->id)->select('exam_type_id', 'title')->join('sm_exam_types', 'sm_exam_types.id', '=', 'sm_exams.exam_type_id')->DISTINCT()->get();

            if ($assigned_exam->count() != 3) {
                Toastr::error('Result not found for this class', 'Failed');
                return redirect()->back();
            }

            return view('backEnd.reports.custom_progress_card_print', compact('exams', 'classes', 'studentDetails', 'assign_subjects', 'assigned_exam', 'custom_result_setup', 'input_section', 'input_class', 'input_student', 'class', 'section'));

       
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function studentFinalResult(Request $request)
    {

        try {
            $exam = $request->exam_term;
            $class = $request->class;
            $section = $request->section;
            $student = $request->student;

            $first_term_percentage = 'percentage1';
            $second_term_percentage = 'percentage2';
            $third_term_percentage = 'percentage3';

            $first_term_result = CustomResultSetting::getFinalResult($exam, $class, $section, $student, $first_term_percentage);
            $second_term_result = CustomResultSetting::getFinalResult($exam, $class, $section, $student, $second_term_percentage);
            $third_term_result = CustomResultSetting::getFinalResult($exam, $class, $section, $student, $third_term_percentage);

            $final_result = $first_term_result + $second_term_result + $third_term_result;
            return $third_term_result;
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}