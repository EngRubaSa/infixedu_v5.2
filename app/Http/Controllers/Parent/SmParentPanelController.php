<?php

namespace App\Http\Controllers\Parent;

use App\Role;
use App\User;
use App\SmBook;
use App\SmExam;
use App\SmClass;
use App\SmEvent;
use App\SmRoute;
use App\SmStaff;
use App\SmParent;
use App\SmHoliday;
use App\SmStudent;
use App\SmSubject;
use App\SmVehicle;
use App\SmWeekend;
use App\YearCheck;
use App\SmExamType;
use App\SmHomework;
use App\SmRoomList;
use App\SmRoomType;
use App\SmBaseSetup;
use App\SmBookIssue;
use App\SmClassTime;
use App\SmLeaveType;
use App\SmFeesAssign;
use App\SmMarksGrade;
use App\SmOnlineExam;
use App\ApiBaseMethod;
use App\SmFeesPayment;
use App\SmLeaveDefine;
use App\SmNoticeBoard;
use App\SmAcademicYear;
use App\SmExamSchedule;
use App\SmLeaveRequest;
use App\SmStudentGroup;
use App\SmAssignSubject;
use App\SmAssignVehicle;
use App\SmDormitoryList;
use App\SmLibraryMember;
use App\SmGeneralSettings;
use App\SmStudentCategory;
use App\SmStudentDocument;
use App\SmStudentTimeline;
use App\SmStudentAttendance;
use Illuminate\Http\Request;
use App\SmFeesAssignDiscount;
use App\SmStudentTakeOnlineExam;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Modules\RolePermission\Entities\InfixRole;


class SmParentPanelController extends Controller
{

    public function __construct()
    {
        $this->middleware('PM');
        // User::checkAuth();
    }

    public function parentDashboard()
    {
        try {
            $holidays = SmHoliday::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();


            $events = SmEvent::where('active_status', 1)
                ->where('academic_id', getAcademicId())
                ->where('school_id', Auth::user()->school_id)
                ->where(function ($q) {
                    $q->where('for_whom', 'All')->orWhere('for_whom', 'Parents');
                })
                ->get();

            $count_event =0;
            $calendar_events = array();

            foreach($holidays as $k => $holiday) {

                $calendar_events[$k]['title'] = $holiday->holiday_title;

                $calendar_events[$k]['start'] = $holiday->from_date;

                $calendar_events[$k]['end'] = $holiday->to_date;

                $calendar_events[$k]['description'] = $holiday->details;

                $calendar_events[$k]['url'] = $holiday->upload_image_file;

                $count_event = $k;
                $count_event++;
            }

            foreach($events as $k => $event) {

                $calendar_events[$count_event]['title'] = $event->event_title;

                $calendar_events[$count_event]['start'] = $event->from_date;

                $calendar_events[$count_event]['end'] = $event->to_date;
                $calendar_events[$count_event]['description'] = $event->event_des;
                $calendar_events[$count_event]['url'] = $event->uplad_image_file;
                $count_event++;
            }
            $totalNotices = SmNoticeBoard::where('active_status', '=', 1)->where('is_published', '=', 1)->where('academic_id', YearCheck::getAcademicId())->get();
            return view('backEnd.parentPanel.parent_dashboard', compact('holidays','calendar_events', 'events','totalNotices'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function studentUpdate(Request $request)
    {
        $request->validate([
            'document_file_1' => "sometimes|nullable|mimes:pdf,doc,docx,jpg,jpeg,png,txt",
            'document_file_2' => "sometimes|nullable|mimes:pdf,doc,docx,jpg,jpeg,png,txt",
            'document_file_3' => "sometimes|nullable|mimes:pdf,doc,docx,jpg,jpeg,png,txt",
            'document_file_4' => "sometimes|nullable|mimes:pdf,doc,docx,jpg,jpeg,png,txt",
        ]);
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $student_detail = SmStudent::find($request->id);

        if(!empty($request->guardians_email)){
            $is_duplicate = SmParent::where('school_id', Auth::user()->school_id)->where('guardians_email', $request->guardians_email)->where('id', '!=', $student_detail->parent_id)->first();
            if ($is_duplicate) {
                Toastr::error('Duplicate guardian email found!', 'Failed');
                return redirect()->back()->withInput();
            }
        }

        if(!empty($request->guardians_mobile)){
            $is_duplicate = SmParent::where('school_id', Auth::user()->school_id)->where('guardians_mobile', $request->guardians_mobile)->where('id', '!=', $student_detail->parent_id)->first();
            if ($is_duplicate) {
                Toastr::error('Duplicate guardian mobile number found!', 'Failed');
                return redirect()->back()->withInput();
            }
        } 


        // always happen start

        $document_file_1 = "";
        if ($request->file('document_file_1') != "") {
            $maxFileSize = SmGeneralSettings::first('file_size')->file_size;
            $file = $request->file('document_file_1');
            $fileSize =  filesize($file);
            $fileSizeKb = ($fileSize / 1000000);
            if($fileSizeKb >= $maxFileSize){
                Toastr::error( 'Max upload file size '. $maxFileSize .' Mb is set in system', 'Failed');
                return redirect()->back();
            }
            if ($student_detail->document_file_1 != "") {
                if (file_exists($student_detail->document_file_1)) {
                    unlink($student_detail->document_file_1);
                }
            }
            $file = $request->file('document_file_1');
            $document_file_1 = 'doc1-' . md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
            $file->move('public/uploads/student/document/', $document_file_1);
            $document_file_1 =  'public/uploads/student/document/' . $document_file_1;
        }

        $document_file_2 = "";
        if ($request->file('document_file_2') != "") {
            $maxFileSize = SmGeneralSettings::first('file_size')->file_size;
            $file = $request->file('document_file_2');
            $fileSize =  filesize($file);
            $fileSizeKb = ($fileSize / 1000000);
            if($fileSizeKb >= $maxFileSize){
                Toastr::error( 'Max upload file size '. $maxFileSize .' Mb is set in system', 'Failed');
                return redirect()->back();
            }
            if ($student_detail->document_file_2 != "") {
                if (file_exists($student_detail->document_file_2)) {
                    unlink($student_detail->document_file_2);
                }
            }
            $file = $request->file('document_file_2');
            $document_file_2 = 'doc2-' . md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
            $file->move('public/uploads/student/document/', $document_file_2);
            $document_file_2 =  'public/uploads/student/document/' . $document_file_2;
        }

        $document_file_3 = "";
        if ($request->file('document_file_3') != "") {
            $maxFileSize = SmGeneralSettings::first('file_size')->file_size;
            $file = $request->file('document_file_3');
            $fileSize =  filesize($file);
            $fileSizeKb = ($fileSize / 1000000);
            if($fileSizeKb >= $maxFileSize){
                Toastr::error( 'Max upload file size '. $maxFileSize .' Mb is set in system', 'Failed');
                return redirect()->back();
            }
            if ($student_detail->document_file_3 != "") {
                if (file_exists($student_detail->document_file_3)) {
                    unlink($student_detail->document_file_3);
                }
            }
            $file = $request->file('document_file_3');
            $document_file_3 = 'doc3-' . md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
            $file->move('public/uploads/student/document/', $document_file_3);
            $document_file_3 =  'public/uploads/student/document/' . $document_file_3;
        }

        $document_file_4 = "";
        if ($request->file('document_file_4') != "") {
            $maxFileSize = SmGeneralSettings::first('file_size')->file_size;
            $file = $request->file('document_file_4');
            $fileSize =  filesize($file);
            $fileSizeKb = ($fileSize / 1000000);
            if($fileSizeKb >= $maxFileSize){
                Toastr::error( 'Max upload file size '. $maxFileSize .' Mb is set in system', 'Failed');
                return redirect()->back();
            }
            if ($student_detail->document_file_4 != "") {
                if (file_exists($student_detail->document_file_4)) {
                    unlink($student_detail->document_file_4);
                }
            }
            $file = $request->file('document_file_4');
            $document_file_4 = 'doc4-' . md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
            $file->move('public/uploads/student/document/', $document_file_4);
            $document_file_4 =  'public/uploads/student/document/' . $document_file_4;
        }


        if ($request->relation == 'Father') {
            $guardians_photo = "";

            if ($request->file('fathers_photo') != "") {
                $student = SmStudent::find($request->id);

                if (@$student->parents->guardians_photo != "") {
                    if (file_exists(@$student->parents->guardians_photo)) {
                        unlink(@$student->parents->guardians_photo);
                    }
                }


                $guardians_photo =  Session::get('fathers_photo');
            }
        } elseif ($request->relation == 'Mother') {
            $guardians_photo = "";
            if ($request->file('mothers_photo') != "") {
                $student = SmStudent::find($request->id);

                if (@$student->parents->guardians_photo != "") {
                    if (file_exists(@$student->parents->guardians_photo)) {
                        unlink(@$student->parents->guardians_photo);
                    }
                }

                $guardians_photo =  Session::get('mothers_photo');
            }
        } elseif ($request->relation == 'Other') {
            $guardians_photo = "";
            if ($request->file('guardians_photo') != "") {
                $student = SmStudent::find($request->id);

                if (@$student->parents->guardians_photo != "") {
                    if (file_exists(@$student->parents->guardians_photo)) {
                        unlink(@$student->parents->guardians_photo);
                    }
                }

                $guardians_photo =  Session::get('guardians_photo');
            }
        }


        $shcool_details = SmGeneralSettings::where('school_id',Auth::user()->school_id)->first();
        $school_name = explode(' ', $shcool_details->school_name);
        $short_form = '';

        foreach ($school_name as $value) {
            $ch = str_split($value);
            $short_form = $short_form . '' . $ch[0];
        }



        DB::beginTransaction();

        try { 


            $user_stu = User::find($student_detail->user_id);   
            $user_stu->email = $request->email_address;   
            $user_stu->save();
            $user_stu->toArray();

            try {  
                    $user_parent = User::find($student_detail->parents->user_id);
                    $user_parent->role_id = 3;
                    $user_parent->username = $request->guardians_email;
                    $user_parent->email = $request->guardians_email;
                    $user_parent->password = Hash::make(123456);
                    $user_parent->save(); 
                try {
 

                        $parent = SmParent::find($student_detail->parent_id);
                        $parent->user_id = $user_parent->id;
                        $parent->fathers_name = $request->fathers_name;
                        $parent->fathers_mobile = $request->fathers_phone;
                        $parent->fathers_occupation = $request->fathers_occupation;
                        if (Session::get('fathers_photo') != "") {
                            $parent->fathers_photo = Session::get('fathers_photo');
                        }

                        $parent->mothers_name = $request->mothers_name;
                        $parent->mothers_mobile = $request->mothers_phone;
                        $parent->mothers_occupation = $request->mothers_occupation;
                        if (Session::get('mothers_photo') != "") {
                            $parent->mothers_photo = Session::get('mothers_photo');
                        }
                        $parent->guardians_name = $request->guardians_name;
                        $parent->guardians_mobile = $request->guardians_phone;
                        $parent->guardians_email = $request->guardians_email;
                        $parent->guardians_occupation = $request->guardians_occupation;
                        $parent->guardians_relation = $request->relation;
                        $parent->relation = $request->relationButton;

                        // if guardian pic updated then add it
                        if ($guardians_photo != "") {
                            $parent->guardians_photo = $guardians_photo;
                        }

                        $parent->guardians_address = $request->guardians_address;
                        $parent->is_guardian = $request->is_guardian; 
                        $parent->save();
                        $parent->toArray(); 

                    try {
                     
                      $student = SmStudent::find($request->id);

                        if (($request->sibling_id == 0 || $request->sibling_id == 1) && $request->parent_id == "") {
                            $student->parent_id = $parent->id;
                        } elseif ($request->sibling_id == 0 && $request->parent_id != "") {
                            $student->parent_id = $request->parent_id;
                        } elseif (($request->sibling_id == 2 || $request->sibling_id == 1) && $request->parent_id != "") {
                            $student->parent_id = $request->parent_id;
                        } elseif ($request->sibling_id == 2 && $request->parent_id == "") {
                            $student->parent_id = $parent->id;
                        }
                       // $student->class_id = $request->class;
                        // $student->section_id = $request->section;
                       // $student->session_id = $request->session;
                       // $student->user_id = $user_stu->id;

                      //  $student->admission_no = $request->admission_number;

                      //  $student->roll_no = $request->roll_number;
                        $student->first_name = $request->first_name;
                        $student->last_name = $request->last_name;
                        $student->full_name = $request->first_name . ' ' . $request->last_name;
                        $student->gender_id = $request->gender;
                        $student->date_of_birth = date('Y-m-d', strtotime($request->date_of_birth));
                       

                        $student->age = $request->age;


                        $student->caste = $request->caste;
                        $student->email = $request->email_address;
                        $student->mobile = $request->phone_number;
                        $student->admission_date = date('Y-m-d', strtotime($request->admission_date));

                        if (Session::get('student_photo') != "") {
                            $student->student_photo = Session::get('student_photo');
                        }

                        /* if ($student_photo != "") {
                        } */
                        if (@$request->blood_group != "") {
                            $student->bloodgroup_id = $request->blood_group;
                        }
                        if (@$request->religion != "") {
                            $student->religion_id = $request->religion;
                        }

                        $student->height = $request->height;
                        $student->weight = $request->weight;
                        $student->current_address = $request->current_address;
                        $student->permanent_address = $request->permanent_address;
                        $student->student_category_id = $request->student_category_id;
                        $student->student_group_id = $request->student_group_id;
                      

 

                        // $student->driver_phone_no = $request->driver_phone;
                        $student->national_id_no = $request->national_id_number;
                        $student->local_id_no = $request->local_id_number;
                        $student->bank_account_no = $request->bank_account_number;
                        $student->bank_name = $request->bank_name;
                        $student->previous_school_details = $request->previous_school_details;
                        $student->aditional_notes = $request->additional_notes;
                        $student->ifsc_code = $request->ifsc_code;
                        $student->document_title_1 = $request->document_title_1;
                        if ($document_file_1 != "") {
                            $student->document_file_1 =  $document_file_1;
                        }

                        $student->document_title_2 = $request->document_title_2;
                        if ($document_file_2 != "") {
                            $student->document_file_2 =  $document_file_2;
                        }

                        $student->document_title_3 = $request->document_title_3;
                        if ($document_file_3 != "") {
                            $student->document_file_3 = $document_file_3;
                        }

                        $student->document_title_4 = $request->document_title_4;

                        if ($document_file_4 != "") {
                            $student->document_file_4 = $document_file_4;
                        } 

                       

                        $student->save();
                        DB::commit();


                        // session null

                        $update_parent = SmParent::where('user_id', Auth::user()->id)->first('guardians_photo');
                        Session::put('profile', $update_parent->guardians_photo);



                        Toastr::success('Operation successful', 'Success');
                        return redirect('parent-dashboard');
                    } catch (\Exception $e) {
                        return $e->getMessage();
                        DB::rollback();
                        Toastr::error('Operation Failed', 'Failed');
                        return redirect()->back();
                    }
                } catch (\Exception $e) {
                    return $e->getMessage();
                    DB::rollback();
                    Toastr::error('Operation Failed', 'Failed');
                    return redirect()->back();
                }
            } catch (\Exception $e) {
                return $e->getMessage();
                // return $e;
                DB::rollback();
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            return $e->getMessage();
            DB::rollback();
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }








    public function UpdatemyChildren($id)
    {
        
        try {
            $student = SmStudent::find($id);

            $classes = SmClass::where('active_status', '=', '1')->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();

            $religions = SmBaseSetup::where('active_status', '=', '1')->where('base_group_id', '=', '2')->get();
            $blood_groups = SmBaseSetup::where('active_status', '=', '1')->where('base_group_id', '=', '3')->get();
            $genders = SmBaseSetup::where('active_status', '=', '1')->where('base_group_id', '=', '1')->get();
            $route_lists = SmRoute::where('active_status', '=', '1')->where('school_id', Auth::user()->school_id)->get();
            $vehicles = SmVehicle::where('active_status', '=', '1')->where('school_id', Auth::user()->school_id)->get();
            $dormitory_lists = SmDormitoryList::where('active_status', '=', '1')->where('school_id', Auth::user()->school_id)->get();
            $driver_lists = SmStaff::where([['active_status', '=', '1'], ['role_id', 9]])->where('school_id', Auth::user()->school_id)->get();
            $categories = SmStudentCategory::where('school_id', Auth::user()->school_id)->get();
            $groups = SmStudentGroup::where('school_id', Auth::user()->school_id)->get();
            $sessions = SmAcademicYear::where('active_status', '=', '1')->where('school_id', Auth::user()->school_id)->get();
            $siblings = SmStudent::where('parent_id', $student->parent_id)->where('school_id', Auth::user()->school_id)->get();
            return view('backEnd.parentPanel.update_my_children', compact('student', 'classes', 'religions', 'blood_groups', 'genders', 'route_lists', 'vehicles', 'dormitory_lists', 'categories','groups', 'sessions', 'siblings', 'driver_lists'));
        } catch (\Exception $e) {
            
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function myChildren($id)
    {
        $parent_info = SmParent::where('user_id', Auth::user()->id)->first();
        // return $parent_info;
        try {
            $student_detail = SmStudent::where('id', $id)->where('parent_id', $parent_info->id)->first();
            if ($student_detail) {
                $driver = SmVehicle::where('sm_vehicles.id', $student_detail->vechile_id)
                    ->join('sm_staffs', 'sm_vehicles.driver_id', '=', 'sm_staffs.id')
                    ->where('sm_staffs.school_id', Auth::user()->school_id)
                    ->first();
                //return $driver;
                $fees_assigneds = SmFeesAssign::where('student_id', $student_detail->id)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
                $fees_discounts = SmFeesAssignDiscount::where('student_id', $student_detail->id)->where('academic_id', getAcademicId())
                    ->where('school_id', Auth::user()->school_id)->get();
                $documents = SmStudentDocument::where('student_staff_id', $student_detail->id)->where('type', 'stu')->where('academic_id', getAcademicId())
                    ->where('school_id', Auth::user()->school_id)->get();
                $timelines = SmStudentTimeline::where('staff_student_id', $student_detail->id)->where('type', 'stu')->where('academic_id', getAcademicId())
                    ->where('visible_to_student', 1)->where('school_id', Auth::user()->school_id)->get();
                $exams = SmExamSchedule::where('class_id', $student_detail->class_id)->where('section_id', $student_detail->section_id)
                    ->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
                $grades = SmMarksGrade::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
                $academic_year = SmAcademicYear::where('id', $student_detail->session_id)->first();
                $exam_terms = SmExamType::where('school_id',Auth::user()->school_id)->where('academic_id', getAcademicId())->get();
                // $exam_terms = [];
                return view('backEnd.parentPanel.my_children', compact('student_detail', 'fees_assigneds', 'driver', 'fees_discounts', 'exams', 'documents', 'timelines', 'grades', 'exam_terms','academic_year'));
            } else {
                Toastr::warning('Invalid Student ID', 'Invalid');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function onlineExamination($id)
    {

        try {
            // $student = Auth::user()->student;
            $student = SmStudent::findOrfail($id);
            $now = date('H:i:s');
            // ->where('start_time', '<', $now)
            $online_exams = SmOnlineExam::where('active_status', 1)->where('status', 1)->where('class_id', $student->class_id)->where('section_id', $student->section_id)
                ->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            // return $online_exams;
            $marks_assigned = SmStudentTakeOnlineExam::whereIn('online_exam_id', $online_exams->pluck('id')->toArray())->where('student_id', $student->id)->where('status', 2)
                ->where('school_id', Auth::user()->school_id)->pluck('online_exam_id')->toArray();;

            return view('backEnd.parentPanel.parent_online_exam', compact('online_exams', 'marks_assigned', 'now', 'student'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function parentLeave($id)
    {

        try {
            // return $id;
            $student = SmStudent::findOrfail($id);
            $apply_leaves = SmLeaveRequest::where('staff_id', '=', $student->user_id)
                ->join('sm_leave_defines', 'sm_leave_defines.id', '=', 'sm_leave_requests.leave_define_id')
                ->join('sm_leave_types', 'sm_leave_types.id', '=', 'sm_leave_defines.type_id')
                ->where('sm_leave_requests.academic_id', getAcademicId())
                ->where('sm_leave_requests.school_id', Auth::user()->school_id)->get();
            // return $apply_leaves;

            return view('backEnd.parentPanel.parent_leave', compact('apply_leaves', 'student'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    function leaveApply(Request $request)
    {
        try {
            $user = Auth::user();
            if ($user) {
                $my_leaves = SmLeaveDefine::where('role_id', 2)->where('school_id', Auth::user()->school_id)->get();
                $apply_leaves = SmLeaveRequest::where('role_id', 2)->where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
                $leave_types = SmLeaveDefine::where('role_id', 2)->where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            } else {
                $my_leaves = SmLeaveDefine::where('role_id', $request->role_id)->where('school_id', Auth::user()->school_id)->get();
                $apply_leaves = SmLeaveRequest::where('role_id', $request->role_id)->where('active_status', 1)->where('school_id', Auth::user()->school_id)->get();
                $leave_types = SmLeaveDefine::where('role_id', $request->role_id)->where('active_status', 1)->where('school_id', Auth::user()->school_id)->get();
            }

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['my_leaves'] = $my_leaves->toArray();
                $data['apply_leaves'] = $apply_leaves->toArray();
                $data['leave_types'] = $leave_types->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }
            return view('backEnd.parentPanel.apply_leave', compact('apply_leaves', 'leave_types', 'my_leaves', 'user'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function leaveStore(Request $request)
    {
        // dd($request->all());
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $input = $request->all();
        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
            $validator = Validator::make($input, [
                'student_id' => "required",
                'apply_date' => "required",
                'leave_type' => "required",
                'leave_from' => 'required|before_or_equal:leave_to',
                'leave_to' => "required",
                'login_id' => "required",
                'role_id' => "required",
                'attach_file' => "sometimes|nullable|mimes:pdf,doc,docx,jpg,jpeg,png,txt",
            ]);
        } else {
            $validator = Validator::make($input, [
                'student_id' => "required",
                'apply_date' => "required",
                'leave_type' => "required",
                'leave_from' => 'required|before_or_equal:leave_to',
                'leave_to' => "required",
                'attach_file' => "sometimes|nullable|mimes:pdf,doc,docx,jpg,jpeg,png,txt",
            ]);
        }
        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        try {
            $input = $request->all();
            $fileName = "";
            if ($request->file('attach_file') != "") {
//                'attach_file' => "sometimes|nullable|mimes:pdf,doc,docx,jpg,jpeg,png,txt",
                $maxFileSize = SmGeneralSettings::first('file_size')->file_size;
                $file = $request->file('attach_file');
                $fileSize =  filesize($file);
                $fileSizeKb = ($fileSize / 1000000);
                if($fileSizeKb >= $maxFileSize){
                    Toastr::error( 'Max upload file size '. $maxFileSize .' Mb is set in system', 'Failed');
                    return redirect()->back();
                }
                $file = $request->file('attach_file');
                $fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                $file->move('public/uploads/leave_request/', $fileName);
                $fileName = 'public/uploads/leave_request/' . $fileName;
            }
            $apply_leave = new SmLeaveRequest();
            $apply_leave->staff_id = $request->student_id;
            $apply_leave->role_id = 2;
            $apply_leave->apply_date = date('Y-m-d', strtotime($request->apply_date));
            $apply_leave->leave_define_id = $request->leave_type;
            $apply_leave->type_id = $request->leave_type;
            $apply_leave->leave_from = date('Y-m-d', strtotime($request->leave_from));
            $apply_leave->leave_to = date('Y-m-d', strtotime($request->leave_to));
            $apply_leave->approve_status = 'P';
            $apply_leave->reason = $request->reason;
            $apply_leave->file = $fileName;
            $apply_leave->school_id = Auth::user()->school_id;
            $apply_leave->academic_id = getAcademicId();
            $result = $apply_leave->save();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Leave Request has been created successfully.');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again.');
                }
            } else {
                if ($result) {
                    Toastr::success('Operation successful', 'Success');
                    return redirect()->back();
                } else {
                    Toastr::error('Operation Failed', 'Failed');
                    return redirect()->back();
                }
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function viewLeaveDetails(Request $request, $id)
    {
        try {
            $leaveDetails = SmLeaveRequest::find($id);
            $apply = "";
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['leaveDetails'] = $leaveDetails->toArray();
                $data['apply'] = $apply;
                return ApiBaseMethod::sendResponse($data, null);
            }
            return view('backEnd.parentPanel.viewLeaveDetails', compact('leaveDetails', 'apply'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    function leaveEdit($id)
    {
    }

    public function pendingLeave(Request $request)
    {
        try {
            $apply_leaves = SmLeaveRequest::where([['active_status', 1], ['approve_status', 'P']])->where('academic_id', getAcademicId())
                ->where('school_id', Auth::user()->school_id)->get();
            $leave_types = SmLeaveType::where('active_status', 1)->where('academic_id', getAcademicId())->whereOr(['school_id', Auth::user()->school_id],
                ['school_id', 1])->get();
            $roles = InfixRole::where('id', 2)->where(function ($q) {
                $q->where('school_id', Auth::user()->school_id)->orWhere('type', 'System');
            })->get();
            $pendingRequest = SmLeaveRequest::where('sm_leave_requests.active_status', 1)
                ->select('sm_leave_requests.id', 'full_name', 'apply_date', 'leave_from', 'leave_to', 'reason', 'file', 'sm_leave_types.type', 'approve_status')
                ->join('sm_leave_defines', 'sm_leave_requests.leave_define_id', '=', 'sm_leave_defines.id')
                ->join('sm_staffs', 'sm_leave_requests.staff_id', '=', 'sm_staffs.id')
                ->leftjoin('sm_leave_types', 'sm_leave_requests.type_id', '=', 'sm_leave_types.id')
                ->where('sm_leave_requests.approve_status', '=', 'P')
                ->where('sm_leave_requests.academic_id', getAcademicId())
                ->where('sm_leave_requests.school_id', Auth::user()->school_id)->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['pending_request'] = $pendingRequest->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }
            return view('backEnd.parentPanel.pending_leave', compact('apply_leaves', 'leave_types', 'roles'));
        } catch (\Exception $e) {
            dd($e);
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function parentLeaveEdit(request $request, $id)
    {
        try {
            $user = Auth::user();
            if ($user) {
                $my_leaves = SmLeaveDefine::where('role_id', 2)->where('school_id', Auth::user()->school_id)->get();
                $apply_leaves = SmLeaveRequest::where('role_id', 2)->where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
                $leave_types = SmLeaveDefine::where('role_id', 2)->where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            } else {
                $my_leaves = SmLeaveDefine::where('role_id', $request->role_id)->where('school_id', Auth::user()->school_id)->get();
                $apply_leaves = SmLeaveRequest::where('role_id', $request->role_id)->where('active_status', 1)->where('school_id', Auth::user()->school_id)->get();
                $leave_types = SmLeaveDefine::where('role_id', $request->role_id)->where('active_status', 1)->where('school_id', Auth::user()->school_id)->get();
            }
            $apply_leave = SmLeaveRequest::find($id);

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['my_leaves'] = $my_leaves->toArray();
                $data['apply_leaves'] = $apply_leaves->toArray();
                $data['leave_types'] = $leave_types->toArray();
                $data['apply_leave'] = $apply_leave->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }
            return view('backEnd.parentPanel.apply_leave', compact('apply_leave', 'apply_leaves', 'leave_types', 'my_leaves', 'user'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function update(Request $request)
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $input = $request->all();
        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
            $validator = Validator::make($input, [
                'id' => "required",
                'apply_date' => "required",
                'leave_type' => "required",
                'leave_from' => 'required|before_or_equal:leave_to',
                'leave_to' => "required",
                'login_id' => "required",
                'role_id' => "required",
                'file' => "sometimes|nullable|mimes:pdf,doc,docx,jpg,jpeg,png,txt",
            ]);
        } else {
            $validator = Validator::make($input, [
                'apply_date' => "required",
                'leave_type' => "required",
                'leave_from' => 'required|before_or_equal:leave_to',
                'leave_to' => "required",
                'file' => "sometimes|nullable|mimes:pdf,doc,docx,jpg,jpeg,png,txt",
            ]);
        }

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $fileName = "";
            if ($request->file('file') != "") {
                $maxFileSize = SmGeneralSettings::first('file_size')->file_size;
                $file = $request->file('file');
                $fileSize =  filesize($file);
                $fileSizeKb = ($fileSize / 1000000);
                if($fileSizeKb >= $maxFileSize){
                    Toastr::error( 'Max upload file size '. $maxFileSize .' Mb is set in system', 'Failed');
                    return redirect()->back();
                }
                $apply_leave = SmLeaveRequest::find($request->id);
                if (file_exists($apply_leave->file)) unlink($apply_leave->file);
                $file = $request->file('file');
                $fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                $file->move('public/uploads/leave_request/', $fileName);
                $fileName = 'public/uploads/leave_request/' . $fileName;
            }

            $user = Auth()->user();
            $apply_leave = SmLeaveRequest::find($request->id);
            $apply_leave->staff_id = $request->student_id;
            $apply_leave->role_id = 2;
            $apply_leave->apply_date = date('Y-m-d', strtotime($request->apply_date));
            $apply_leave->leave_define_id = $request->leave_type;
            $apply_leave->leave_from = date('Y-m-d', strtotime($request->leave_from));
            $apply_leave->leave_to = date('Y-m-d', strtotime($request->leave_to));
            $apply_leave->approve_status = 'P';
            $apply_leave->reason = $request->reason;
            if ($fileName != "") {
                $apply_leave->file = $fileName;
            }
            $result = $apply_leave->save();
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Leave Request has been updated successfully');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again.');
                }
            } else {
                if ($result) {
                    Toastr::success('Operation successful', 'Success');
                    return redirect('parent-apply-leave');
                } else {
                    Toastr::error('Operation Failed', 'Failed');
                    return redirect()->back();
                }
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    function DeleteLeave(Request $request, $id)
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        try {
            $apply_leave = SmLeaveRequest::find($id);
            if ($apply_leave->file != "") {
                if (file_exists($apply_leave->file)) unlink($apply_leave->file);
            }
            $result = $apply_leave->delete();
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Request has been deleted successfully');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again.');
                }
            } else {
                if ($result) {
                    Toastr::success('Operation successful', 'Success');
                    return redirect('parent-apply-leave');
                } else {
                    Toastr::error('Operation Failed', 'Failed');
                    return redirect()->back();
                }
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function classRoutine($id)
    {
        try {
            $student_detail = SmStudent::where('id', $id)->first();
            
            $class_id = $student_detail->class_id;
            $section_id = $student_detail->section_id;
            $sm_weekends = SmWeekend::orderBy('order', 'ASC')->where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $class_times = SmClassTime::where('type', 'class')->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            // return $class_times;
            return view('backEnd.parentPanel.class_routine', compact('class_times', 'class_id', 'section_id', 'sm_weekends', 'student_detail'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function attendance($id)
    {
        try {
            $student_detail = SmStudent::where('id', $id)->first();
            $academic_years = SmAcademicYear::where('active_status', 1)->where('school_id', Auth::user()->school_id)->get();
            return view('backEnd.parentPanel.attendance', compact('student_detail', 'academic_years'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function attendanceSearch(Request $request)
    {
        $request->validate([
            'month' => 'required',
            'year' => 'required'
        ]);

        try {
            $student_detail = SmStudent::where('id', $request->student_id)->first();
            $year = $request->year;
            $month = $request->month;
            $current_day = date('d');
            $days = cal_days_in_month(CAL_GREGORIAN, $request->month, $request->year);
            //$students = SmStudent::where('class_id', $request->class)->where('section_id', $request->section)->where('school_id',Auth::user()->school_id)->get();
            // $academic_years = SmAcademicYear::where('active_status', 1)->where('school_id', Auth::user()->school_id)->get();
            // $attendances = SmStudentAttendance::/* where('student_id', $student_detail->id)->where('attendance_date', 'LIKE', '%'.$request->year . '-' . $request->month . '%')-> */get();
            
            $attendances = SmStudentAttendance::where('student_id', $student_detail->id)->where('academic_id', getAcademicId())->where('attendance_date', 'like', $request->year . '-' . $request->month . '%')->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            $academic_years = SmAcademicYear::where('active_status', '=', 1)->where('school_id',Auth::user()->school_id)->get();
            // dd($attendances);
            return view('backEnd.parentPanel.attendance', compact('attendances', 'days', 'year', 'month', 'current_day', 'student_detail', 'academic_years'));
        } catch (\Exception $e) {
            // dd($e);
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function attendancePrint($student_id, $month, $year)
    {
        try {
            $student_detail = SmStudent::where('id', $student_id)->first();
            $current_day = date('d');
            $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            //$students = SmStudent::where('class_id', $request->class)->where('section_id', $request->section)->where('school_id',Auth::user()->school_id)->get();
            $attendances = SmStudentAttendance::where('student_id', $student_detail->id)->where('attendance_date', 'like', $year . '-' . $month . '%')->where('school_id', Auth::user()->school_id)->get();
            $customPaper = array(0, 0, 700.00, 1000.80);
            $pdf = PDF::loadView(
                'backEnd.parentPanel.attendance_print',
                [
                    'attendances' => $attendances,
                    'days' => $days,
                    'year' => $year,
                    'month' => $month,
                    'current_day' => $current_day,
                    'student_detail' => $student_detail
                ]
            )->setPaper('A4', 'landscape');
            return $pdf->stream('my_child_attendance.pdf');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function examinationSchedule($id)
    {
        try {
            // return $id;
            $user = Auth::user();
            $parent = SmParent::where('user_id', $user->id)->first();
            $student_detail = SmStudent::where('id', $id)->first();
            $student_id = $student_detail->id;
            // return $student_detail;
            $exam_types = SmExamType::where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();

            return view('backEnd.parentPanel.parent_exam_schedule', compact('exam_types', 'student_id'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function parentBookList()
    {

        try {
            $books = SmBook::where('active_status', 1)
                ->orderBy('id', 'DESC')
                ->where('school_id', Auth::user()->school_id)->get();
            return view('backEnd.parentPanel.parentBookList', compact('books'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function parentBookIssue()
    {
        try {
            $user = Auth::user();
            $parent_detail = SmParent::where('user_id', $user->id)->first();

            $library_member = SmLibraryMember::where('member_type', 3)->where('student_staff_id', $parent_detail->user_id)->first();
            // return $library_member;
            if (empty($library_member)) {
                Toastr::error('You are not library member ! Please contact with librarian', 'Failed');
                return redirect()->back();
            }
            $issueBooks = SmBookIssue::where('member_id', $library_member->student_staff_id)
            ->leftjoin('sm_books','sm_books.id','sm_book_issues.book_id')
            ->leftjoin('library_subjects','library_subjects.id','sm_books.subject_id')
            ->where('sm_book_issues.issue_status', 'I')->where('sm_book_issues.school_id', Auth::user()->school_id)->get();

            return view('backEnd.parentPanel.parentBookIssue', compact('issueBooks'));
        } catch (\Exception $e) {
            // dd($e);
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function examinationScheduleSearch(Request $request)
    {
        // return $request;
        $request->validate([
            'exam' => 'required',
        ]);
        try {
            $user = Auth::user();
            $parent = SmParent::where('user_id', $user->id)->first();
            $student_detail = SmStudent::where('id', $request->student_id)->first();
            $student_id = $student_detail->id;
            $assign_subjects = SmAssignSubject::where('class_id', $student_detail->class_id)->where('section_id', $student_detail->section_id)
                ->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();

            // return  $assign_subjects;
            if ($assign_subjects->count() == 0) {
                Toastr::error('No Subject Assigned. Please assign subjects in this class.', 'Failed');
                return redirect()->back();
                // return redirect()->back()->with('message-danger', 'No Subject Assigned. Please assign subjects in this class.');
            }

            $exams = SmExam::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $class_id = $student_detail->class_id;
            $section_id = $student_detail->section_id;
            $exam_id = $request->exam;

            $exam_types = SmExamType::where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $exam_periods = SmClassTime::where('type', 'exam')->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $exam_schedule_subjects = "";
            $assign_subject_check = "";

            return view('backEnd.parentPanel.parent_exam_schedule', compact('exams', 'assign_subjects', 'class_id', 'section_id', 'exam_id', 'exam_schedule_subjects', 'assign_subject_check', 'exam_types', 'exam_periods', 'student_id'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function examination($id)
    {
        try {
            $student_detail = SmStudent::where('id', $id)->first();
            $exams = SmExamSchedule::where('class_id', $student_detail->class_id)->where('section_id', $student_detail->section_id)->where('school_id', Auth::user()->school_id)->get();
            $grades = SmMarksGrade::where('active_status', 1)->where('school_id', Auth::user()->school_id)->get();
            $exam_terms = SmExamType::where('school_id', Auth::user()->school_id)->where('academic_id', getAcademicId())->get();

            return view('backEnd.parentPanel.student_result', compact('student_detail', 'exams', 'grades','exam_terms'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function subjects($id)
    {
        try {
            $student_detail = SmStudent::where('id', $id)->first();
            $assignSubjects = SmAssignSubject::where('class_id', $student_detail->class_id)->where('section_id', $student_detail->section_id)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            return view('backEnd.parentPanel.subject', compact('assignSubjects', 'student_detail'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function teacherList($id)
    {
        try {
            $student_detail = SmStudent::where('id', $id)->first();
            $teachers = SmAssignSubject::where('class_id', $student_detail->class_id)->where('section_id', $student_detail->section_id)->get()->unique('teacher_id');
            return view('backEnd.parentPanel.teacher_list', compact('teachers', 'student_detail'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function transport($id)
    {
        try {
            $student_detail = SmStudent::where('id', $id)->first();
            $routes = SmAssignVehicle::join('sm_vehicles', 'sm_assign_vehicles.vehicle_id', 'sm_vehicles.id')
            ->join('sm_students', 'sm_vehicles.id', 'sm_students.vechile_id')
            ->join('sm_parents', 'sm_parents.id', 'sm_students.parent_id')
            ->where('sm_assign_vehicles.active_status', 1)
            ->where('sm_parents.user_id', Auth::user()->id)
            ->where('sm_assign_vehicles.school_id',Auth::user()->school_id)
            ->get();
            // return Auth::user()->id;
            // return $routes;
            
            // $routes = SmAssignVehicle::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            return view('backEnd.parentPanel.transport', compact('routes', 'student_detail'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function dormitory($id)
    {
        try {
            $student_detail = SmStudent::where('id', $id)->first();
            // return $student_detail;
            $room_lists = SmRoomList::where('active_status', 1)->where('id', $student_detail->room_id)->where('school_id', Auth::user()->school_id)->get();
            $room_lists = $room_lists->groupBy('dormitory_id');
            $room_types = SmRoomType::where('active_status', 1)->where('school_id', Auth::user()->school_id)->get();
            $dormitory_lists = SmDormitoryList::where('active_status', 1)->where('id', $student_detail->dormitory_id)->where('school_id', Auth::user()->school_id)->get();
            return view('backEnd.parentPanel.dormitory', compact('room_lists', 'room_types', 'dormitory_lists', 'student_detail'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function homework($id)
    {
        try {
            $student_detail = SmStudent::where('id', $id)->first();
            $homeworkLists = SmHomework::where('class_id', $student_detail->class_id)->where('section_id', $student_detail->section_id)->where('academic_id', getAcademicId())
                ->where('school_id', Auth::user()->school_id)->get();
            return view('backEnd.parentPanel.homework', compact('homeworkLists', 'student_detail'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function homeworkView($class_id, $section_id, $homework_id)
    {
        try {
            $homeworkDetails = SmHomework::where('class_id', '=', $class_id)->where('section_id', '=', $section_id)->where('id', '=', $homework_id)->first();
            return view('backEnd.parentPanel.homeworkView', compact('homeworkDetails', 'homework_id'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function parentNoticeboard()
    {
        try {
            $allNotices = SmNoticeBoard::where('active_status', 1)->where('inform_to', 'LIKE', '%3%')
                ->orderBy('id', 'DESC')
                ->where('academic_id', getAcademicId())
                ->where('school_id', Auth::user()->school_id)->get();

            return view('backEnd.parentPanel.parentNoticeboard', compact('allNotices'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function childListApi(Request $request, $id)
    {
        try {
            $parent = SmParent::where('user_id', $id)->first();
            $student_info = DB::table('sm_students')
                ->join('sm_classes', 'sm_classes.id', '=', 'sm_students.class_id')
                ->join('sm_sections', 'sm_sections.id', '=', 'sm_students.section_id')
                // ->join('sm_exams','sm_exams.id','=','sm_exam_types.id' )
                // ->join('sm_subjects','sm_subjects.id','=','sm_result_stores.subject_id' )

                ->where('sm_students.parent_id', '=', $parent->id)


                ->select('sm_students.user_id', 'student_photo', 'sm_students.full_name as student_name', 'class_name', 'section_name', 'roll_no')

                ->where('sm_students.school_id', Auth::user()->school_id)->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {


                return ApiBaseMethod::sendResponse($student_info, null);
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function childProfileApi(Request $request, $id)
    {
        try {
            $student_detail = SmStudent::where('id', $id)->first();
            $siblings = SmStudent::where('parent_id', $student_detail->parent_id)->where('active_status', 1)->where('school_id', Auth::user()->school_id)->get();
            $fees_assigneds = SmFeesAssign::where('student_id', $student_detail->id)->where('school_id', Auth::user()->school_id)->get();
            $fees_discounts = SmFeesAssignDiscount::where('student_id', $student_detail->id)->where('school_id', Auth::user()->school_id)->get();
            $documents = SmStudentDocument::where('student_staff_id', $student_detail->id)->where('type', 'stu')->where('school_id', Auth::user()->school_id)->get();
            $timelines = SmStudentTimeline::where('staff_student_id', $student_detail->id)->where('type', 'stu')->where('visible_to_student', 1)->where('school_id', Auth::user()->school_id)->get();
            $exams = SmExamSchedule::where('class_id', $student_detail->class_id)->where('section_id', $student_detail->section_id)->where('school_id', Auth::user()->school_id)->get();
            $grades = SmMarksGrade::where('active_status', 1)->where('school_id', Auth::user()->school_id)->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['student_detail'] = $student_detail->toArray();
                $data['fees_assigneds'] = $fees_assigneds->toArray();
                $data['fees_discounts'] = $fees_discounts->toArray();
                $data['exams'] = $exams->toArray();
                $data['documents'] = $documents->toArray();
                $data['timelines'] = $timelines->toArray();
                $data['siblings'] = $siblings->toArray();
                $data['grades'] = $grades->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }

            //return view('backEnd.studentPanel.my_profile', compact('student_detail', 'fees_assigneds', 'fees_discounts', 'exams', 'documents', 'timelines', 'siblings', 'grades'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function collectFeesChildApi(Request $request, $id)
    {
        try {
            $student = SmStudent::where('id', $id)->first();
            $fees_assigneds = SmFeesAssign::where('student_id', $id)->orderBy('id', 'desc')->where('school_id', Auth::user()->school_id)->get();

            $fees_assigneds2 = DB::table('sm_fees_assigns')
                ->select('sm_fees_types.id as fees_type_id', 'sm_fees_types.name', 'sm_fees_masters.date as due_date', 'sm_fees_masters.amount as amount')
                ->join('sm_fees_masters', 'sm_fees_masters.id', '=', 'sm_fees_assigns.fees_master_id')
                ->join('sm_fees_types', 'sm_fees_types.id', '=', 'sm_fees_masters.fees_type_id')
                ->join('sm_fees_payments', 'sm_fees_payments.fees_type_id', '=', 'sm_fees_masters.fees_type_id')
                ->where('sm_fees_assigns.student_id', $student->id)
                //->where('sm_fees_payments.student_id', $student->id)
                ->where('sm_fees_assigns.school_id', Auth::user()->school_id)->get();
            $i = 0;
            return $fees_assigneds2;
            foreach ($fees_assigneds2 as $row) {
                $d[$i]['fees_name'] = $row->name;
                $d[$i]['due_date'] = $row->due_date;
                $d[$i]['amount'] = $row->amount;
                $d[$i]['paid'] = DB::table('sm_fees_payments')->where('fees_type_id', $row->fees_type_id)->sum('amount');
                $d[$i]['fine'] = DB::table('sm_fees_payments')->where('fees_type_id', $row->fees_type_id)->sum('fine');
                $d[$i]['discount_amount'] = DB::table('sm_fees_payments')->where('fees_type_id', $row->fees_type_id)->sum('discount_amount');
                $d[$i]['balance'] = ((float) $d[$i]['amount'] + (float) $d[$i]['fine'])  - ((float) $d[$i]['paid'] + (float) $d[$i]['discount_amount']);
                $i++;
            }

            //dd($fees_assigneds2);
            //, DB::raw("SUM(sm_fees_payments.amount) as total_paid where sm_fees_payments.fees_type_id==")
            $fees_discounts = SmFeesAssignDiscount::where('student_id', $id)->where('school_id', Auth::user()->school_id)->get();

            $applied_discount = [];
            foreach ($fees_discounts as $fees_discount) {
                $fees_payment = SmFeesPayment::select('fees_discount_id')->where('fees_discount_id', $fees_discount->id)->first();
                if (isset($fees_payment->fees_discount_id)) {
                    $applied_discount[] = $fees_payment->fees_discount_id;
                }
            }

            //dd($fees_discounts);


            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                // $data['student'] = $student;
                $data['fees'] = $d;
                return ApiBaseMethod::sendResponse($fees_assigneds2, null);
            }

            return view('backEnd.feesCollection.collect_fees_student_wise', compact('student', 'fees_assigneds', 'fees_discounts', 'applied_discount'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function classRoutineApi(Request $request, $id)
    {
        try {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $user_id = $id;
            } else {
                $user = Auth::user();

                if ($user) {
                    $user_id = $user->id;
                } else {
                    $user_id = $request->user_id;
                }
            }

            $student_detail = SmStudent::where('id', $id)->first();
            //return $student_detail;
            $class_id = $student_detail->class_id;
            $section_id = $student_detail->section_id;

            $sm_weekends = SmWeekend::where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->orderBy('order', 'ASC')->where('active_status', 1)->get();
            $class_times = SmClassTime::where('type', 'class')->where('school_id', Auth::user()->school_id)->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['student_detail'] = $student_detail->toArray();
                // $data['class_id'] = $class_id;
                // $data['section_id'] = $section_id;
                // $data['sm_weekends'] = $sm_weekends->toArray();
                // $data['class_times'] = $class_times->toArray();

                $weekenD = SmWeekend::where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();

                foreach ($weekenD as $row) {
                    $data[$row->name] = DB::table('sm_class_routine_updates')
                        ->select('sm_class_times.period', 'sm_class_times.start_time', 'sm_class_times.end_time', 'sm_subjects.subject_name', 'sm_class_rooms.room_no')
                        ->join('sm_classes', 'sm_classes.id', '=', 'sm_class_routine_updates.class_id')
                        ->join('sm_sections', 'sm_sections.id', '=', 'sm_class_routine_updates.section_id')
                        ->join('sm_class_times', 'sm_class_times.id', '=', 'sm_class_routine_updates.class_period_id')
                        ->join('sm_subjects', 'sm_subjects.id', '=', 'sm_class_routine_updates.subject_id')
                        ->join('sm_class_rooms', 'sm_class_rooms.id', '=', 'sm_class_routine_updates.room_id')

                        ->where([
                            ['sm_class_routine_updates.class_id', $class_id], ['sm_class_routine_updates.section_id', $section_id], ['sm_class_routine_updates.day', $row->id],
                        ])->where('sm_classes.school_id', Auth::user()->school_id)->get();
                }

                return ApiBaseMethod::sendResponse($data, null);
            }

            //return view('backEnd.studentPanel.class_routine', compact('class_times', 'class_id', 'section_id', 'sm_weekends'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function childHomework(Request $request, $id)
    {
        try {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $student_detail = SmStudent::where('id', $id)->first();

                $class_id = $student_detail->class->id;
                $subject_list = SmAssignSubject::where([['class_id', $class_id], ['section_id', $student_detail->section_id]])->where('school_id', Auth::user()->school_id)->get();

                $i = 0;
                foreach ($subject_list as $subject) {
                    $homework_subject_list[$subject->subject->subject_name] = $subject->subject->subject_name;
                    $allList[$subject->subject->subject_name] =
                        DB::table('sm_homeworks')
                        ->select('sm_homeworks.description', 'sm_subjects.subject_name', 'sm_homeworks.homework_date', 'sm_homeworks.submission_date', 'sm_homeworks.evaluation_date', 'sm_homeworks.file', 'sm_homeworks.marks', 'sm_homework_students.complete_status as status')
                        ->leftjoin('sm_homework_students', 'sm_homework_students.homework_id', '=', 'sm_homeworks.id')
                        ->leftjoin('sm_subjects', 'sm_subjects.id', '=', 'sm_homeworks.subject_id')
                        ->where('class_id', $student_detail->class_id)->where('section_id', $student_detail->section_id)->where('subject_id', $subject->subject_id)->where('sm_homeworks.school_id', Auth::user()->school_id)->get();
                }
                //return $h;

                $homeworkLists = SmHomework::where('class_id', $student_detail->class_id)->where('section_id', $student_detail->section_id)->where('school_id', Auth::user()->school_id)->get();
            }
            // dd($allList);
            $data = [];

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                foreach ($allList as $r) {
                    foreach ($r as $s) {
                        $data[] = $s;
                    }
                }
                return ApiBaseMethod::sendResponse($data, null);
            }
            // return view('backEnd.studentPanel.student_homework', compact('homeworkLists', 'student_detail'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function childAttendanceAPI(Request $request, $id)
    {

        $input = $request->all();

        $validator = Validator::make($input, [
            'month' => "required",
            'year' => "required",
        ]);

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            $student_detail = SmStudent::where('id', $id)->first();

            $year = $request->year;
            $month = $request->month;
            if ($month < 10) {
                $month = '0' . $month;
            }
            $current_day = date('d');

            $days = cal_days_in_month(CAL_GREGORIAN, $month, $request->year);
            $days2 = cal_days_in_month(CAL_GREGORIAN, $month - 1, $request->year);
            $previous_month = $month - 1;
            $previous_date = $year . '-' . $previous_month . '-' . $days2;
            $previousMonthDetails['date'] = $previous_date;
            $previousMonthDetails['day'] = $days2;
            $previousMonthDetails['week_name'] = date('D', strtotime($previous_date));
            $attendances = SmStudentAttendance::where('student_id', $student_detail->id)
                ->where('attendance_date', 'like', '%' . $request->year . '-' . $month . '%')
                ->select('attendance_type', 'attendance_date')
                ->where('school_id', Auth::user()->school_id)->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data['attendances'] = $attendances;
                $data['previousMonthDetails'] = $previousMonthDetails;
                $data['days'] = $days;
                $data['year'] = $year;
                $data['month'] = $month;
                $data['current_day'] = $current_day;
                $data['status'] = 'Present: P, Late: L, Absent: A, Holiday: H, Half Day: F';
                return ApiBaseMethod::sendResponse($data, null);
            }
            //Test
            //return view('backEnd.studentPanel.student_attendance', compact('attendances', 'days', 'year', 'month', 'current_day'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function aboutApi(request $request)
    {
        try {
            $about = DB::table('sm_general_settings')
                ->join('sm_languages', 'sm_general_settings.language_id', '=', 'sm_languages.id')
                ->join('sm_academic_years', 'sm_general_settings.session_id', '=', 'sm_academic_years.id')
                ->join('sm_about_pages', 'sm_general_settings.school_id', '=', 'sm_about_pages.school_id')
                ->select('main_description', 'school_name', 'site_title', 'school_code', 'address', 'phone', 'email', 'logo', 'sm_languages.language_name', 'year as session', 'copyright_text')
                ->where('sm_general_settings.school_id', Auth::user()->school_id)->first();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {

                return ApiBaseMethod::sendResponse($about, null);
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    function StudentDownload($file_name)
    {
        try {
            $file = public_path() . '/uploads/student/timeline/' . $file_name;
            if (file_exists($file)) {
                return Response::download($file);
            }
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}