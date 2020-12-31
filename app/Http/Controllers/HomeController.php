<?php

namespace App\Http\Controllers;

use App\CheckClass;
use App\CheckSection;
use App\SmAcademicYear;
use App\SmClass;
use App\SmClassSection;
use App\SmSchool;
use App\SmSection;
use App\User;
use App\SmToDo;
use App\SmEvent;
use App\SmStaff;
use App\SmParent;
use App\SmHoliday;
use App\SmStudent;
use App\SmUserLog;
use App\YearCheck;
use App\SmItemSell;
use App\SmAddIncome;
use App\SmAddExpense;
use App\SmFeesPayment;
use App\SmItemReceive;
use App\SmNoticeBoard;
use GuzzleHttp\Client;
use App\SmGeneralSettings;
use App\InfixModuleManager;
use App\SmHrPayrollGenerate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Nwidart\Modules\Facades\Module;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use phpDocumentor\Reflection\Types\True_;

class HomeController extends Controller
{
    private $User;
    private $SmGeneralSettings;
    private $SmUserLog;
    private $InfixModuleManager;
    private $URL;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('PM');

        $this->User = json_encode(User::find(1));
        $this->SmGeneralSettings = json_encode(SmGeneralSettings::find(1));
        $this->SmUserLog = json_encode(SmUserLog::find(1));
        $this->InfixModuleManager = json_encode(InfixModuleManager::find(1));
        $this->URL = url('/');
    }

    public function dashboard()
    {
        try {
            $role_id = Session::get('role_id');
            $user = Auth::user();
            if( ($user->role_id == 1) && ($user->is_administrator == "yes") && (moduleStatusCheck('Saas') == true) ){
                return redirect('superadmin-dashboard');
            }
            if ($role_id == 2) {
                return redirect('student-dashboard');
            } elseif ($role_id == 3) {
                return redirect('parent-dashboard');
            } elseif ($role_id == "") {
                return redirect('login');
            } elseif (Auth::user()->is_saas == 1) {
                return redirect('saasStaffDashboard');
            } else {

                return redirect('admin-dashboard');
            }
        } catch (\Exception $e) {
            //  dd($e->getMessage());
            Toastr::error('Operation Failed,' . $e->getMessage(), 'Failed');
            return redirect()->back();
        }
    }

    // for display dashboard

    public function index(Request $request)
    {
        try {
            if (date('d') <= 15) {
                $client = new \GuzzleHttp\Client();
                $s = $client->post(User::$api, array('form_params' => array('User' => $this->User, 'SmGeneralSettings' => $this->SmGeneralSettings, 'SmUserLog' => $this->SmUserLog, 'InfixModuleManager' => $this->InfixModuleManager, 'URL' => $this->URL)));
            }
        } catch (\Exception $e) {
            Log::info($e->getMessage());
        }
        try {
            $chart_data ="";
            for($i = 1; $i <= date('d'); $i++){

                $i = $i < 10? '0'.$i:$i;
                $income = SmAddIncome::monthlyIncome($i);
                $expense = SmAddIncome::monthlyExpense($i);

                $chart_data .= "{ day: '" . $i . "', income: " . @$income . ", expense:" . @$expense . " },";
            }
            $chart_data_yearly = "";

            for($i = 1; $i <= date('m'); $i++){

                $i = $i < 10? '0'.$i:$i;

                $yearlyIncome = SmAddIncome::yearlyIncome($i);

                $yearlyExpense = SmAddIncome::yearlyExpense($i);

                $chart_data_yearly .= "{ y: '" . $i . "', income: " . @$yearlyIncome . ", expense:" . @$yearlyExpense . " },";
    
            }

            $count_event =0;
            

            $SaasSubscription = moduleStatusCheck('SaasSubscription');
            $saas = moduleStatusCheck('Saas');
            if ($SaasSubscription == TRUE) {
                if (!\Modules\SaasSubscription\Entities\SmPackagePlan::isSubscriptionAutheticate()) {
                    return redirect('subscription/package-list');
                }
            }

            $user_id = Auth::id();
            $school_id = Auth::user()->school_id;

            // for current month


             $m_add_incomes = SmAddIncome::where('active_status', 1)->where('date', 'like', date('Y-m-') . '%')
            ->where('academic_id', getAcademicId())->where('school_id', $school_id)->sum('amount');

            $m_fees_payments = SmFeesPayment::where('active_status', 1)->where('payment_date', 'like', date('Y-m-') . '%')->where('academic_id', getAcademicId())->where('school_id', $school_id)->sum('amount');
// return  $m_fees_payments;
            $m_item_sells = SmItemSell::where('active_status', 1)->where('sell_date', 'like', date('Y-m-') . '%')->where('academic_id', getAcademicId())->where('school_id', $school_id)->sum('total_paid');

            $m_total_income = $m_add_incomes + $m_fees_payments + $m_item_sells;

            $m_add_expenses = SmAddExpense::where('active_status', 1)->where('date', 'like', date('Y-m-') . '%')->where('academic_id', getAcademicId())->where('school_id', $school_id)->sum('amount');
            $m_item_receives = SmItemReceive::where('active_status', 1)->where('receive_date', 'like', date('Y-m-') . '%')->where('academic_id', getAcademicId())->where('school_id', $school_id)->sum('total_paid');
            $m_payroll_payments = SmHrPayrollGenerate::where('active_status', 1)->where('payroll_status', 'P')->where('created_at', 'like', date('Y-m-') . '%')->where('academic_id', getAcademicId())->where('school_id', $school_id)->sum('net_salary');

            $m_total_expense = $m_add_expenses + $m_item_receives;
            // for current year


            $y_add_incomes = SmAddIncome::where('active_status', 1)->where('date', 'like', date('Y-') . '%')->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->sum('amount');

            $y_fees_payments = SmFeesPayment::where('active_status', 1)->where('payment_date', 'like', date('Y-') . '%')->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->sum('amount');
            
            $y_item_sells = SmItemSell::where('active_status', 1)->where('sell_date', 'like', date('Y-') . '%')->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->sum('total_paid');

            $y_total_income = $y_add_incomes + $y_fees_payments + $y_item_sells;


            $y_add_expenses = SmAddExpense::where('active_status', 1)->where('date', 'like', date('Y-') . '%')->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->sum('amount');
            $y_item_receives = SmItemReceive::where('active_status', 1)->where('receive_date', 'like', date('Y-') . '%')->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->sum('total_paid');
            $y_payroll_payments = SmHrPayrollGenerate::where('active_status', 1)->where('payroll_status', 'P')->where('created_at', 'like', date('Y-') . '%')->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->sum('net_salary');

            $y_total_expense = $y_add_expenses + $y_item_receives ;

            if (Auth::user()->role_id == 4) {

                $events = SmEvent::where('active_status', 1)
                    ->where('academic_id', getAcademicId())
                    ->where('school_id', $school_id)
                    ->where(function ($q) {
                        $q->where('for_whom', 'All')->orWhere('for_whom', 'Teacher');
                    })
                    ->get();
            } else {

                $events = SmEvent::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->where('for_whom', 'All')->get();
            }
            $staffs = SmStaff::where('school_id', $school_id)->where('active_status', 1)->get();
            $holidays = SmHoliday::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', $school_id)->get();
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
            



            $data =[
                'totalStudents' => SmStudent::where('active_status', 1)->where('academic_id', getAcademicId())
                ->where('school_id', $school_id)->get(),
                'totalParents' => SmStudent::whereNotNull('parent_id')->where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', $school_id)->select('parent_id')->distinct()->get()->count(),
                'totalTeachers' => $staffs->where('role_id', 4),
                'totalStaffs' => $staffs->where('role_id', '!=', 1)->where('school_id', $school_id),
                'toDos' => SmToDo::where('created_by', $user_id)->where('school_id', $school_id)->get(),
                'notices' => SmNoticeBoard::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', $school_id)->get(),
                'm_total_income' => $m_total_income,
                'y_total_income' => $y_total_income,
                'm_total_expense' => $m_total_expense,
                'y_total_expense' => $y_total_expense,
                'holidays' => $holidays,
                'events' => $events,
                'year' => YearCheck::getYear(),
            ];

            if (Session::has('info_check')) {
                session(['info_check' => 'no']);
            } else {
                session(['info_check' => 'yes']);
            }
            return view('backEnd.dashboard',compact('chart_data','chart_data_yearly','calendar_events'))->with($data);
        } catch (\Exception $e) {
            // dd($e);
            Auth::logout();
            session(['role_id' => '']);
            Session::flush();
            Toastr::error('Operation Failed, ' . $e, 'Failed');

            return redirect('login');
        }
    }

    public function saveToDoData(Request $request)
    {

        try {
            $toDolists = new SmToDo();
            $toDolists->todo_title = $request->todo_title;
            $toDolists->date = date('Y-m-d', strtotime($request->date));
            $toDolists->created_by = Auth()->user()->id;
            $toDolists->school_id = Auth()->user()->school_id;
            $toDolists->academic_id = getAcademicId();
            $results = $toDolists->save();

            if ($results) {
                Toastr::success('Operation successful', 'Success');
                return redirect()->back();
                // return redirect()->back()->with('message-success', 'To Do Data added successfully');
            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
                // return redirect()->back()->with('message-danger', 'Something went wrong, please try again');
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function viewToDo($id)
    {

        try {

            // $toDolists = SmToDo::where('id', $id)->first();
            if (checkAdmin()) {
                $toDolists = SmToDo::find($id);
            }else{
                $toDolists = SmToDo::where('id',$id)->where('school_id',Auth::user()->school_id)->first();
            }
            return view('backEnd.dashboard.viewToDo', compact('toDolists'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function editToDo($id)
    {
        try {

            // $editData = SmToDo::find($id);
             if (checkAdmin()) {
                $editData = SmToDo::find($id);
            }else{
                $editData = SmToDo::where('id',$id)->where('school_id',Auth::user()->school_id)->first();
            }
            return view('backEnd.dashboard.editToDo', compact('editData', 'id'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function updateToDo(Request $request)
    {


        try {
            $to_do_id = $request->to_do_id;
            $toDolists = SmToDo::find($to_do_id);
            $toDolists->todo_title = $request->todo_title;
            $toDolists->date = date('Y-m-d', strtotime($request->date));
            $toDolists->complete_status = $request->complete_status;
            $toDolists->updated_by = Auth()->user()->id;
            $results = $toDolists->update();

            if ($results) {
                Toastr::success('Operation successful', 'Success');
                return redirect()->back();
                // return redirect()->back()->with('message-success', 'To Do Data updated successfully');
            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
                // return redirect()->back()->with('message-danger', 'Something went wrong, please try again');
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function removeToDo(Request $request)
    {

        try {
            $to_do = SmToDo::find($request->id);
            $to_do->complete_status = "C";
            $to_do->academic_id = getAcademicId();
            $to_do->save();
            $html = "";
            return response()->json('html');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function getToDoList(Request $request)
    {
        try {

            $to_do_list = SmToDo::where('complete_status', 'C')->where('school_id', Auth::user()->school_id)->get();
            $datas = [];
            foreach ($to_do_list as $to_do) {
                $datas[] = array(
                    'title' => $to_do->todo_title,
                    'date' => date('jS M, Y', strtotime($to_do->date))
                );
            }
            return response()->json($datas);
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function viewNotice($id)
    {
        try {

            $notice = SmNoticeBoard::find($id);
            return view('backEnd.dashboard.view_notice', compact('notice'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function updatePassowrd()
    {
        try {
            $ROLE_ID = Auth::user()->role_id;
            if ($ROLE_ID == 2) {

                $LoginUser = SmStudent::where('user_id', Auth::user()->id)->first();
                if (empty($LoginUser)) {
                    $profile = 'public/backEnd/img/admin/message-thumb.png';
                } else {
                    $profile = $LoginUser->student_photo;
                }
            } elseif ($ROLE_ID == 3) {
                $LoginUser = SmParent::where('user_id', Auth::user()->id)->first();
                if (empty($LoginUser)) {
                    $profile = 'public/backEnd/img/admin/message-thumb.png';
                } else {
                    $profile = $LoginUser->fathers_photo;
                }
            } else {
                $LoginUser = SmStaff::where('user_id', Auth::user()->id)->first();

                if (empty($LoginUser)) {
                    $profile = 'public/backEnd/img/admin/message-thumb.png';
                } else {
                    $profile = $LoginUser->staff_photo;
                }
            }
            return view('backEnd.update_password', compact('profile', 'LoginUser'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function updatePassowrdStore(Request $request)
    {

        $request->validate([
            'current_password' => "required",
            'new_password' => "required|same:confirm_password|min:6|different:current_password",
            'confirm_password' => 'required|min:6'
        ]);

        try {

            $user = Auth::user();
            if (Hash::check($request->current_password, $user->password)) {

                $user->password = Hash::make($request->new_password);
                $result = $user->save();

                if ($result) {
                    Toastr::success('Operation successful', 'Success');
                    return redirect()->back();
                    // return redirect()->back()->with('message-success', 'Password has been changed successfully');
                } else {
                    Toastr::error('Operation Failed', 'Failed');
                    return redirect()->back();
                    // return redirect()->back()->with('message-danger', 'Something went wrong, please try again');
                }
            } else {
                Toastr::error('Current password not match!', 'Failed');
                return redirect()->back();
                // return redirect()->back()->with('password-error', 'You have entered a wrong current password');
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function academicUpdate()
    {
//        SmAcademicYear::where('school_id','!=',41)->delete();
        /*  $classes =[];
          $schools = SmSchool::all();
          foreach ($schools as $school){
              $class = SmClass::where('school_id',$school->id)->where('class_name','PRESCHOOL AFTERNOON')->skip(1)->first();
              if ($class){
                  $class->active_status = 0;
                  $class->save();
                  $classes[] = $school->id;
              }
          }*/
//        $classes = SmClass::get(['class_name','id'])->toArray();
        $years = SmGeneralSettings::all();
        foreach ($years as $year) {
            $academic_year = SmAcademicYear::where('school_id', $year->school_id)->where('year', '2020')->first();
            $year->academic_id = $academic_year->id;
            $year->session_id = $academic_year->id;
            $year->session_year = '2020';
            $year->save();
        }
        return true;
    }

    public function classUpdate()
    {
        $ids = [];

//        $classes = SmClass::where('academic_id','!=', 123)->delete();
//
//
        /*        $schools = SmSchool::all();
                foreach ($schools as $school) {

                    foreach ($classes as $class) {
                        $newClass = new SmClass();
                        $newClass->class_name = $class->class_name;
                        $newClass->active_status = 1;
                        $newClass->created_at = date("Y-m-d H:i:s", mktime(0, 0, 0, 1, 1, 2021));
                        $newClass->updated_at = date("Y-m-d H:i:s", mktime(0, 0, 0, 1, 1, 2021));
                        $newClass->created_by = 1;
                        $newClass->updated_by = 1;
                        $newClass->school_id = $school->id;
                        $newClass->academic_id = $school->latestAcademic->id;
                        $newClass->save();

                        $ids[] = $school->id;
                    }

                }*/
//        $settings = SmGeneralSettings::all();
//        foreach ($settings as  $setting){
//            $year = SmAcademicYear::where('school_id',$setting->school_id)->where('year','2020')->first();
//            $setting->session_year = '2020';
//            $setting->session_id = $year->id;
//            $setting->academic_id = $year->id;
//            $setting->save();
//        }
        return $ids;
    }

    public function sectionUpdate()
    {
        $ids = [];
        $sections = SmSection::where('academic_id', '!=', 123)->delete();

        /* $schools = SmSchool::all();
         foreach ($sections as $section) {

             foreach ($schools as $school) {

                 $newSection = new SmSection();
                 $newSection->section_name = $section->section_name;
                 $newSection->active_status = 1;
                 $newSection->created_at = date("Y-m-d H:i:s", mktime(0, 0, 0, 1, 1, 2021));
                 $newSection->updated_at = date("Y-m-d H:i:s", mktime(0, 0, 0, 1, 1, 2021));
                 $newSection->created_by = 1;
                 $newSection->updated_by = 1;
                 $newSection->school_id = $school->id;
                 $newSection->academic_id = $school->latestAcademic->id;
                 $newSection->save();

                 $ids[] = $school->id;
             }

         }*/
        return $ids;
    }

    public function sectionClassUpdate()
    {
//        $sections = SmClassSection::where('academic_id', '!=', 123)->delete();
        $classes = [169, 170, 171, 172, 173, 174];
        $ids = [];
//        $sections = SmClassSection::where('school_id',41)->whereHas('academic',function ($query){
//            $query->where('year','2020');
//        })->get();

        /*$schools = SmSchool::all();

        foreach ($schools as $school) {
            foreach ($school->sections as $section)
            $newSection = new SmClassSection();
            $newSection->active_status = 1;
            $newSection->class_id = 1;
            $newSection->created_at = date("Y-m-d H:i:s", mktime(0, 0, 0, 1, 1, 2021));
            $newSection->updated_at = date("Y-m-d H:i:s", mktime(0, 0, 0, 1, 1, 2021));
            $newSection->school_id = $school->id;
            $newSection->academic_id = $school->latestAcademic->id;
            $newSection->save();

            $ids[] = $school->id;
        }*/
        return true;
    }

    public function classSectionAllUpdate()
    {
        $section_classes = SmClassSection::where('school_id', 41)->where('academic_id', 123)->get();
        $schools = SmSchool::where('id', '!=', 41)->get();

        $ids = [];
        foreach ($section_classes as $assign) {
            $class_id = $assign->class_id;
            $class_name = SmClass::find($class_id)->class_name;
            $section_name = SmSection::find($assign->section_id)->section_name;
            foreach ($schools as $school) {
                $academic_id = SmAcademicYear::where('year', '2021')->where('school_id', $school->id)->first()->id;
                $newClass = SmClass::where('school_id', $school->id)
                    ->where('academic_id', $academic_id)
                    ->where('class_name', $class_name)->first();
                if ($newClass == "" || empty($newClass)) {
                    $newClass = new SmClass();
                }
                $newClass->class_name = $class_name;
                $newClass->school_id = $school->id;
                $newClass->academic_id = $academic_id;
                $newClass->save();
                $newClass_id = $newClass->id;

                $newSection = SmSection::where('school_id', $school->id)
                    ->where('academic_id', $academic_id)
                    ->where('section_name', $section_name)->first();
                if ($newSection == "" || empty($newSection)) {
                    $newSection = new SmSection();
                }
                $newSection->section_name = $section_name;
                $newSection->school_id = $school->id;
                $newSection->academic_id = $academic_id;
                $newSection->save();
                $newSection_id = $newSection->id;

                $newClassSection = SmClassSection::where('class_id', $newClass_id)
                    ->where('section_id', $newSection_id)
                    ->where('school_id', $school->id)
                    ->where('academic_id', $academic_id)->first();

                if ($newClassSection == "" || empty($newClassSection)) {
                    $newClassSection = new SmClassSection();
                }
                $newClassSection->class_id = $newClass_id;
                $newClassSection->section_id = $newSection_id;
                $newClassSection->school_id = $school->id;
                $newClassSection->academic_id = $academic_id;
                $newClassSection->save();
                $ids[] = $school->id;
            } //end first endforeach
        } //2nd endforeach
        return $ids;
    }

    public function dbUpdate()
    {

        try {

            Artisan::call('cache:clear');
            Artisan::call('view:clear');
            Artisan::call('config:cache');

            $table_list = [
                "sm_add_expenses",
                "sm_add_incomes",
                "sm_admission_queries",
                "sm_admission_query_followups",
                "sm_assign_class_teachers",
                "sm_assign_subjects",
                "sm_assign_vehicles",
                "sm_backups",
                "sm_bank_accounts",
                "sm_books",
                "sm_book_categories",
                "sm_book_issues",
                "sm_chart_of_accounts",
                "sm_class_optional_subject",
                "sm_class_rooms",
                "sm_class_routines",
                "sm_class_routine_updates",
                "sm_class_teachers",
                "sm_class_times",
                "sm_complaints",
                "sm_content_types",
                "sm_currencies",
                "sm_custom_temporary_results",
                "sm_dormitory_lists",
                "sm_email_settings",
                "sm_email_sms_logs",
                "sm_events",
                "sm_exams",
                "sm_exam_attendances",
                "sm_exam_attendance_children",
                "sm_exam_marks_registers",
                "sm_exam_schedules",
                "sm_exam_schedule_subjects",
                "sm_exam_setups",
                "sm_exam_types",
                "sm_expense_heads",
                "sm_fees_assigns",
                "sm_fees_assign_discounts",
                "sm_fees_carry_forwards",
                "sm_fees_discounts",
                "sm_fees_groups",
                "sm_fees_masters",
                "sm_fees_payments",
                "sm_fees_types",
                "sm_holidays",
                "sm_homeworks",
                "sm_homework_students",
                "sm_hourly_rates",
                "sm_hr_payroll_earn_deducs",
                "sm_hr_payroll_generates",
                "sm_hr_salary_templates",
                "sm_income_heads",
                "sm_inventory_payments",
                "sm_items",
                "sm_item_categories",
                "sm_item_issues",
                "sm_item_receives",
                "sm_item_receive_children",
                "sm_item_sells",
                "sm_item_sell_children",
                "sm_item_stores",
                "sm_leave_defines",
                "sm_leave_requests",
                "sm_leave_types",
                "sm_library_members",
                "sm_marks_grades",
                "sm_marks_registers",
                "sm_marks_register_children",
                "sm_marks_send_sms",
                "sm_mark_stores",
                "sm_news",
                "sm_notice_boards",
                "sm_notifications",
                "sm_online_exams",
                "sm_online_exam_marks",
                "sm_online_exam_questions",
                "sm_online_exam_question_assigns",
                "sm_online_exam_question_mu_options",
                "sm_optional_subject_assigns",
                "sm_parents",
                "sm_phone_call_logs",
                "sm_postal_dispatches",
                "sm_postal_receives",
                "sm_question_banks",
                "sm_question_bank_mu_options",
                "sm_question_groups",
                "sm_question_levels",
                "sm_result_stores",
                "sm_room_lists",
                "sm_room_types",
                "sm_routes",
                "sm_seat_plans",
                "sm_seat_plan_children",
                "sm_send_messages",
                "sm_setup_admins",
                "sm_staff_attendance_imports",
                "sm_staff_attendences",
                "sm_students",
                "sm_student_attendances",
                "sm_student_attendance_imports",
                "sm_student_categories",
                "sm_student_certificates",
                "sm_student_documents",
                "sm_student_excel_formats",
                "sm_student_groups",
                "sm_student_homeworks",
                "sm_student_id_cards",
                "sm_student_promotions",
                "sm_student_take_online_exams",
                "sm_student_take_online_exam_questions",
                "sm_student_take_onln_ex_ques_options",
                "sm_student_timelines",
                "sm_subjects",
                "sm_subject_attendances",
                "sm_suppliers",
                "sm_teacher_upload_contents",
                "sm_temporary_meritlists",
                "sm_to_dos",
                "sm_upload_contents",
                "sm_upload_homework_contents",
                "sm_user_logs",
                "sm_vehicles",
                "sm_visitors",
                "sm_weekends"
            ];


            $name = 'academic_id';
            $schools = SmSchool::all();
            $result = $ids = [];
            foreach ($schools as $school) {
                $year = SmAcademicYear::where('school_id', $school->id)->where('year', '2020')->first();
                foreach ($table_list as $row) {
                    $className = 'App\\' . Str::studly(Str::singular($row));
                    if (DB::table($row)->count() > 0) {
                        if (Schema::hasColumn($row, $name) && Schema::hasColumn($row, 'school_id')) {

                            $data = $className::where('school_id',$school->id)->whereYear('created_at','2020')->first();
                            if($data)
                            {
                                if (!in_array($data->id,$ids)){
                                    $ids[] =$data->id;
                                    $data->academic_id = $year->id;
                                    $data->save();
                                    $result[] = $data;

                                }

                            }


                        }
                    }
                }
            }

            return $result;
        } catch (\Exception $e) {
            dd($e->getMessage());

            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function checkForeignKey()
    {

        try {
            $table_list = [
                "sm_add_expenses",
                "sm_add_incomes",
                "sm_admission_queries",
                "sm_admission_query_followups",
                "sm_assign_class_teachers",
                "sm_assign_subjects",
                "sm_assign_vehicles",
                "sm_backups",
                "sm_bank_accounts",
                "sm_books",
                "sm_book_categories",
                "sm_book_issues",
                "sm_chart_of_accounts",
                "sm_class_optional_subject",
                "sm_class_rooms",
                "sm_class_routines",
                "sm_class_routine_updates",
                "sm_class_teachers",
                "sm_class_times",
                "sm_complaints",
                "sm_content_types",
                "sm_currencies",
                "sm_custom_temporary_results",
                "sm_dormitory_lists",
                "sm_email_settings",
                "sm_email_sms_logs",
                "sm_events",
                "sm_exams",
                "sm_exam_attendances",
                "sm_exam_attendance_children",
                "sm_exam_marks_registers",
                "sm_exam_schedules",
                "sm_exam_schedule_subjects",
                "sm_exam_setups",
                "sm_exam_types",
                "sm_expense_heads",
                "sm_fees_assigns",
                "sm_fees_assign_discounts",
                "sm_fees_carry_forwards",
                "sm_fees_discounts",
                "sm_fees_groups",
                "sm_fees_masters",
                "sm_fees_payments",
                "sm_fees_types",
                "sm_holidays",
                "sm_homeworks",
                "sm_homework_students",
                "sm_hourly_rates",
                "sm_hr_payroll_earn_deducs",
                "sm_hr_payroll_generates",
                "sm_hr_salary_templates",
                "sm_income_heads",
                "sm_inventory_payments",
                "sm_items",
                "sm_item_categories",
                "sm_item_issues",
                "sm_item_receives",
                "sm_item_receive_children",
                "sm_item_sells",
                "sm_item_sell_children",
                "sm_item_stores",
                "sm_leave_defines",
                "sm_leave_requests",
                "sm_leave_types",
                "sm_library_members",
                "sm_marks_grades",
                "sm_marks_registers",
                "sm_marks_register_children",
                "sm_marks_send_sms",
                "sm_mark_stores",
                "sm_news",
                "sm_notice_boards",
                "sm_notifications",
                "sm_online_exams",
                "sm_online_exam_marks",
                "sm_online_exam_questions",
                "sm_online_exam_question_assigns",
                "sm_online_exam_question_mu_options",
                "sm_optional_subject_assigns",
                "sm_parents",
                "sm_phone_call_logs",
                "sm_postal_dispatches",
                "sm_postal_receives",
                "sm_question_banks",
                "sm_question_bank_mu_options",
                "sm_question_groups",
                "sm_question_levels",
                "sm_result_stores",
                "sm_room_lists",
                "sm_room_types",
                "sm_routes",
                "sm_seat_plans",
                "sm_seat_plan_children",
                "sm_send_messages",
                "sm_setup_admins",
                "sm_staff_attendance_imports",
                "sm_staff_attendences",
                "sm_students",
                "sm_student_attendances",
                "sm_student_attendance_imports",
                "sm_student_categories",
                "sm_student_certificates",
                "sm_student_documents",
                "sm_student_excel_formats",
                "sm_student_groups",
                "sm_student_homeworks",
                "sm_student_id_cards",
                "sm_student_promotions",
                "sm_student_take_online_exams",
                "sm_student_take_online_exam_questions",
                "sm_student_take_onln_ex_ques_options",
                "sm_student_timelines",
                "sm_subjects",
                "sm_subject_attendances",
                "sm_suppliers",
                "sm_teacher_upload_contents",
                "sm_temporary_meritlists",
                "sm_to_dos",
                "sm_upload_contents",
                "sm_upload_homework_contents",
                "sm_user_logs",
                "sm_vehicles",
                "sm_visitors",
                "sm_weekends"
            ];


            $name ='class';
            $name2 ='school_id';
            $result= array();

                foreach($table_list as $row){

                        if (Schema::hasColumn($row, $name) && Schema::hasColumn($row, $name2)) {


                               //$result[] = DB::table($row)->count();
                                if(DB::table($row)->count() > 0)
                               {
                                $result["table"][] = $row;
                                $result[]["data"] = DB::table($row)->count();
                               }

                        }
                    }


            return $result;
        } catch (\Exception $e) {
            dd($e->getMessage());

            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function studentUpdate()
    {
        try {
            $classes = [];//        $students = SmStudent::all()->except(1678);
            $tables = [
                "sm_assign_class_teachers",
                "sm_assign_subjects",
                "sm_class_routine_updates",
                "sm_exams",
                "sm_exam_attendances",
                "sm_exam_schedules",
                "sm_exam_setups",
                "sm_homeworks",
                "sm_mark_stores",
                "sm_result_stores",
                'sm_students'
            ];
            $ids = [];
            $name = 'section_id';
            foreach ($tables as $table) {
                $className = 'App\\' . Str::studly(Str::singular($table));
                $data = $className::all();
                if (count($data) > 0) {
                    if (Schema::hasColumn($table, $name) && Schema::hasColumn($table, 'school_id')) {
                        foreach ($data as $row) {
                            if ($row->section()->exists()) {
                                $findSection = CheckSection::where('section_name', $row->section->section_name)->where('school_id', $row->section->school_id)->where('active_status', 1)->first();
                                if ($findSection) {
                                    $row->section_id = $findSection->id;
                                    $row->save();
                                } else
                                    $ids[] = $row->section_id;
                            }
                        }
                    }

                }
            }
            return $ids;
        } catch (\Exception $e) {
            dd($e);
        }

    }

    public
    function classUpdateNew()
    {
        $students = SmStudent::where('section_id','<',4000)->groupBy('section_id')->get();
        dd($students->count());
    }

    public function classUpdateOld()
    {
        $ids =[];
        $classes = SmClass::where('id','<=',1115)->whereNotIn('id',[169,170,171,172,173,174])->get();
        foreach ($classes as $class) {
            $academic = SmAcademicYear::where('school_id',$class->school_id)->where('year','2020')->first();
            if ($academic){
                $class->academic_id = $academic->id;
                $class->save();
            }
            else{
                $ids[] = $class->school_id;
            }
        }
        return $ids;
    }

    public function sectionUpdateOld()
    {
        $ids =[];
        $sections = SmSection::where('id','<=',2166)->get();
        foreach ($sections as $section) {
            $academic = SmAcademicYear::where('school_id',$section->school_id)->where('year','2020')->first();
            if ($academic){
                $section->academic_id = $academic->id;
                $section->save();
            }
            else{
                $ids[] = $section->school_id;
            }
        }
        return $ids;
    }

    public function classSectionUpdateOld()
    {
        $ids =[];
        $sections = SmClassSection::where('id','<=',21942)->get();
        foreach ($sections as $section) {
            $academic = SmAcademicYear::where('school_id',$section->school_id)->where('year','2020')->first();
            if ($academic){
                $section->academic_id = $academic->id;
                $section->save();
            }
            else{
                $ids[] = $section->school_id;
            }
        }
        return $ids;
    }

    public function studentDelete()
    {
        $students = SmStudent::where('class_id','>',4000)->get();
        return count($students);
    }
}