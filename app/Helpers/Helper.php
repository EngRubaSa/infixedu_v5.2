<?php

use App\SmAcademicYear;
use App\SmStaff;
use App\SmsTemplate;
use App\SmStyle;
use App\User;
use App\SmParent;
use App\SmSchool;
use App\SmStudent;
use App\SmLanguage;
use App\SmExamSetup;
use App\SmMarkStore;
use App\SmDateFormat;
use App\SmFeesMaster;
use App\SmMarksGrade;
use App\SmSmsGateway;
use App\SmFeesPayment;
use App\SmResultStore;
use App\SmClassTeacher;
use App\SmEmailSetting;
use App\SmExamSchedule;
use App\SmAssignSubject;
use App\SmExamAttendance;
use App\SmGeneralSettings;
use App\SmHomeworkStudent;
use App\InfixModuleManager;
use App\CustomResultSetting;
use App\SmExamAttendanceChild;
use App\SmOptionalSubjectAssign;
use App\SmUploadHomeworkContent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Nwidart\Modules\Facades\Module;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Artisan;
use Modules\RolePermission\Entities\InfixRole;
use Modules\RolePermission\Entities\InfixPermissionAssign;
use Modules\ParentRegistration\Entities\SmStudentRegistration;

function sendEmailBio($data, $to_name, $to_email, $email_sms_title)
{
    $systemSetting = DB::table('sm_general_settings')->select('school_name', 'email')->find(1);
    $systemEmail = DB::table('sm_email_settings')->find(1);
    $system_email = $systemEmail->from_email;
    $school_name = $systemSetting->school_name;
    if (!empty($system_email)) {
        $data['email_sms_title'] = $email_sms_title;
        $data['system_email'] = $system_email;
        $data['school_name'] = $school_name;
        $details = $to_email;
        dispatch(new \App\Jobs\SendEmailJob($data, $details));
        $error_data = [];
        return true;
    } else {
        $error_data[0] = 'success';
        $error_data[1] = 'Operation Failed, Please Updated System Mail';
        return $error_data;
    }

}

function sendSMSApi($to_mobile, $sms, $id)
{
    $activeSmsGateway = SmSmsGateway::find($id);
    if ($activeSmsGateway->gateway_name == 'Twilio') {
        $client = new Twilio\Rest\Client($activeSmsGateway->twilio_account_sid, $activeSmsGateway->twilio_authentication_token);
        if (!empty($to_mobile)) {
            $result = $message = $client->messages->create($to_mobile, array('from' => $activeSmsGateway->twilio_registered_no, 'body' => $sms));
            return $result;
        }
    } //end Twilio
    else if ($activeSmsGateway->gateway_name == 'Clickatell') {

        // config(['clickatell.api_key' => $activeSmsGateway->clickatell_api_id]); //set a variale in config file(clickatell.php)

        $clickatell = new \Clickatell\Rest();
        $result = $clickatell->sendMessage(['to' => $to_mobile, 'content' => $sms]);
    } //end Clickatell

    else if ($activeSmsGateway->gateway_name == 'Msg91') {
        $msg91_authentication_key_sid = $activeSmsGateway->msg91_authentication_key_sid;
        $msg91_sender_id = $activeSmsGateway->msg91_sender_id;
        $msg91_route = $activeSmsGateway->msg91_route;
        $msg91_country_code = $activeSmsGateway->msg91_country_code;

        $curl = curl_init();

        $url = "https://api.msg91.com/api/sendhttp.php?mobiles=" . $to_mobile . "&authkey=" . $msg91_authentication_key_sid . "&route=" . $msg91_route . "&sender=" . $msg91_sender_id . "&message=" . $sms . "&country=91";

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true, CURLOPT_ENCODING => "", CURLOPT_MAXREDIRS => 10, CURLOPT_TIMEOUT => 30, CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1, CURLOPT_CUSTOMREQUEST => "GET", CURLOPT_SSL_VERIFYHOST => 0, CURLOPT_SSL_VERIFYPEER => 0,
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            $result = "cURL Error #:" . $err;
        } else {
            $result = $response;
        }
    } //end Msg91
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => 0,
    ));
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        $result = "cURL Error #:" . $err;
    } else {
        $result = $response;
    }
    return $result;

} //end Msg91


function sendSMSBio($to_mobile, $sms)
{
    $activeSmsGateway = SmSmsGateway::where('active_status', '=', 1)->first();
    if ($activeSmsGateway->gateway_name == 'Twilio') {

        config(['TWILIO.SID' => $activeSmsGateway->twilio_account_sid]);
        config(['TWILIO.TOKEN' => $activeSmsGateway->twilio_authentication_token]);
        config(['TWILIO.FROM' => $activeSmsGateway->twilio_registered_no]);
        $account_id = $activeSmsGateway->twilio_account_sid; // Your Account SID from www.twilio.com/console
        $auth_token = $activeSmsGateway->twilio_authentication_token; // Your Auth Token from www.twilio.com/console
        $from_phone_number = $activeSmsGateway->twilio_registered_no;
        $client = new Twilio\Rest\Client($account_id, $auth_token);
        if (!empty($to_mobile)) {
            $result = $message = $client->messages->create($to_mobile, array('from' => $from_phone_number, 'body' => $sms));
            return $result;
        }
    } //end Twilio
    else if ($activeSmsGateway->gateway_name == 'Clickatell') {


        // config(['clickatell.api_key' => $activeSmsGateway->clickatell_api_id]); //set a variale in config file(clickatell.php)

        $clickatell = new \Clickatell\Rest();
        $result = $clickatell->sendMessage(['to' => $to_mobile, 'content' => $sms]);
    } //end Clickatell

    else if ($activeSmsGateway->gateway_name == 'Msg91') {
        $msg91_authentication_key_sid = $activeSmsGateway->msg91_authentication_key_sid;
        $msg91_sender_id = $activeSmsGateway->msg91_sender_id;
        $msg91_route = $activeSmsGateway->msg91_route;
        $msg91_country_code = $activeSmsGateway->msg91_country_code;

        $curl = curl_init();

        $url = "https://api.msg91.com/api/sendhttp.php?mobiles=" . $to_mobile . "&authkey=" . $msg91_authentication_key_sid . "&route=" . $msg91_route . "&sender=" . $msg91_sender_id . "&message=" . $sms . "&country=91";

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true, CURLOPT_ENCODING => "", CURLOPT_MAXREDIRS => 10, CURLOPT_TIMEOUT => 30, CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1, CURLOPT_CUSTOMREQUEST => "GET", CURLOPT_SSL_VERIFYHOST => 0, CURLOPT_SSL_VERIFYPEER => 0,
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            $result = "cURL Error #:" . $err;
        } else {
            $result = $response;
        }
    } //end Msg91
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION =>
            CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => 0,
    ));
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        $result = "cURL Error #:" . $err;
    } else {
        $result = $response;
    }
    return $result;
} //end Msg91


function getValueByString($student_id, $string, $extra = null)
{
    $student = SmStudent::find($student_id);
    if ($extra != null) {
        return $student->$string->$extra;

    } else {
        return $student->$string;
    }
}

function getParentName($student_id, $string, $extra = null)
{
    $student = SmStudent::find($student_id);
    $parent = SmParent::where('id', $student->parent_id)->first();
    if ($extra != null) {

        return $student->$parent->$extra;

    } else {
        return $parent->fathers_name;
    }
}

function SMSBody($body, $s_id, $time)
{
    try {
        $original_message = $body;
        // $original_message= "Dear Parent [fathers_name], your child [class] came to the school at [section]";
        $chars = preg_split('/[\s,]+/', $original_message, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        foreach ($chars as $item) {
            if (strstr($item[0], "[")) {
                $str = str_replace('[', '', $item);
                $str = str_replace(']', '', $str);
                $str = str_replace('.', '', $str);
                if ($str == "class") {
                    $str = "class";
                    $extra = "class_name";
                    $custom_array[$item] = getValueByString($s_id, $str, $extra);
                } elseif ($str == "section") {
                    $str = "section";
                    $extra = "section_name";
                    $custom_array[$item] = getValueByString($s_id, $str, $extra);
                } elseif ($str == 'check_in_time') {
                    $custom_array[$item] = $time;
                } elseif ($str == 'fathers_name') {
                    $str = "parents";
                    $extra = "fathers_name";
                    $custom_array[$item] = getValueByString($s_id, $str, $extra);
                    // $custom_array[$item]= 'father';
                } else {
                    $custom_array[$item] = getValueByString($s_id, $str);
                }
            }
        }

        foreach ($custom_array as $key => $value) {
            $original_message = str_replace($key, $value, $original_message);
        }


        return $original_message;


    } catch (\Exception $e) {
        $data = [];
        return $data;
    }

}

function FeesDueSMSBody($body, $s_id, $time)
{
    try {
        $original_message = $body;
        $chars = preg_split('/[\s,]+/', $original_message, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        foreach ($chars as $item) {
            if (strstr($item[0], "|")) {
                $str = str_replace('|', '', $item);
                // return $str;
                $str = str_replace('|', '', $str);
                $str = str_replace('.', '', $str);
                if ($str == "StudentName") {
                    $str = "StudentName";
                    $extra = "full_name";
                    $custom_array[$item] = getValueByString($s_id, $str, $extra);

                } elseif ($str == 'fathers_name') {
                    $str = "parents";
                    $extra = "fathers_name";
                    $custom_array[$item] = getValueByString($s_id, $str, $extra);
                    // $custom_array[$item]= 'father';
                } else {
                    $custom_array[$item] = getValueByString($s_id, $str);
                }
            }
        }

        foreach ($custom_array as $key => $value) {
            $original_message = str_replace($key, $value, $original_message);
        }

        return $original_message;


    } catch (\Exception $e) {
        $data = [];
        return $data;
    }

}

if (!function_exists('DateConvat')) {
    function DateConvat($input_date)
    {
        $generalSetting = SmGeneralSettings::find(1);
        $system_date_foramt = SmDateFormat::find($generalSetting->date_format_id);
        $DATE_FORMAT = $system_date_foramt->format;
        echo date_format(date_create($input_date), $DATE_FORMAT);
    }
}


if (!function_exists('userPermission')) {
    function userPermission($assignId, $role_id = null, $purpose = null)
    {
        $role_id = Auth::user()->role_id;
        $permissions = session()->get('permission');


        if (empty($permissions) && (Auth::user()->is_administrator != "yes")) {
            $infixRole = InfixRole::find($role_id);

            $module_links = [];
            if ($infixRole->is_saas == 1) {
                $permissions = InfixPermissionAssign::where('role_id', $role_id)->get(['id', 'module_id']);
            }
            if ($infixRole->is_saas == 0) {
                $permissions = InfixPermissionAssign::where('role_id', $role_id)->where('school_id', Auth::user()->school_id)->get(['id', 'module_id']);
            }
            foreach ($permissions as $permission) {
                $module_links[] = $permission->module_id;
            }
            $permissions = $module_links;
        }

        if ($role_id == 1 && Auth::user()->is_administrator == "yes") {
            return True;
        }

        if ((!empty($permissions)) && ($role_id != 1)) {

            if (@in_array($assignId, $permissions)) {
                return True;
            } else {
                return False;
            }
        } else {

            return True;
        }

    }
}


if (!function_exists('moduleStatusCheck')) {
    function moduleStatusCheck($module)
    {

        try {
            // get all module from session;
            $all_module = session()->get('all_module');
            //check module status
            $modulestatus = Module::find($module)->isDisabled();

            //if session exist and non empty
            if (!empty($all_module)) {
                if ((in_array($module, $all_module)) && $modulestatus == false) {

                    return True;
                } else {
                    return False;
                }

            } //if session failed or empty data then hit database
            else {
                // is available Modules / FeesCollection1 / Providers / FeesCollectionServiceProvider . php
                $is_module_available = 'Modules/' . $module . '/Providers/' . $module . 'ServiceProvider.php';

                if (file_exists($is_module_available)) {
                    $modulestatus = Module::find($module)->isDisabled();

                    if ($modulestatus == FALSE) {
                        $is_verify = InfixModuleManager::where('name', $module)->first();

                        if (!empty($is_verify->purchase_code)) {
                            return TRUE;

                        }
                    }
                }
            }
        } catch (\Throwable $th) {
            return FALSE;
        }

    }
}

if (!function_exists('dateConvert')) {

    function dateConvert($input_date)
    {
        try {
            $system_date_format = session()->get('system_date_format');

            if (empty($system_date_format)) {
                $date_format_id = SmGeneralSettings::where('id', 1)->first(['date_format_id'])->date_format_id;
                $system_date_format = SmDateFormat::where('id', $date_format_id)->first(['format'])->format;
                session()->put('system_date_format', $system_date_format);
                return date_format(date_create($input_date), $system_date_format);
            } else {

                return date_format(date_create($input_date), $system_date_format);

            }
        } catch (\Throwable $th) {

            return $input_date;
        }


    }
}

if (!function_exists('getAcademicId')) {
    function getAcademicId()
    {
        try {
            $session_id = session()->get('sessionId');
            return $session_id;
        } catch (\Exception $e) {
            return SmGeneralSettings::where('school_id', Auth::user()->school_id)->first('session_id')->session_id;;
        }
    }
}

if (!function_exists('checkAdmin')) {
    function checkAdmin()
    {
        if (Auth::check()) {
          
            if (Auth::user()->is_administrator == "yes") {
                return true;
            } elseif (Auth::user()->is_saas == 1) {
                return true;
            } else {
                return false;
            }
        }

    }
}

if (!function_exists('send_mail')) {
    function send_mail($reciver_email, $receiver_name, $subject, $view, $compact = [])
    {
        $setting = SmEmailSetting::first();
        $sender_email = $setting->from_email;
        $sender_name = $setting->from_name;
        $email_driver = SmGeneralSettings::first('email_driver')->email_driver;
        try {
            if ($email_driver == "smtp") {
                Mail::send($view, $compact, function ($message) use ($reciver_email, $receiver_name, $sender_name, $sender_email, $subject) {
                    $message->to($reciver_email, $receiver_name)->subject($subject);
                    $message->from($sender_email, $sender_name);
                });
            }
            if ($email_driver == "php") {
                // dd($email_driver);
                $message = (string)view($view, $compact);
                $headers = "From: <$sender_email> \r\n";
                $headers .= "Reply-To: $receiver_name <$reciver_email> \r\n";
                $headers .= "MIME-Version: 1.0\r\n";
                $headers .= "Content-Type: text/html; charset=utf-8\r\n";
                @mail($reciver_email, $subject, $message, $headers);


            }

        } catch (\Exception $e) {
            Log::info($e->getMessage());
        }
    }
}


// Get File Path From HELPER

if (!function_exists('getFilePath3')) {
    function getFilePath3($data)
    {
        if ($data) {
            $name = explode('/', $data);
            if ($name[3]) {
                return $name[3];
            } else {
                return '';
            }
        } else {
            return '';
        }
    }
}
if (!function_exists('getFilePath4')) {
    function getFilePath4($data)
    {
        if ($data) {
            $name = explode('/', $data);
            if ($name[4]) {
                return $name[3];
            } else {
                return '';
            }
        } else {
            return '';
        }
    }
}

if (!function_exists('showPicName')) {
    function showPicName($data)
    {
        $name = explode('/', $data);
        return $name[3];
    }
}

if (!function_exists('showJoiningLetter')) {
    function showJoiningLetter($data)
    {
        $name = explode('/', $data);
        return $name[3];
    }
}

if (!function_exists('showResume')) {
    function showResume($data)
    {
        $name = explode('/', $data);
        return $name[3];
    }
}
if (!function_exists('showDocument')) {
    function showDocument($data)
    {
        @$name = explode('/', @$data);
        if (!empty(@$name[4])) {

            return $name[4];
        } else {
            return '';
        }
    }
}
// end get file path from helpers


if (!function_exists('termResult')) {
    function termResult($exam_id, $class_id, $section_id, $student_id, $subject_count)
    {
        try {
            $assigned_subject = SmAssignSubject::where('class_id', $class_id)->where('section_id', $section_id)->get();
            $mark_store = DB::table('sm_mark_stores')->where([['class_id', $class_id], ['section_id', $section_id], ['exam_term_id', $exam_id], ['student_id', $student_id]])->first();
            $subject_marks = [];
            $subject_gpas = [];
            foreach ($assigned_subject as $subject) {
                $subject_mark = DB::table('sm_mark_stores')->where([['class_id', $class_id], ['section_id', $section_id], ['exam_term_id', $exam_id], ['student_id', $student_id], ['subject_id', $subject->subject_id]])->first();
                $custom_result = new CustomResultSetting;  // correct

                $subject_gpa = $custom_result->getGpa($subject_mark->total_marks);
                // return $subject_mark;
                $subject_marks[$subject->subject_id][0] = $subject_mark->total_marks;
                $subject_marks[$subject->subject_id][1] = $subject_gpa;
                $subject_gpas[$subject->subject_id] = $subject_gpa;
            }
            $total_gpa = array_sum($subject_gpas);
            $term_result = $total_gpa / $subject_count;
            return $term_result;
        } catch (\Exception $e) {
            $data = [];
            return $data;
        }
    }
}

if (!function_exists('getFinalResult')) {
    function getFinalResult($exam_id, $class_id, $section_id, $student_id, $percentage)
    {
        try {
            $system_setting = SmGeneralSettings::find(1);
            $system_setting = $system_setting->session_id;
            $custom_result_setup = CustomResultSetting::where('academic_year', $system_setting)->first();

            $assigned_subject = SmAssignSubject::where('class_id', $class_id)->where('section_id', $section_id)->get();

            $all_subjects_gpa = [];
            foreach ($assigned_subject as $subject) {
                $custom_result = new CustomResultSetting;
                $subject_gpa = $custom_result->getSubjectGpa($exam_id, $class_id, $section_id, $student_id, $subject->subject_id);
                $all_subjects_gpa[] = $subject_gpa[$subject->subject_id][1];
            }
            $percentage = $custom_result_setup->$percentage;
            $term_gpa = array_sum($all_subjects_gpa) / $assigned_subject->count();;
            $percentage = number_format((float)$percentage, 2, '.', '');
            $new_width = ($percentage / 100) * $term_gpa;
            return $new_width;
        } catch (\Exception $e) {
            $data = [];
            return $data;
        }
    }
}

if (!function_exists('getSubjectGpa')) {
    function getSubjectGpa($class_id, $section_id, $exam_id, $student_id, $subject)
    {
        try {
            $subject_marks = [];
            $subject_mark = DB::table('sm_mark_stores')->where('student_id', $student_id)->where('exam_term_id', '=', $exam_id)->first();

            $custom_result = new CustomResultSetting;
            $subject_gpa = $custom_result->getGpa($subject_mark->total_marks);

            $subject_marks[$subject][0] = $subject_mark->total_marks;
            $subject_marks[$subject][1] = $subject_gpa;

            // return $subject_mark->total_marks;
            return $subject_marks;
        } catch (\Exception $e) {
            $data = [];
            return $data;
        }
    }
}

if (!function_exists('getGrade')) {
    function getGrade($marks)
    {
        try {
            $marks_gpa = DB::table('sm_marks_grades')->where('percent_from', '<=', $marks)->where('percent_upto', '>=', $marks)
                ->where('academic_id', getAcademicId())->first();
            return $marks_gpa->grade_name;
        } catch (\Exception $e) {
            $data = [];
            return $data;
        }
    }
}

if (!function_exists('getNumberOfPart')) {
    function getNumberOfPart($subject_id, $class_id, $section_id, $exam_term_id)
    {
        try {
            $results = SmExamSetup::where([
                ['class_id', $class_id],
                ['subject_id', $subject_id],
                ['section_id', $section_id],
                ['exam_term_id', $exam_term_id],
            ])->get();
            return $results;
        } catch (\Exception $e) {
            $data = [];
            return $data;
        }
    }
}

if (!function_exists('GetResultBySubjectId')) {
    function GetResultBySubjectId($class_id, $section_id, $subject_id, $exam_id, $student_id)
    {

        try {
            $data = SmMarkStore::where([
                ['class_id', $class_id],
                ['section_id', $section_id],
                ['exam_term_id', $exam_id],
                ['student_id', $student_id],
                ['subject_id', $subject_id]
            ])->get();
            return $data;
        } catch (\Exception $e) {
            $data = [];
            return $data;
        }
    }
}

if (!function_exists('GetFinalResultBySubjectId')) {
    function GetFinalResultBySubjectId($class_id, $section_id, $subject_id, $exam_id, $student_id)
    {

        try {
            $data = SmResultStore::where([
                ['class_id', $class_id],
                ['section_id', $section_id],
                ['exam_type_id', $exam_id],
                ['student_id', $student_id],
                ['subject_id', $subject_id]
            ])->first();

            return $data;
        } catch (\Exception $e) {
            $data = [];
            return $data;
        }
    }
}

if (!function_exists('markGpa')) {
    function markGpa($marks)
    {
        $mark = SmMarksGrade::where([['percent_from', '<=', floor($marks)], ['percent_upto', '>=', floor($marks)]])->where('academic_id', getAcademicId())->first();
        if ($mark)
            return $mark;
        else
            return '';
    }
}
if (!function_exists('getGrade')) {
    function getGrade($grade)
    {
        $mark = SmMarksGrade::where('from', '<=', $grade)->where('up', '>=', $grade)->where('academic_id', getAcademicId())->first();
        if ($mark)
            return $mark;
        else
            return '';
    }
}

if (!function_exists('is_optional_subject')) {
    function is_optional_subject($student_id, $subject_id)
    {
        try {
            $result = SmOptionalSubjectAssign::where('student_id', $student_id)->where('subject_id', $subject_id)->first();
            if ($result) {
                return TRUE;
            } else {
                return FALSE;
            }
        } catch (\Exception $e) {
            return FALSE;
        }
    }
}

if (!function_exists('getMarksOfPart')) {
    function getMarksOfPart($student_id, $subject_id, $class_id, $section_id, $exam_term_id)
    {
        try {
            $results = SmMarkStore::where([
                ['student_id', $student_id],
                ['class_id', $class_id],
                ['subject_id', $subject_id],
                ['section_id', $section_id],
                ['exam_term_id', $exam_term_id],
            ])->get();
            return $results;
        } catch (\Exception $e) {
            $data = [];
            return $data;
        }
    }
}

if (!function_exists('getExamResult')) {
    function getExamResult($exam_id, $student)
    {
        $eligible_subjects = SmAssignSubject::where('class_id', $student->class_id)->where('section_id', $student->section_id)->where('academic_id', getAcademicId())
            ->where('school_id', Auth::user()->school_id)->get();

        foreach ($eligible_subjects as $subject) {

            $getMark = SmResultStore::where([
                ['exam_type_id', $exam_id],
                ['class_id', $student->class_id],
                ['section_id', $student->section_id],
                ['student_id', $student->id],
                ['subject_id', $subject->subject_id]
            ])->first();

            if ($getMark == "") {
                return false;
            }


            $result = SmResultStore::where([
                ['exam_type_id', $exam_id],
                ['class_id', $student->class_id],
                ['section_id', $student->section_id],
                ['student_id', $student->id]
            ])->get();

            return $result;
        }
    }
}

if (!function_exists('teacherAssignedClass')) {
    function teacherAssignedClass()
    {
        try {
            $class_id = [];
            $role_id = Auth::user()->role_id;
            if ($role_id == 4) {
                $classes = SmClassTeacher::where('teacher_id', Auth::user()->id)->get(['id']);
                foreach ($classes as $class) {
                    $class_id[] = $class->module_id;
                }
            } else {

                $general_setting = SmGeneralSettings::find(1);
                return @$general_setting->school_name;
            }
        } catch (\Exception $e) {
            return $class_id = [];;
        }
    }
}

if (!function_exists('getValueByStringTestRegistration')) {
    function getValueByStringTestRegistration($data, $str)
    {
        if ($str == 'password') {
            return '123456';
        } elseif ($str == 'school_name') {
            if (moduleStatusCheck('Saas') == TRUE) {
                $student_info = SmStudentRegistration::find(@$data['id']);
                return @$student_info->school->school_name;
            } else {
                $general_setting = SmGeneralSettings::find(1);
                return @$general_setting->school_name;
            }
        }

        if ($data['slug'] == 'student') {
            $student_info = SmStudentRegistration::find(@$data['id']);
            if ($str == 'name') {
                return @$student_info->first_name . ' ' . @$student_info->last_name;
            } elseif ($str == 'guardian_name') {
                return @$student_info->guardian_name;
            } elseif ($str == 'class') {
                return @$student_info->class->class_name;
            } elseif ($str == 'section') {
                return @$student_info->section->section_name;
            }
        } elseif ($data['slug'] == 'parent') {
            $parent_info = SmStudentRegistration::find(@$data['id']);
            if ($str == 'name') {
                return @$parent_info->guardian_name;
            } elseif ($str == 'student_name') {
                return @$parent_info->first_name . ' ' . @$parent_info->last_name;
            }
        }
    }
}
if (!function_exists('getValueByStringTestReset')) {
    function getValueByStringTestReset($data, $str)
    {
        if ($str == 'school_name') {

            $general_setting = SmGeneralSettings::find(1);
            return @$general_setting->school_name;
        } elseif ($str == 'name') {
            $user = User::where('email', $data['email'])->first();
            return @$user->full_name;
        }
    }
}

if (!function_exists('subjectPosition')) {
    function subjectPosition($subject_id, $class_id, $custom_result)
    {

        $students = SmStudent::where('class_id', $class_id)->get();

        $subject_mark_array = [];
        foreach ($students as $student) {
            $subject_marks = 0;

            $first_exam_mark = SmMarkStore::where('student_id', $student->id)->where('class_id', $class_id)->where('subject_id', $subject_id)->where('exam_term_id', $custom_result->exam_term_id1)->sum('total_marks');

            $subject_marks = $subject_marks + $first_exam_mark / 100 * $custom_result->percentage1;

            $second_exam_mark = SmMarkStore::where('student_id', $student->id)->where('class_id', $class_id)->where('subject_id', $subject_id)->where('exam_term_id', $custom_result->exam_term_id2)->sum('total_marks');

            $subject_marks = $subject_marks + $second_exam_mark / 100 * $custom_result->percentage2;

            $third_exam_mark = SmMarkStore::where('student_id', $student->id)->where('class_id', $class_id)->where('subject_id', $subject_id)->where('exam_term_id', $custom_result->exam_term_id3)->sum('total_marks');

            $subject_marks = $subject_marks + $third_exam_mark / 100 * $custom_result->percentage3;

            $subject_mark_array[] = round($subject_marks);


        }

        arsort($subject_mark_array);

        $position_array = [];
        foreach ($subject_mark_array as $position_mark) {
            $position_array[] = $position_mark;
        }


        return $position_array;

    }
}

if (!function_exists('getValueByStringDuesFees')) {
    function getValueByStringDuesFees($student_detail, $str, $fees_info)
    {
        if ($str == 'student_name') {

            return @$student_detail->full_name;

        } elseif ($str == 'parent_name') {

            $parent_info = SmParent::find($student_detail->parent_id);
            return @$parent_info->fathers_name;

        } elseif ($str == 'due_amount') {

            return @$fees_info['dues_fees'];

        } elseif ($str == 'due_date') {

            $fees_master = SmFeesMaster::find($fees_info['fees_master']);


            return @$fees_master->date;

        } elseif ($str == 'school_name') {

            return @Auth::user()->school->school_name;

        } elseif ($str == 'fees_name') {

            $fees_master = SmFeesMaster::find($fees_info['fees_master']);

            return $fees_master->feesTypes->name;
        }
    }
}
if (!function_exists('assignedRoutineSubject')) {

    function assignedRoutineSubject($class_id, $section_id, $exam_id, $subject_id)
    {

        try {
            return SmExamSchedule::where('class_id', $class_id)->where('section_id', $section_id)->where('exam_term_id', $exam_id)->where('subject_id', $subject_id)->first();
        } catch (\Exception $e) {
            $data = [];
            return $data;
        }
    }

}

if (!function_exists('assignedRoutine')) {

    function assignedRoutine($class_id, $section_id, $exam_id, $subject_id, $exam_period_id)
    {
        try {
            return SmExamSchedule::where('class_id', $class_id)->where('section_id', $section_id)->where('exam_term_id', $exam_id)->where('subject_id', $subject_id)
                ->where('exam_period_id', $exam_period_id)->first();
        } catch (\Exception $e) {
            $data = [];
            return $data;
        }
    }

}

if (!function_exists('is_absent_check')) {

    function is_absent_check($exam_id, $class_id, $section_id, $subject_id, $student_id)
    {
        try {
            $exam_attendance = SmExamAttendance::where('exam_id', $exam_id)->where('class_id', $class_id)->where('section_id', $section_id)->where('subject_id', $subject_id)->first();
            $exam_attendance_child = SmExamAttendanceChild::where('exam_attendance_id', $exam_attendance->id)->where('student_id', $student_id)->first();
            return $exam_attendance_child;
        } catch (\Exception $e) {
            $data = [];
            return $data;
        }
    }

}


if (!function_exists('feesPayment')) {
    function feesPayment($type_id, $student_id)
    {
        try {
            return SmFeesPayment::where('fees_type_id', $type_id)->where('student_id', $student_id)->get();
        } catch (\Exception $e) {
            $data = [];
            return $data;
        }
    }
}

if (!function_exists('textDirection')) {

    function textDirection()
    {
        try {
            $var = session()->get('text_direction');

            if (!empty($var)) {
                return $var;
            } else {

                $ttl_rtl = SmGeneralSettings::where('school_id', Auth::user()->school_id)->first('ttl_rtl')->ttl_rtl;
                session()->put('text_direction', $ttl_rtl);
                return session()->get('text_direction');
            }

        } catch (\Exception $e) {

            return SmGeneralSettings::where('school_id', Auth::user()->school_id)->first('ttl_rtl')->ttl_rtl;
        }
    }

}


if (!function_exists('generalSetting')) {
    function generalSetting()
    {
        session()->forget('generalSetting');
        if (session()->has('generalSetting')) {
            return session()->get('generalSetting');
        } else {
            $generalSetting = SmGeneralSettings::first();
            session()->put('generalSetting', $generalSetting);

            return session()->get('generalSetting');
        }
    }
}

if (!function_exists('activeStyle')) {
    function activeStyle()
    {
        if (session()->has('active_style')) {
            $active_style = session()->get('active_style');
            return $active_style;
        } else {
            $active_style = Auth::check() ? SmStyle::where('school_id', Auth::user()->school_id)->where('is_active', 1)->first() :
                SmStyle::where('school_id', 1)->where('is_active', 1)->first();
            session()->put('active_style', $active_style);
            return session()->get('active_style');
        }
    }
}

if (!function_exists('systemDateFormat')){
    function systemDateFormat(){
        if (session()->has('system_date_format')) {
            return session()->get('system_date_format');
        } else {
            $system_date_format = SmDateFormat::find(DB::table('sm_general_settings')->first()->date_format_id);
            session()->put('system_date_foramt', $system_date_format);

            return session()->get('system_date_foramt');
        }
    }
}
if (!function_exists('emailTemplate')){
    function emailTemplate(){
        if (session()->has('email_template')) {
            return session()->get('email_template');
        } else {
            $email_template = SmsTemplate::where('id', 1)->first();
            session()->put('email_template', $email_template);

            return session()->get('email_template');
        }
    }
}
if (!function_exists('dashboardBackground')){
    function dashboardBackground(){
        if (session()->has('dashboard_background')) {
            return session()->get('dashboard_background');
        } else {
            $dashboard_background = DB::table('sm_background_settings')->where([['is_default', 1], ['title', 'Dashboard Background']])->first();
            session()->put('dashboard_background', $dashboard_background);

            return session()->get('dashboard_background');
        }
    }
}

if (!function_exists('allStyles')){
    function allStyles(){
       
        if (session()->has('all_styles')) {
            return session()->get('all_styles');
        } else {
            $all_styles = SmStyle::where('school_id', 1)->where('active_status', 1)->get() ;
            session()->put('all_styles', $all_styles);

            return session()->get('all_styles');
        }
    }
}

if (!function_exists('textDirection')){
    function textDirection(){
       
        if (session()->has('text_direction')) {
            return session()->get('text_direction');
        } else {
            $ttl_rtl = SmGeneralSettings::where('school_id', Auth::user()->school_id)->first('ttl_rtl')->ttl_rtl;
            session()->put('text_direction', $ttl_rtl);

            return session()->get('text_direction');
        }
    }
}

if (!function_exists('schoolConfig')){
    function schoolConfig(){
        if (session()->has('school_config')) {
            return session()->get('school_config');
        } else {
            $school_config = Auth::check() ? SmGeneralSettings::where('school_id', Auth::user()->school_id)->first() :
                DB::table('sm_general_settings')->where('school_id', 1)->first();
            session()->put('school_config', $school_config);

            return session()->get('school_config');
        }
    }
}
if (!function_exists('selectedLanguage')){
    function selectedLanguage(){
        if (session()->has('selected_language')) {
            return session()->get('selected_language');
        } else {
            $selected_language = Auth::check() ? SmGeneralSettings::where('school_id', Auth::user()->school_id)->first() :
                DB::table('sm_general_settings')->where('school_id', 1)->first();
            session()->put('selected_language', $selected_language);

            return session()->get('selected_language');
        }
    }
}

if (!function_exists('profile')){
    function profile(){
        // dd(session()->get('profile'));
        if (session()->has('profile')) {
            return session()->get('profile');
        } else {
            if(Auth::user()->role_id == 2){
                $profile =  SmStudent::where('user_id', Auth::id())->first('student_photo');
                session()->put('profile', @$profile->student_photo);
                
            }else if(Auth::user()->role_id == 3){
                $profile =  SmParent::where('user_id', Auth::id())->first('guardians_photo');
                session()->put('profile', @$profile->guardians_photo);
                
            }
            else{
                $profile = SmStaff::where('user_id', Auth::id())->first('staff_photo');    
                session()->put('profile', @$profile->staff_photo);
            }
            

            return session()->get('profile');
        }
    }
}

if (!function_exists('getSession')){
    function getSession(){
        if (session()->has('session')) {
            return session()->get('session');
        } else {
            $selected_language = Auth::check() ? SmGeneralSettings::where('school_id', Auth::user()->school_id)->first() :
                DB::table('sm_general_settings')->where('school_id', 1)->first();
            $session = DB::table('sm_academic_years')->where('id', $selected_language->session_id)->first();
            session()->put('session', $session);

            return session()->get('session');
        }
    }
}

if (!function_exists('systemLanguage')){
    function systemLanguage(){
        if (session()->has('systemLanguage')) {
            return session()->get('systemLanguage');
        } else {

            $systemLanguage = SmLanguage::where('school_id', Auth::user()->school_id)->get();
            session()->put('systemLanguage',$systemLanguage);
            return session()->get('systemLanguage');
        }
    }
}

if (!function_exists('academicYears')){
    function academicYears(){
        if (session()->has('academic_years')) {
            return session()->get('academic_years');
        } else {
            $academic_years = Auth::check() ? SmAcademicYear::where('active_status', 1)->where('school_id', Auth::user()->school_id)->get() : '';
            session()->put('academic_years',$academic_years);
            return session()->get('academic_years');
        }
    }
}