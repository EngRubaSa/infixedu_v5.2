<?php

namespace App\Http\Controllers;

use App\User;
use App\SmExam;
use ZipArchive;
use App\SmClass;
use App\SmStaff;
use App\SmStyle;
use App\Language;
use App\SmBackup;
use App\SmModule;
use App\SmSchool;
use App\SmCountry;
use App\SmSection;
use App\SmSession;
use App\SmSubject;
use App\YearCheck;
use App\SmCurrency;
use App\SmExamType;
use App\SmLanguage;
use App\SmTimeZone;
use App\SmsTemplate;
use App\SmCustomLink;
use App\SmDateFormat;
use App\SmSmsGateway;
use App\ApiBaseMethod;
use App\Envato\Envato;
use GuzzleHttp\Client;
use App\SmAcademicYear;
use App\SmEmailSetting;
use App\SmAssignSubject;
use App\SmSystemVersion;
use App\SmLanguagePhrase;
use App\SmPaymentMethhod;
use App\AcademicTableList;
use App\SmGeneralSettings;
use App\SmHomePageSetting;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\SmFrontendPersmission;
use Illuminate\Support\Carbon;
use Laravel\Passport\Passport;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use App\SmPaymentGatewaySetting;
use Chumper\Zipper\Facades\Zipper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Session;
use Modules\Saas\Entities\SaasTableList;
use Illuminate\Support\Facades\Validator;

class SmSystemSettingController extends Controller
{

    public function __construct()
    {
        $this->middleware('PM');
        if (empty(Auth::user()->id)) {
            return redirect('login');
        }
    }


    public function sendTestMail()
    {

        $e = SmEmailSetting::where('school_id', Auth::user()->school_id)->first();
        if (empty($e)) {
            Toastr::error('All Field in Smtp Details Must Be filled Up', 'Failed');
            return redirect()->back();
        }

        if (
            $e->mail_username == ''
            || $e->mail_password == ''
            || $e->mail_encryption == ''
            || $e->mail_port == ''
            || $e->mail_host == ''
            || $e->mail_driver == ''
        ) {
            Toastr::error('All Field in Smtp Details Must Be filled Up', 'Failed');
            return redirect()->back();
        }
        try {
            $settings = SmEmailSetting::first();
            $reciver_email = "spn13@spondonit.com";
            $receiver_name =  Auth::user()->full_name;
            $subject = 'Email Setup Testing';
            $view ="test_email";
            $compact['data'] =  array('email' => $settings->from_email,'name' => Auth::user()->full_name); 
            @send_mail($reciver_email, $receiver_name, $subject , $view ,$compact);

            // $name = Auth::user()->full_name;
            // Mail::send(['text' => 'mail_test.mail'],['name' => $name], function ($message) {
            //     $settings = SmEmailSetting::find(1);
            //     $email = $settings->from_email;

            //     $message->to($email, 'Test Email')->subject('Email Setup Testing');
            //     $message->from($email, Auth::user()->full_name);
            // });
            Toastr::success('Test Mail Send Successfully', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed, Setup page is not complete', 'Failed');
            return redirect()->back();
        }
    }

    public function news()
    {

        try {
            $exams       = SmExam::where('academic_id', getAcademicId())->get();
            $exams_types = SmExamType::where('academic_id', getAcademicId())->get();
            $classes     = SmClass::where('academic_id', getAcademicId())->where('active_status', 1)->get();
            $subjects    = SmSubject::where('academic_id', getAcademicId())->where('active_status', 1)->get();
            $sections    = SmSection::where('academic_id', getAcademicId())->where('active_status', 1)->get();
            return view('frontEnd.home.light_news', compact('exams', 'classes', 'subjects', 'exams_types', 'sections'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }



    public function notificationApi(Request $request)
    {

        try {
            return view('backEnd.api');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function flutterNotificationApi(Request $request)
    {

        try {
            $user = User::where('id', $request->id)->first();
            if ($user->notificationToken != '') {
                //echo 'Infix Edu';
                define('API_ACCESS_KEY', 'AAAAFyQhhks:APA91bGJqDLCpuPgjodspo7Wvp1S4yl3jYwzzSxet_sYQH9Q6t13CtdB_EiwD6xlVhNBa6RcHQbBKCHJ2vE452bMAbmdABsdPriJy_Pr9YvaM90yEeOCQ6VF7JEQ501Prhnu_2bGCPNp');
                //   $registrationIds = ;
                #prep the bundle
                $msg = array(
                    'body'     => $_REQUEST['body'],
                    'title'    => $_REQUEST['title'],

                );
                $fields = array(
                    'to'        => $user->notificationToken,
                    'notification'    => $msg
                );


                $headers = array(
                    'Authorization: key=' . API_ACCESS_KEY,
                    'Content-Type: application/json'
                );
                #Send Reponse To FireBase Server
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
                $result = curl_exec($ch);
                echo $result;
                curl_close($ch);
            } else {
                if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                    return ApiBaseMethod::sendError('Token not found');
                }
            }
        } catch (\Exception $e) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError($e);
            }
        }
    }
    // tableEmpty
    public function tableEmpty()
    {

        try {
            $sms_services = DB::table('migrations')->get();

            $tables                = DB::select('SHOW TABLES');
            $table_list            = [];
            $table_list_with_count = [];
            $tableString = 'Tables_in_' . DB::connection()->getDatabaseName();

            foreach ($tables as $table) {
                $table_name              = $table->$tableString;
                $table_list[]            = $table_name;
                $count                   = DB::table($table_name)->count();
                $table_list_with_count[] = $table->$tableString . '(' . $count . ')';
                // $table_strings[] = '$this->call('. $table_name.'Seeder::class);';

            }
            return view('backEnd.systemSettings.tableEmpty', compact('table_list', 'table_list_with_count'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    // end tableEmpty

    public function databaseDelete(Request $request)
    {
        try {
            $list_of_table = $request->permisions;

            if (empty($list_of_table)) {
                Toastr::success('Operation successful', 'Success');
                return redirect()->back();
            }
            foreach ($list_of_table as $table) {

                DB::statement('SET FOREIGN_KEY_CHECKS=0;');
                DB::table($table)->truncate();
                //            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            }

            $staff = new SmStaff();

            $staff->user_id        = Auth::user()->id;
            $staff->role_id        = 1;
            $staff->staff_no       = 1;
            $staff->designation_id = 1;
            $staff->department_id  = 1;
            $staff->first_name     = 'Super';
            $staff->last_name      = 'Admin';
            $staff->full_name      = 'Super Admin';
            $staff->fathers_name   = 'NA';
            $staff->mothers_name   = 'NA';

            $staff->date_of_birth   = '1980-12-26';
            $staff->date_of_joining = '2019-05-26';

            $staff->gender_id        = 1;
            $staff->email            = Auth::user()->email;
            $staff->mobile           = '';
            $staff->emergency_mobile = '';
            $staff->merital_status   = '';
            $staff->staff_photo      = 'public/uploads/staff/staff1.jpg';

            $staff->current_address   = '';
            $staff->permanent_address = '';
            $staff->qualification     = '';
            $staff->experience        = '';

            $staff->casual_leave    = '12';
            $staff->medical_leave   = '15';
            $staff->metarnity_leave = '45';

            $staff->driving_license         = '';
            $staff->driving_license_ex_date = '2019-02-23';
            $staff->save();

            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function databaseRestory(Request $request)
    {

        try {
            set_time_limit(900);
            Artisan::call('db:seed');
            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function displaySetting()
    {

        try {
            $sms_services       = SmSmsGateway::all();
            $active_sms_service = SmSmsGateway::select('id')->where('active_status', 1)->first();
            return view('backEnd.systemSettings.displaySetting', compact('sms_services', 'active_sms_service'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function smsSettings()
    {

        try {
            $sms_services       = SmSmsGateway::all();
            $active_sms_service = $sms_services->where('active_status', 1)->first();

            return view('backEnd.systemSettings.smsSettings', compact('sms_services', 'active_sms_service'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function languageSettings()
    {

        try {
            $sms_languages = SmLanguage::where('school_id', Auth::user()->school_id)->get();
            $all_languages = DB::table('languages')->where('school_id', Auth::user()->school_id)->orderBy('code', 'ASC')->get();
            return view('backEnd.systemSettings.languageSettings', compact('sms_languages', 'all_languages'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function languageEdit($id)
    {

        try {
            $selected_languages = SmLanguage::find($id);
            $sms_languages      = SmLanguage::where('school_id', Auth::user()->school_id)->get();
            $all_languages      = DB::table('languages')->where('school_id', Auth::user()->school_id)->orderBy('code', 'ASC')->get();
            return view('backEnd.systemSettings.languageSettings', compact('sms_languages', 'all_languages', 'selected_languages'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function languageUpdate(Request $request)
    {

        try {
            $id               = $request->id;
            $language_id      = $request->language_id;
            $language_details = Language::find($language_id);

            if (!empty($language_id)) {
                $sms_languages                     = SmLanguage::find($id);
                $sms_languages->language_name      = $language_details->name != null ? $language_details->name : '';
                $sms_languages->language_universal = $language_details->code;
                $sms_languages->native             = $language_details->native;
                $sms_languages->lang_id            = $language_details->id;

                $results = $sms_languages->save();
                if ($results) {
                    Toastr::success('Operation successful', 'Success');
                    return redirect('language-settings');
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



    public function setEnvironmentValue()
    {

        try {
            $values['APP_LOCALE'] = 'en';
            $envFile = app()->environmentFilePath();
            $str = file_get_contents($envFile);
            if (count($values) > 0) {
                foreach ($values as $envKey => $envValue) {
                    $str .= "\n";
                    $keyPosition = strpos($str, "{$envKey}=");
                    $endOfLinePosition = strpos($str, "\n", $keyPosition);
                    $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);
                    if (!$keyPosition || !$endOfLinePosition || !$oldLine) {
                        $str .= "{$envKey}={$envValue}\n";
                    } else {
                        $str = str_replace($oldLine, "{$envKey}={$envValue}", $str);
                    }
                }
            }
            $str = substr($str, 0, -1);
            $res = file_put_contents($envFile, $str);
            return $res;
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }



    public function ajaxLanguageChange(Request $request)
    {
        

        try {
        //     $uni = $request->id;
        //     SmLanguage::where('active_status', 1)->where('school_id', Auth::user()->school_id)->update(['active_status' => 0]);

        //     $updateLang = SmLanguage::where('language_universal', $uni)->where('school_id', Auth::user()->school_id)->first();
        
        //     $updateLang->active_status = 1;
        //     $updateLang->update();
        //     $langs = SmLanguage::where('school_id', Auth::user()->school_id)->get();
        //     session()->put('systemLanguage',$langs);
        //    if($uni != 'en'){
        //     session()->put('lang', strtolower($uni));
        //    }
        //    App::setLocale($uni);



        // //    $values['APP_LOCALE'] = $updateLang->language_universal;
        // //     $envFile = app()->environmentFilePath();
        // //     $str = file_get_contents($envFile);
        // //     if (count($values) > 0) {
        // //         foreach ($values as $envKey => $envValue) {
        // //             $str .= "\n";
        // //             $keyPosition = strpos($str, "{$envKey}=");
        // //             $endOfLinePosition = strpos($str, "\n", $keyPosition);
        // //             $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);
        // //             if (!$keyPosition || !$endOfLinePosition || !$oldLine) {
        // //                 $str .= "{$envKey}={$envValue}\n";
        // //             } else {
        // //                 $str = str_replace($oldLine, "{$envKey}={$envValue}", $str);
        // //             }
        // //         }
        // //     }
        // //     $str = substr($str, 0, -1);
        // //     $res = file_put_contents($envFile, $str);
        //    return response()->json([$uni]);

            $uni = $request->id;
            SmLanguage::where('active_status', 1)->where('school_id', Auth::user()->school_id)->update(['active_status' => 0]);

            $updateLang = SmLanguage::where('language_universal', $uni)->where('school_id', Auth::user()->school_id)->first();
        
            $updateLang->active_status = 1;
            $updateLang->update();
            $langs = SmLanguage::where('school_id', Auth::user()->school_id)->get();
            session()->put('systemLanguage',$langs);

            $values['APP_LOCALE'] = $updateLang->language_universal;
            $envFile = app()->environmentFilePath();
            $str = file_get_contents($envFile);
            if (count($values) > 0) {
                foreach ($values as $envKey => $envValue) {
                    $str .= "\n";
                    $keyPosition = strpos($str, "{$envKey}=");
                    $endOfLinePosition = strpos($str, "\n", $keyPosition);
                    $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);
                    if (!$keyPosition || !$endOfLinePosition || !$oldLine) {
                        $str .= "{$envKey}={$envValue}\n";
                    } else {
                        $str = str_replace($oldLine, "{$envKey}={$envValue}", $str);
                    }
                }
            }
            $str = substr($str, 0, -1);
            $res = file_put_contents($envFile, $str);

            return response()->json([$updateLang]);
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function ajaxSubjectDropdown(Request $request)
    {

        try {
            $class_id = $request->class;
            $allSubjects = SmAssignSubject::where([['section_id', '=', $request->id], ['class_id', $class_id]])->get();

            $subjectsName = [];
            foreach ($allSubjects as $allSubject) {
                $subjectsName[] = SmSubject::find($allSubject->subject_id);
            }

            return response()->json([$subjectsName]);
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function languageAdd(Request $request)
    {

          
        $request->validate([
            
            'lang_id' => 'required|max:255',
        ]);

        try {
            $lang_id          = $request->lang_id;
            $language_details = DB::table('languages')->where('id', $lang_id)->first();

            if (!empty($language_details)) {
                $sms_languages                     = new SmLanguage();
                $sms_languages->language_name      = $language_details->name;
                $sms_languages->language_universal = $language_details->code;
                $sms_languages->native             = $language_details->native;
                $sms_languages->lang_id            = $language_details->id;
                $sms_languages->active_status      = '0';
                $sms_languages->school_id      = Auth::user()->school_id;
                $results = $sms_languages->save();
                if ($results) {
                    $systemLanguage = SmLanguage::where('school_id', Auth::user()->school_id)->get();
                    session()->forget('systemLanguage');
                    session()->put('systemLanguage',$systemLanguage);
                
                    if (Schema::hasColumn('sm_language_phrases', $language_details->code)) {
                        Toastr::success('Operation successful', 'Success');
                        return redirect('language-settings');
                    } else {
                        if (DB::statement('ALTER TABLE sm_language_phrases ADD ' . $language_details->code . ' text')) {
                            $column = $language_details->code;
                            $all_translation_terms = SmLanguagePhrase::all();
                            $jsonArr = [];
                            foreach ($all_translation_terms as $row) {
                                $lid          = $row->id;
                                $english_term = $row->en;
                                if (!empty($english_term)) {
                                    $update_translation_term                = SmLanguagePhrase::find($lid);
                                    $update_translation_term->$column       = $english_term;
                                    $update_translation_term->active_status = 1;
                                    $update_translation_term->save();
                                }
                            }

                            $path = base_path() . '/resources/lang/' . $language_details->code;
                           
                            if (!file_exists($path)) {
                                File::makeDirectory($path, $mode = 0777, true, true);

                                $getData = SmLanguagePhrase::where('active_status', 1)->pluck($language_details->code, 'default_phrases');
                                $jsonContent = json_encode($getData);
                                $file = base_path() . '/resources/lang/' . $language_details->code . '/'. $language_details->code. ".json";
                                File::put($file, $jsonContent);
                                
                                $name =  '/resources/lang/'. $language_details->code . '/'. $language_details->code. ".json";
                                $nameStr = '"' .$name.'"' ;
                                $newPath      = $path . 'lang.php';
                                $page_content = "<?php
                                                    \$jsonFile =  base_path() .". $nameStr . ";
                                                    \$array =  json_decode(file_get_contents(\$jsonFile), true);
                                                    return \$array;";

                        
                                if (!file_exists($newPath)) {
                                    File::put($path . '/lang.php', $page_content);
                                }
                            }
                            Toastr::success('Operation successful', 'Success');
                            return redirect('language-settings');
                        } else {
                            Toastr::error('Operation Failed', 'Failed');
                            return redirect()->back();
                        }
                    }
                } else {
                    Toastr::error('Operation Failed', 'Failed');
                    return redirect()->back();
                }
            } //not empty language
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }



    //backupSettings
    public function backupSettings()
    {

        try {
            $sms_dbs = SmBackup::where('academic_id', getAcademicId())->orderBy('id', 'DESC')->get();
            return view('backEnd.systemSettings.backupSettings', compact('sms_dbs'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function BackupStore(Request $request)
    {
        $request->validate([
            'content_file' => 'required|file',
        ]);

        try {
            if ($request->file('content_file') != "") {
                $file = $request->file('content_file');
                if ($file->getClientOriginalExtension() == 'sql') {
                    $file_name = 'Restore_' . date('d_m_Y_') . $file->getClientOriginalName();
                    $file->move('public/databaseBackup/', $file_name);
                    $content_file = 'public/databaseBackup/' . $file_name;
                } else {
                    Toastr::error('Operation Failed', 'Failed');
                    return redirect()->back();
                }
            }

            if (isset($content_file)) {
                $store                = new SmBackup();
                $store->file_name     = $file_name;
                $store->source_link   = $content_file;
                $store->active_status = 1;
                $store->created_by    = Auth::user()->id;
                $store->updated_by    = Auth::user()->id;
                $result               = $store->save();
            }
            if ($result) {
                Toastr::success('Operation successful', 'Success');
                return redirect()->back();
            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }

            $sms_dbs = SmBackup::orderBy('id', 'DESC')->get();
            return view('backEnd.systemSettings.backupSettings', compact('sms_dbs'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function languageSetup($language_universal)
    {
       

        try {
            $sms_languages = SmLanguagePhrase::where('active_status', 1)->get();
            $modules       = SmModule::all();
           

            return view('backEnd.systemSettings.languageSetup', compact('language_universal', 'sms_languages', 'modules'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function deleteDatabase($id)
    {

        try {
            $source_link = "";
            // $data        = SmBackup::find($id);
             if (checkAdmin()) {
                $data = SmBackup::find($id);
            }else{
                $data = SmBackup::where('id',$id)->where('school_id',Auth::user()->school_id)->first();
            }
            if (!empty($data)) {
                $source_link = $data->source_link;
                if (file_exists($source_link)) {
                    unlink($source_link);
                }
            }
            $result = SmBackup::where('id', $id)->delete();
            if ($result) {
                Toastr::success('Operation successful', 'Success');
                return redirect()->back();
            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    //download database from public/databaseBackup
    public function downloadDatabase($id)
    {

        try {
            $source_link = "";
            // $data        = SmBackup::where('id', $id)->first();
             if (checkAdmin()) {
                $data = SmBackup::find($id);
            }else{
                $data = SmBackup::where('id',$id)->where('school_id',Auth::user()->school_id)->first();
            }
            if (!empty($data)) {
                $source_link = $data->source_link;
                if (file_exists($source_link)) {
                    unlink($source_link);
                }
            }

            if (file_exists($source_link)) {
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="' . basename($source_link) . '"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($source_link));
                flush(); // Flush system output buffer
                readfile($source_link);
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    //restore database from public/databaseBackup
    public function restoreDatabase($id)
    {

        try {
            // $sm_db = SmBackup::where('id', $id)->first();
             if (checkAdmin()) {
                $sm_db = SmBackup::find($id);
            }else{
                $sm_db = SmBackup::where('id',$id)->where('school_id',Auth::user()->school_id)->first();
            }
            if (!empty($sm_db)) {
                $source_link = $sm_db->source_link;
            }

            $DB_HOST     = env("DB_HOST", "");
            $DB_DATABASE = env("DB_DATABASE", "");
            $DB_USERNAME = env("DB_USERNAME", "");
            $DB_PASSWORD = env("DB_PASSWORD", "");

            $connection = mysqli_connect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE);

            if (!file_exists($source_link)) {
                Toastr::error('File not found', 'Failed');
                return redirect()->back();
            }
            $handle   = fopen($source_link, "r+");
            $contents = fread($handle, filesize($source_link));
            $sql      = explode(';', $contents);
            $flag     = 0;
            foreach ($sql as $query) {
                $result = mysqli_query($connection, $query);
                if ($result) {
                    $flag = 1;
                }
            }
            fclose($handle);

            if ($flag) {
                Toastr::success('Operation successful', 'Success');
                return redirect()->back();
            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    //get files Backup #file
    public function getfilesBackup($id)
    {
        set_time_limit(1600);

        try{

            if ($id == 1) {
                $path             = base_path() . '/public/uploads';
                $zip_file = 'Backup_' . date('d_m_Y').'_'.time() . '_Images.zip';
            } else if ($id == 2) {
                $path = base_path() . '';
                $zip_file = 'Backup_' . date('d_m_Y').'_'.time() . '_Projects.zip';
            }

            if ($id == 1) {
                $folder = public_path() . '/Backup/ImageBackup/';
                if (!file_exists($folder)) {
                    File::makeDirectory($folder, $mode = 0777, true, true);
                }
                $temp = $folder.$zip_file;
            } else {
                $folder = public_path() . '/Backup/ProjectBackup/';
                if (!file_exists($folder)) {
                    File::makeDirectory($folder, $mode = 0777, true, true);
                }
                $temp = $folder.$zip_file;
            }

            // $zip_file = 'Backup_' . date('d_m_Y').'_'.time() . '_Images.zip';
            $zip = new \ZipArchive();
            $zip->open($temp, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

            // $path = public_path('uploads');
            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));
            // dd($files);
            
            
            foreach ($files as $name => $file)
            {
                // We're skipping all subfolders
                if (!$file->isDir()) {
                    $filePath     = $file->getRealPath();

                    // extracting filename with substr/strlen
                    $relativePath = $zip_file . substr($filePath, strlen($path) + 1);
                    // dd($relativePath);
                    $zip->addFile($filePath, $relativePath);
                }
            }
            $zip->close();
            $store                = new SmBackup();
            $store->file_name     = $zip_file;
            $store->source_link   = $temp;
            $store->active_status = 1;
            $store->file_type     = $id;
            $store->created_by    = Auth::user()->id;
            $store->updated_by    = Auth::user()->id;
            $store->academic_id = getAcademicId();
            $result               = $store->save();
            if ($id == 2) {
                return response()->download($zip_file);
            }
            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
            

        } catch (\Exception $e) {
            // dd($e);
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    
    // download Files #file
    public function downloadFiles($id)
    {


        try {
            // $sm_db       = SmBackup::where('id', $id)->first();
             if (checkAdmin()) {
                $sm_db = SmBackup::find($id);
            }else{
                $sm_db = SmBackup::where('id',$id)->where('school_id',Auth::user()->school_id)->first();
            }
            $source_link = $sm_db->source_link;
            if (@file_exists(@$source_link)) {
                return response()->download($source_link);
            } else {
                Toastr::error('File not found', 'Failed');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            // dd($e);
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function getDatabaseBackup()
    {

        try {
            $DB_HOST     = env("DB_HOST", "");
            $DB_DATABASE = env("DB_DATABASE", "");
            $DB_USERNAME = env("DB_USERNAME", "");
            $DB_PASSWORD = env("DB_PASSWORD", "");
            $connection  = mysqli_connect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE);

            $tables = array();
            $result = mysqli_query($connection, "SHOW TABLES");
            while ($row = mysqli_fetch_row($result)) {
                $tables[] = $row[0];
            }
            $return = '';

            // dd($tables);

            foreach ($tables as $table) {
                $result     = mysqli_query($connection, "SELECT * FROM " . $table);
                $num_fields = mysqli_num_fields($result);

                $return .= 'DROP TABLE ' . $table . ';';
                $row2 = mysqli_fetch_row(mysqli_query($connection, "SHOW CREATE TABLE " . $table));
                $return .= "\n\n" . $row2[1] . ";\n\n";

                for ($i = 0; $i < $num_fields; $i++) {
                    while ($row = mysqli_fetch_row($result)) {
                        $return .= "INSERT INTO " . $table . " VALUES(";
                        for ($j = 0; $j < $num_fields; $j++) {
                            $row[$j] = addslashes($row[$j]);
                            if (isset($row[$j])) {
                                $return .= '"' . $row[$j] . '"';
                            } else {
                                $return .= '""';
                            }
                            if ($j < $num_fields - 1) {
                                $return .= ',';
                            }
                        }
                        $return .= ");\n";
                    }
                }
                $return .= "\n\n\n";
            }

            if (!file_exists('public/databaseBackup')) {
                mkdir('public/databaseBackup', 0777, true);
            }
            //save file
            $name   = 'database_backup_'. date('d_m_Y').'_'.time().'.sql';
            $path   = 'public/databaseBackup/' . $name;
            $handle = fopen($path, "w+");
            fwrite($handle, $return);
            fclose($handle);

            $get_backup                = new SmBackup();
            $get_backup->file_name     = $name;
            $get_backup->source_link   = $path;
            $get_backup->active_status = 1;
            $get_backup->file_type     = 0;
            $get_backup->academic_id = getAcademicId();
            $results                   = $get_backup->save();

            // $sms_dbs = SmBackup::orderBy('id', 'DESC')->get();
            // return view('backEnd.systemSettings.backupSettings', compact('sms_dbs'));

            if ($results) {
                Toastr::success('Operation successful', 'Success');
                return redirect()->back();
            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        } catch (\Exception $e) {
           
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function updateClickatellData()
    {

        try {
            $gateway_id          = $_POST['gateway_id'];
            $clickatell_username = $_POST['clickatell_username'];
            $clickatell_password = $_POST['clickatell_password'];
            $clickatell_api_id   = $_POST['clickatell_api_id'];

            if ($gateway_id) {
                $gatewayDetails = SmSmsGateway::where('id', $gateway_id)->first();
                if (!empty($gatewayDetails)) {

                    $gatewayDetailss                      = SmSmsGateway::find($gatewayDetails->id);
                    $gatewayDetailss->clickatell_username = $clickatell_username;
                    $gatewayDetailss->clickatell_password = $clickatell_password;
                    $gatewayDetailss->clickatell_api_id   = $clickatell_api_id;
                    $results                              = $gatewayDetailss->update();
                } else {

                    $gatewayDetail                      = new SmSmsGateway();
                    $gatewayDetail->clickatell_username = $clickatell_username;
                    $gatewayDetail->clickatell_password = $clickatell_password;
                    $gatewayDetail->clickatell_api_id   = $clickatell_api_id;
                    $results                            = $gatewayDetail->save();
                }
            }

            if ($results) {
                echo "success";
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function updateTwilioData()
    {


        try {
            $gateway_id                  = $_POST['gateway_id'];
            $twilio_account_sid          = $_POST['twilio_account_sid'];
            $twilio_authentication_token = $_POST['twilio_authentication_token'];
            $twilio_registered_no        = $_POST['twilio_registered_no'];

            if ($gateway_id) {
                $gatewayDetails = SmSmsGateway::where('id', $gateway_id)->first();
                if (!empty($gatewayDetails)) {

                    $gatewayDetailss                              = SmSmsGateway::find($gatewayDetails->id);
                    $gatewayDetailss->twilio_account_sid          = $twilio_account_sid;
                    $gatewayDetailss->twilio_authentication_token = $twilio_authentication_token;
                    $gatewayDetailss->twilio_registered_no        = $twilio_registered_no;
                    $results                                      = $gatewayDetailss->update();
                } else {

                    $gatewayDetail                              = new SmSmsGateway();
                    $gatewayDetail->twilio_account_sid          = $twilio_account_sid;
                    $gatewayDetail->twilio_authentication_token = $twilio_authentication_token;
                    $gatewayDetail->twilio_registered_no        = $twilio_registered_no;
                    $results                                    = $gatewayDetail->save();
                }
            }

            if ($results) {
                echo "success";
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function updateTextlocalData( Request $request)
    {
        // dd("yes");
        
        $validator = Validator::make($request->all(), [
            'textlocal_username' => 'required',
            'textlocal_hash' => 'required',
            'textlocal_sender' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with(['textlocal_settings' => 'active']);
        }

        try {
            $gateway_id                  = $_POST['gateway_id'];
            $textlocal_username          = $_POST['textlocal_username'];
            $textlocal_hash              = $_POST['textlocal_hash'];
            $textlocal_sender            = $_POST['textlocal_sender'];

            if ($gateway_id) {
                $gatewayDetails = SmSmsGateway::where('id', $gateway_id)->first();
                if (!empty($gatewayDetails)) {

                    $gatewayDetailss                              = SmSmsGateway::find($gatewayDetails->id);
                    $gatewayDetailss->textlocal_username          = $textlocal_username;
                    $gatewayDetailss->textlocal_hash = $textlocal_hash;
                    $gatewayDetailss->textlocal_sender        = $textlocal_sender;
                    $results                                      = $gatewayDetailss->update();
                } else {

                    $gatewayDetail                              = new SmSmsGateway();
                    $gatewayDetail->textlocal_username          = $textlocal_username;
                    $gatewayDetail->textlocal_hash =     $textlocal_hash;
                    $gatewayDetail->textlocal_sender        = $textlocal_sender;
                    $results                                    = $gatewayDetail->save();
                }
            }

            Toastr::success('Operation successful', 'Success');
            return redirect()->back()->with(['textlocal_settings' => 'active']);
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back()->with(['textlocal_settings' => 'active']);
        }
    }

    public function updateAfricaTalkingData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'africatalking_username' => 'required',
            'africatalking_api_key' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with(['africatalking_settings' => 'active']);
        }
        
        try {
            $gateway_id                  = $_POST['gateway_id'];
            $africatalking_username          = $_POST['africatalking_username'];
            $africatalking_api_key          = $_POST['africatalking_api_key'];

            if ($gateway_id) {
                $gatewayDetails = SmSmsGateway::where('id', $gateway_id)->first();
                if (!empty($gatewayDetails)) {

                    $gatewayDetailss                              = SmSmsGateway::find($gatewayDetails->id);
                    $gatewayDetailss->africatalking_username          = $africatalking_username;
                    $gatewayDetailss->africatalking_api_key          = $africatalking_api_key;
                    $results                                      = $gatewayDetailss->update();
                } else {

                    $gatewayDetail                              = new SmSmsGateway();
                    $gatewayDetail->africatalking_username          = $africatalking_username;
                    $gatewayDetail->africatalking_api_key          = $africatalking_api_key;
                    $results                                    = $gatewayDetail->save();
                }
            }

            Toastr::success('Operation successful', 'Success');
            return redirect()->back()->with(['africatalking_settings' => 'active']);
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back()->with(['africatalking_settings' => 'active']);
        }
    }

    public function updateMsg91Data(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'msg91_authentication_key_sid' => 'required',
            'msg91_route' => 'required',
            'msg91_country_code' => 'required',
            'msg91_sender_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with(['msg91_settings' => 'active']);
        }
        
        


        try {
            $gateway_id                   = $request->gateway_id;
            $msg91_authentication_key_sid = $request->msg91_authentication_key_sid;
            $msg91_route                  = $request->msg91_route;
            $msg91_country_code           = $request->msg91_country_code;
            $msg91_sender_id              = $request->msg91_sender_id;

            $key1 = 'MSG91_KEY';
            $key2 = 'MSG91_SENDER_ID';
            $key3 = 'MSG91_COUNTRY';
            $key4 = 'MSG91_ROUTE';

            $value1 = $msg91_authentication_key_sid;
            $value2 = $msg91_sender_id;
            $value3 = $msg91_country_code;
            $value4 = $msg91_route;

            $path            = base_path() . "/.env";
            $MSG91_KEY       = env($key1);
            $MSG91_SENDER_ID = env($key2);
            $MSG91_COUNTRY   = env($key3);
            $MSG91_ROUTE     = env($key4);

            if (file_exists($path)) {
                file_put_contents($path, str_replace(
                    "$key1=" . $MSG91_KEY,
                    "$key1=" . $value1,
                    file_get_contents($path)
                ));
                file_put_contents($path, str_replace(
                    "$key2=" . $MSG91_SENDER_ID,
                    "$key2=" . $value2,
                    file_get_contents($path)
                ));
                file_put_contents($path, str_replace(
                    "$key3=" . $MSG91_COUNTRY,
                    "$key3=" . $value3,
                    file_get_contents($path)
                ));
                file_put_contents($path, str_replace(
                    "$key4=" . $MSG91_ROUTE,
                    "$key4=" . $value4,
                    file_get_contents($path)
                ));
            }

            if ($gateway_id) {
                $gatewayDetails = SmSmsGateway::where('id', $gateway_id)->first();
                if (!empty($gatewayDetails)) {

                    $gatewayDetailss                               = SmSmsGateway::find($gatewayDetails->id);
                    $gatewayDetailss->msg91_authentication_key_sid = $msg91_authentication_key_sid;
                    $gatewayDetailss->msg91_sender_id              = $msg91_sender_id;
                    $gatewayDetailss->msg91_route                  = $msg91_route;
                    $gatewayDetailss->msg91_country_code           = $msg91_country_code;
                    $results                                       = $gatewayDetailss->update();
                } else {

                    $gatewayDetail = new SmSmsGateway();

                    $gatewayDetail->msg91_authentication_key_sid = $msg91_authentication_key_sid;
                    $gatewayDetail->msg91_sender_id              = $msg91_sender_id;
                    $gatewayDetail->msg91_route                  = $msg91_route;
                    $gatewayDetail->msg91_country_code           = $msg91_country_code;

                    $results = $gatewayDetail->save();
                }
            }

            if ($results) {
                Toastr::success('Operation successful', 'Success');
                return redirect()->back()->with(['msg91_settings' => 'active']);
            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back()->with(['msg91_settings' => 'active']);
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back()->with(['msg91_settings' => 'active']);
        }
    }

    public function activeSmsService()
    {

        try {
            $sms_service = $_POST['sms_service'];

            if ($sms_service) {
                $gatewayDetailss = SmSmsGateway::where('active_status', '=', 1)
                    ->update(['active_status' => 0]);
            }

            $gatewayDetails                = SmSmsGateway::find($sms_service);
            $gatewayDetails->active_status = 1;
            $results                       = $gatewayDetails->update();

            if ($results) {
                echo "success";
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function generalSettingsView(Request $request)
    {
        try {
             $editData = SmGeneralSettings::where('school_id', Auth::user()->school_id)->first();
           
            $session = SmGeneralSettings::join('sm_academic_years', 'sm_academic_years.id', '=', 'sm_general_settings.session_id')->find(1);
            // return $editData;
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {

                return ApiBaseMethod::sendResponse($editData, null);
            }
            return view('backEnd.systemSettings.generalSettingsView', compact('editData', 'session'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function updateGeneralSettings(Request $request)
    {

        try {

            $editData        = SmGeneralSettings::where('school_id', Auth::user()->school_id)->first();
            $session_ids     = SmAcademicYear::where('school_id', Auth::user()->school_id)->where('active_status', 1)->get();
            $dateFormats     = SmDateFormat::where('active_status', 1)->get();
            $languages       = SmLanguage::all();
            $countries       = SmCountry::select('currency')->groupBy('currency')->get();
            $currencies      = SmCurrency::all();
            $academic_years  = SmAcademicYear::where('school_id', Auth::user()->school_id)->get();
            $time_zones      = SmTimeZone::all();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data                = [];
                $data['editData']    = $editData;
                $data['session_ids'] = $session_ids->toArray();
                $data['dateFormats'] = $dateFormats->toArray();
                $data['languages']   = $languages->toArray();
                $data['countries']   = $countries->toArray();
                $data['currencies']  = $currencies->toArray();
                $data['academic_years']  = $academic_years->toArray();
                return ApiBaseMethod::sendResponse($data, 'apply leave');
            }
            return view('backEnd.systemSettings.updateGeneralSettings', compact('editData', 'session_ids', 'dateFormats', 'languages', 'countries', 'currencies', 'academic_years', 'time_zones'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function updateGeneralSettingsData(Request $request)
    {
        // return $request;
        
        $input = $request->all();
        

        $validator = Validator::make($input, [
            'school_name'     => "required",
            'site_title'      => "required",
            'phone'           => "required",
            'email'           => "required",
            'session_id'      => "required",
            'language_id'     => "required",
            'date_format_id'  => "required",
            'currency'        => "required",
            'currency_symbol' => "required",
            'school_code'     => "required",
            'time_zone'       => "required",
            'promotionSetting' => "required",
            'file_size' => "required",

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
            $id                               = Auth::user()->school_id;
            $generalSettData                  = SmGeneralSettings::where('school_id', $id)->first();
            $generalSettData->school_name     = $request->school_name;
            $generalSettData->site_title      = $request->site_title;
            $generalSettData->school_code     = $request->school_code;
            $generalSettData->address         = $request->address;
            $generalSettData->phone           = $request->phone;
            $generalSettData->email           = $request->email;
            $generalSettData->session_id      = $request->session_id;
            $generalSettData->academic_id      = $request->session_id;
            $generalSettData->language_id     = $request->language_id;
            $generalSettData->date_format_id  = $request->date_format_id;
            $generalSettData->currency        = $request->currency;
            $generalSettData->currency_symbol = $request->currency_symbol;
            $generalSettData->promotionSetting = $request->promotionSetting;
            $generalSettData->time_zone_id = $request->time_zone;
            $generalSettData->file_size = $request->file_size;
            $generalSettData->copyright_text = $request->copyright_text;
            $results = $generalSettData->save();

            if($results){
                session()->forget('generalSetting');
                session()->forget('sessionId');
                session()->put('sessionId', $request->session_id);
                session()->put('generalSetting', $generalSettData);
            }
          
            


            $school = SmSchool::find(Auth::user()->school_id);
            $school->school_name = $request->school_name;
            $school->school_code = $request->school_code;
            $school->address = $request->address;
            $school->phone = $request->phone;
            $school->email = $request->email;
            $school->save();

            if ($generalSettData->timeZone != "") {
                $value1 = $generalSettData->timeZone->time_zone;



                $key1 = 'APP_TIMEZONE';

                $path            = base_path() . "/.env";
                $APP_TIMEZONE       = env($key1);

                if (file_exists($path)) {
                    file_put_contents($path, str_replace(
                        "$key1=" . $APP_TIMEZONE,
                        "$key1=" . $value1,
                        file_get_contents($path)
                    ));
                }
            }

            $get_all_school_settings = SmGeneralSettings::get();

            foreach ($get_all_school_settings as $key => $school_setting) {
                $school_setup                  = SmGeneralSettings::find($school_setting->id);
                $school_setup->language_id     = $request->language_id;
                $school_setup->date_format_id  = $request->date_format_id;
                $school_setup->currency        = $request->currency;
                $school_setup->currency_symbol = $request->currency_symbol;
                $school_setup->time_zone_id = $request->time_zone;
                $school_setup->copyright_text = $request->copyright_text;
                $results = $school_setup->save();
            }
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($results) {
                    return ApiBaseMethod::sendResponse(null, 'General Settings has been updated successfully');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again');
                }
            } else {
                if ($results) {
                    Toastr::success('Operation successful', 'Success');
                    return redirect('general-settings');
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

    public function updateSchoolLogo(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'main_school_logo' => "sometimes|nullable|mimes:jpg,jpeg,png|max:50000",
            'main_school_favicon' => "sometimes|nullable|mimes:jpg,jpeg,png|max:50000",
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
            // for upload School Logo
            if ($request->file('main_school_logo') != "") {
                $maxFileSize = SmGeneralSettings::first('file_size')->file_size;
                $file = $request->file('main_school_logo');
                $fileSize =  filesize($file);
                $fileSizeKb = ($fileSize / 1000000);
                if($fileSizeKb >= $maxFileSize){
                    Toastr::error( 'Max upload file size '. $maxFileSize .' Mb is set in system', 'Failed');
                    return redirect()->back();
                }
                $main_school_logo = "";
                $file             = $request->file('main_school_logo');
                $main_school_logo = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                $file->move('public/uploads/settings/', $main_school_logo);
                $main_school_logo      = 'public/uploads/settings/' . $main_school_logo;
                $generalSettData       = SmGeneralSettings::where('school_id',Auth::user()->school_id)->first();
                $generalSettData->logo = $main_school_logo;
                $results               = $generalSettData->update();

                if($results){
                    session()->forget('school_config');
                    $school_config = SmGeneralSettings::where('school_id', Auth::user()->school_id)->first();
                    session()->put('school_config', $school_config);

                    session()->forget('generalSetting');
                    session()->put('generalSetting', $generalSettData);
                }
            }
            // for upload School favicon
            else if ($request->file('main_school_favicon') != "") {
                $maxFileSize = SmGeneralSettings::first('file_size')->file_size;
                $file = $request->file('main_school_favicon');
                $fileSize =  filesize($file);
                $fileSizeKb = ($fileSize / 1000000);
                if($fileSizeKb >= $maxFileSize){
                    Toastr::error( 'Max upload file size '. $maxFileSize .' Mb is set in system', 'Failed');
                    return redirect()->back();
                }
                $main_school_favicon = "";
                $file                = $request->file('main_school_favicon');
                $main_school_favicon = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                $file->move('public/uploads/settings/', $main_school_favicon);
                $main_school_favicon      = 'public/uploads/settings/' . $main_school_favicon;
                $generalSettData          = SmGeneralSettings::where('school_id',Auth::user()->school_id)->first();
                $generalSettData->favicon = $main_school_favicon;
                $results                  = $generalSettData->update();

                if($results){
                    session()->forget('school_config');
                    $school_config = SmGeneralSettings::where('school_id', Auth::user()->school_id)->first();
                    session()->put('school_config', $school_config);

                    session()->forget('generalSetting');
                    session()->put('generalSetting', $generalSettData);
                }
                

            } else {
                if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                    return ApiBaseMethod::sendError('No change applied, please try again');
                }
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
            if ($results) {
                if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                    return ApiBaseMethod::sendResponse(null, 'Logo has been updated successfully');
                }
                Toastr::success('Operation successful', 'Success');
                return redirect()->back();
            } else {
                if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                    return ApiBaseMethod::sendError('Something went wrong, please try again');
                }
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function emailSettings()
    {

        try {
            $editData = SmEmailSetting::first();
            $active_mail_driver = SmGeneralSettings::first('email_driver')->email_driver;
            return view('backEnd.systemSettings.emailSettingsView', compact('editData','active_mail_driver'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function updateEmailSettingsData(Request $request)
    {
      

        if($request->engine_type == "php"){
            $request->validate([
                'from_name'         => "required",
                'from_email'        => "required|email",
            ]);
        }
        if($request->engine_type == "smtp"){
            
            $request->validate([
                'from_name'         => "required",
                'from_email'        => "required|email",
                'mail_password'     => "required",
                'mail_encryption'    => "required",
            ]);

            if (
                $request->mail_username == ''
                || $request->mail_password == ''
                || $request->mail_encryption == ''
                || $request->mail_port == ''
                || $request->mail_host == '' || $request->mail_driver == ''
            ) {
                Toastr::error('All Field in Smtp Details Must Be filled Up', 'Failed');
                return redirect()->back();
            }
        }

        


        

        try {
            if($request->engine_type == "smtp"){
                

            $key1 = 'MAIL_USERNAME';
            $key2 = 'MAIL_PASSWORD';
            $key3 = 'MAIL_ENCRYPTION';
            $key4 = 'MAIL_PORT';
            $key5 = 'MAIL_HOST';
            $key6 = 'MAIL_MAILER';
            $key7 = 'MAIL_FROM_ADDRESS';

            $value1 = str_replace(" ", "", $request->mail_username);
            $value2 = str_replace(" ", "", $request->mail_password);
            $value3 = str_replace(" ", "", $request->mail_encryption);
            $value4 = str_replace(" ", "", $request->mail_port);
            $value5 = str_replace(" ", "", $request->mail_host);
            $value6 = str_replace(" ", "", $request->mail_driver);
            $value7 = str_replace(" ", "", $request->from_email);

            $path                   = base_path() . "/.env";
            $MAIL_USERNAME          = env($key1);
            $MAIL_PASSWORD          = env($key2);
            $MAIL_ENCRYPTION        = env($key3);
            $MAIL_PORT              = env($key4);
            $MAIL_HOST              = env($key5);
            $MAIL_DRIVER            = env($key6);
            $FROM_MAIL              = env($key7);

            if (file_exists($path)) {
                file_put_contents($path, str_replace(
                    "$key1=" . $MAIL_USERNAME,
                    "$key1=" . $value1,
                    file_get_contents($path)
                ));
                file_put_contents($path, str_replace(
                    "$key2=" . $MAIL_PASSWORD,
                    "$key2=" . $value2,
                    file_get_contents($path)
                ));
                file_put_contents($path, str_replace(
                    "$key3=" . $MAIL_ENCRYPTION,
                    "$key3=" . $value3,
                    file_get_contents($path)
                ));
                file_put_contents($path, str_replace(
                    "$key4=" . $MAIL_PORT,
                    "$key4=" . $value4,
                    file_get_contents($path)
                ));
                file_put_contents($path, str_replace(
                    "$key5=" . $MAIL_HOST,
                    "$key5=" . $value5,
                    file_get_contents($path)
                ));
                file_put_contents($path, str_replace(
                    "$key6=" . $MAIL_DRIVER,
                    "$key6=" . $value6,
                    file_get_contents($path)
                ));
                file_put_contents($path, str_replace(
                    "$key7=" . $FROM_MAIL,
                    "$key7=" . $value7,
                    file_get_contents($path)
                ));
            }
            

            $e = SmEmailSetting::where('email_engine_type','smtp')->where('school_id', Auth::user()->school_id)->first();
            
            if (empty($e)) {
                $e = new SmEmailSetting();
            }
            $e->from_name         = $request->from_name;
            $e->from_email        = $request->from_email;
            $e->mail_driver     = $request->mail_driver;
            $e->mail_host     = $request->mail_host;
            $e->mail_port       = $request->mail_port;
            $e->mail_username         = $request->mail_username;
            $e->mail_password     = $request->mail_password;
            $e->mail_encryption     = $request->mail_encryption;
            $e->active_status = $request->active_status; 
            $e->school_id     = Auth::user()->school_id;
            $results = $e->save();

            if($request->active_status == 1){
                
                $gs = SmGeneralSettings::first();
                
                $gs->email_driver = "smtp";
                $gs->save();
                $phpp = SmEmailSetting::where('email_engine_type','php')->where('school_id', Auth::user()->school_id)->first();
                
                $phpp->active_status = 0 ;
                $phpp->save();
            }
        }

        if($request->engine_type == "php"){

            $php = SmEmailSetting::where('email_engine_type','php')->where('school_id', Auth::user()->school_id)->first();

            if (empty($php)) {
                $php = new SmEmailSetting();
            }
            $php->from_name         = $request->from_name;
            $php->from_email        = $request->from_email;
            $php->school_id     = Auth::user()->school_id;
            $php->active_status = $request->active_status; 
            $results = $php->save();

            if($request->active_status == 1){
                $gs = SmGeneralSettings::first();
                $gs->email_driver = "php";
                $gs->save();
                $smtp = SmEmailSetting::where('email_engine_type','smtp')->where('school_id', Auth::user()->school_id)->first();
                $smtp->active_status = 0 ;
                $smtp->save();
            }

        }


            //========================

            try {
                
                $settings = SmEmailSetting::first();
                $reciver_email = $settings->from_email;
                $receiver_name =  Auth::user()->full_name;
                $subject = 'Email Setup Testing';
                $view ="test_email";
                $compact['data'] =  array('email' => $settings->from_email,'name' => Auth::user()->full_name); 
                @send_mail($reciver_email, $receiver_name, $subject , $view ,$compact);

                
            } catch (\Exception $e) {
                Toastr::error('Email credentials maybe wrong !', 'Failed');
                return redirect()->back();
            }


            //========================
            if ($results) {
                Toastr::success('Operation successful', 'Success');
                return redirect()->back();
            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed,' . $e->getMessage(), 'Failed');
            return redirect()->back();
        }
    }

    public function paymentMethodSettings()
    {
        
        try {
            $statement                = "SELECT P.id as PID, D.id as DID, P.active_status as IsActive, P.method, D.* FROM sm_payment_methhods as P, sm_payment_gateway_settings D WHERE P.gateway_id=D.id";

            $PaymentMethods           = DB::select($statement);
            $paymeny_gateway          = SmPaymentMethhod::where('school_id', Auth::user()->school_id)->get();
            $paymeny_gateway_settings = SmPaymentGatewaySetting::where('school_id', Auth::user()->school_id)->get();
            $payment_methods = SmPaymentMethhod::where('school_id', Auth::user()->school_id)->get();
            return view('backEnd.systemSettings.paymentMethodSettings', compact('PaymentMethods', 'paymeny_gateway', 'paymeny_gateway_settings','payment_methods'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function updatePaymentGateway(Request $request)
    {
        
        // $validator = Validator::make($request->all(), [
        //     'msg91_authentication_key_sid' => 'required',
        //     'msg91_route' => 'required',
        //     'msg91_country_code' => 'required',
        //     'msg91_sender_id' => 'required',
        // ]);

        // if ($validator->fails()) {
        //     return redirect()->back()
        //         ->withErrors($validator)
        //         ->withInput()
        //         ->with(['msg91_settings' => 'active']);
        // }

        try {
            $paymeny_gateway = [
                'gateway_name', 'gateway_username', 'gateway_password', 'gateway_signature', 'gateway_client_id', 'gateway_mode',
                'gateway_secret_key', 'gateway_secret_word', 'gateway_publisher_key', 'gateway_private_key', 'cheque_details', 'bank_details'
            ];
            $count          = 0;
            $gatewayDetails = SmPaymentGatewaySetting::where('gateway_name', $request->gateway_name)->where('school_id', Auth::user()->school_id)->first();

            foreach ($paymeny_gateway as $input_field) {
                if (isset($request->$input_field) && !empty($request->$input_field)) {
                    $gatewayDetails->$input_field = $request->$input_field;
                }
            }
            $results = $gatewayDetails->save();


            if ($results) {
                Toastr::success('Operation successful', 'Success');
                return redirect()->back()->with([$request->gateway_name => 'active']);
            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back()->with([$request->gateway_name => 'active']);
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back()->with([$request->gateway_name => 'active']);
        }
    }

    public function isActivePayment(Request $request)
    {

        $request->validate(
            [
                'gateways' => 'required|array',
            ],
            [
                'gateways.required' => 'At least one gateway required!',
            ]
        );


        try {
            $update = SmPaymentMethhod::where('school_id', Auth::user()->school_id)->where('active_status', '=', 1)->update(['active_status' => 0]);

            foreach ($request->gateways as $pid => $isChecked) {
                $results = SmPaymentMethhod::where('school_id', Auth::user()->school_id)->where('id', '=', $pid)->update(['active_status' => 1]);
            }

            if ($results) {
                Toastr::success('Operation successful', 'Success');
                return redirect()->back();
            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function updatePaypalData()
    {

        try {
            $gateway_id       = $_POST['gateway_id'];
            $paypal_username  = $_POST['paypal_username'];
            $paypal_password  = $_POST['paypal_password'];
            $paypal_signature = $_POST['paypal_signature'];
            $paypal_client_id = $_POST['paypal_client_id'];
            $paypal_secret_id = $_POST['paypal_secret_id'];

            if ($gateway_id) {
                $gatewayDetails = SmPaymentGatewaySetting::where('id', $gateway_id)->first();
                if (!empty($gatewayDetails)) {

                    $gatewayDetailss                   = SmPaymentGatewaySetting::find($gatewayDetails->id);
                    $gatewayDetailss->paypal_username  = $paypal_username;
                    $gatewayDetailss->paypal_password  = $paypal_password;
                    $gatewayDetailss->paypal_signature = $paypal_signature;
                    $gatewayDetailss->paypal_client_id = $paypal_client_id;
                    $gatewayDetailss->paypal_secret_id = $paypal_secret_id;
                    $results                           = $gatewayDetailss->update();
                } else {

                    $gatewayDetail                   = new SmPaymentGatewaySetting();
                    $gatewayDetail->paypal_username  = $paypal_username;
                    $gatewayDetail->paypal_password  = $paypal_password;
                    $gatewayDetail->paypal_signature = $paypal_signature;
                    $gatewayDetail->paypal_client_id = $paypal_client_id;
                    $gatewayDetail->paypal_secret_id = $paypal_secret_id;
                    $results                         = $gatewayDetail->save();
                }
            }

            if ($results) {
                Toastr::success('Operation successful', 'Success');
                return redirect()->back();
            } else {

                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function updateStripeData()
    {
        return $request;

        try {
            $gateway_id            = $_POST['gateway_id'];
            $stripe_api_secret_key = $_POST['stripe_api_secret_key'];
            $stripe_publisher_key  = $_POST['stripe_publisher_key'];

            if ($gateway_id) {
                $gatewayDetails = SmPaymentGatewaySetting::where('id', $gateway_id)->where('school_id', Auth::user()->school_id)->first();
                if (!empty($gatewayDetails)) {

                    $gatewayDetailss                        = SmPaymentGatewaySetting::find($gatewayDetails->id);
                    $gatewayDetailss->stripe_api_secret_key = $stripe_api_secret_key;
                    $gatewayDetailss->stripe_publisher_key  = $stripe_publisher_key;
                    $results                                = $gatewayDetailss->update();
                } else {

                    $gatewayDetail                        = new SmPaymentGatewaySetting();
                    $gatewayDetail->stripe_api_secret_key = $stripe_api_secret_key;
                    $gatewayDetail->stripe_publisher_key  = $stripe_publisher_key;
                    $results                              = $gatewayDetail->save();
                }
            }

            if ($results) {
                echo "success";
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function updatePayumoneyData()
    {

        try {
            $gateway_id       = $_POST['gateway_id'];
            $pay_u_money_key  = $_POST['pay_u_money_key'];
            $pay_u_money_salt = $_POST['pay_u_money_salt'];

            if ($gateway_id) {
                $gatewayDetails = SmPaymentGatewaySetting::where('id', $gateway_id)->first();
                if (!empty($gatewayDetails)) {

                    $gatewayDetailss                   = SmPaymentGatewaySetting::find($gatewayDetails->id);
                    $gatewayDetailss->pay_u_money_key  = $pay_u_money_key;
                    $gatewayDetailss->pay_u_money_salt = $pay_u_money_salt;
                    $results                           = $gatewayDetailss->update();
                } else {

                    $gatewayDetail                   = new SmPaymentGatewaySetting();
                    $gatewayDetail->pay_u_money_key  = $pay_u_money_key;
                    $gatewayDetail->pay_u_money_salt = $pay_u_money_salt;
                    $results                         = $gatewayDetail->save();
                }
            }

            if ($results) {
                echo "success";
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function activePaymentGateway()
    {

        try {
            $gateway_id = $_POST['gateway_id'];

            if ($gateway_id) {
                $gatewayDetailss = SmPaymentGatewaySetting::where('active_status', '=', 1)
                    ->update(['active_status' => 0]);
            }

            $results = SmPaymentGatewaySetting::where('gateway_name', '=', $gateway_id)
                ->update(['active_status' => 1]);

            if ($results) {
                echo "success";
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function languageDelete(Request $request)
    {

        $delete_directory = SmLanguage::find($request->id);

        DB::beginTransaction();

        try {

            if (DB::statement('ALTER TABLE sm_language_phrases DROP COLUMN ' . $delete_directory->language_universal)) {
                if ($delete_directory) {
                    $path = base_path() . '/resources/lang/' . $delete_directory->language_universal;
                    if (file_exists($path)) {
                        File::deleteDirectory($path);
                    }
                    $result = SmLanguage::destroy($request->id);
                    if ($result) {
                        $systemLanguage = SmLanguage::where('school_id', Auth::user()->school_id)->get();
                        session()->forget('systemLanguage');
                        session()->put('systemLanguage',$systemLanguage);

                        Toastr::success('Operation successful', 'Success');
                        return redirect()->back();
                    }
                } else {
                    Toastr::error('Operation Failed', 'Failed');
                    return redirect()->back();
                }
            } //end drop table column

            DB::commit();
            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function changeLocale($locale)
    {

        try {
            Session::put('locale', $locale);
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function changeLanguage($id)
    {

        try {
            SmLanguage::where('active_status', '=', 1)->where('school_id', Auth::user()->school_id)->update(['active_status' => 0]);
            $language                = SmLanguage::find($id);
            $language->active_status = 1;
            $result = $language->save();

           if ($result) {
            $systemLanguage = SmLanguage::where('school_id', Auth::user()->school_id)->get();
            session()->forget('systemLanguage');
            session()->put('systemLanguage',$systemLanguage);
           }

            Session::flash('langChange', 'Successfully Language Changed');
            return redirect()->to('locale/' . $language->language_universal);
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function getTranslationTerms(Request $request)
    {

        try {
            $terms = SmLanguagePhrase::where('modules', $request->id)->get();
            return response()->json($terms);
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function translationTermUpdate(Request $request)
    {

        $request->validate(
            [
                'module_id' => 'required',
                'language_universal' => 'required',
            ],
            [
                'module_id.required' => 'Please select at least one module',
            ]
        );



        try {
            $InputId            = $request->InputId;
            $language_universal = $request->language_universal;
            $LU                 = $request->LU;

            foreach ($InputId as $id) {
                $data  = SmLanguagePhrase::find($id);
                $data->$language_universal = $LU[$id];
                $data->save();
            }
            
            
            //    $getData = SmLanguagePhrase::where('active_status',1)->get();
            //    $LanguageArr=[];
            //    foreach ($getData as $row) {
            //     $LanguageArr[$row->default_phrases]=$row->bn;
            //   }
         

            $getData = SmLanguagePhrase::where('active_status', 1)->pluck($language_universal, 'default_phrases');
            $jsonContent = json_encode($getData);
            $file = base_path() . '/resources/lang/' . $language_universal . '/'. $language_universal. ".json";
            File::put($file, $jsonContent);
           
            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    //Update System is Availalbe

    public function recurse_copy($src, $dst)
    {

        try {
            $dir = opendir($src);
            @mkdir($dst);
            while (false !== ($file = readdir($dir))) {
                if (($file != '.') && ($file != '..')) {
                    if (is_dir($src . '/' . $file)) {
                        $this->recurse_copy($src . '/' . $file, $dst . '/' . $file);
                    } else {
                        copy($src . '/' . $file, $dst . '/' . $file);
                    }
                }
            }
            closedir($dir);
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

     //Update System is Availalbe
     public function academicIdUpdated(){
        try{

            $table_list = [
            "custom_result_settings",
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
            "sm_classes",
            "sm_class_optional_subject",
            "sm_class_rooms",
            "sm_class_routines",
            "sm_class_routine_updates",
            "sm_class_sections",
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
            "sm_sections",
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
            "sm_upload_homework_contents",
            "sm_user_logs",
            "sm_vehicles",
            "sm_visitors",
            "sm_weekends"
        ];

        
        $MyUpdatedTable = [];
        $academicYears = SmAcademicYear::select('year', 'id', 'school_id')->get();
        $ids = [];
        foreach($table_list as  $table) {

            foreach($academicYears as $ac){
                
                $className = 'App\\' . Str::studly(Str::singular($table));
                  $rs = $className::whereYear('created_at', $ac->year)
                ->where('school_id', $ac->school_id)
                ->first();
               
                if(!empty($rs)){

                if(! in_array($rs->id, $ids)){
                    $ids[] = $rs->id;
                        $rs->academic_id = $ac->id;
                        $rs->save();
                        $MyUpdatedTable[] = $table;
                        }
                    }
                }

                
            }

            $ids =[];

            
    
   
    return $MyUpdatedTable;

    }
    catch (\Exception $e) {
        dd($e);
}
    
}

    public function DbUpgrade()
    {
        
        try {
            if (!Schema::hasTable('infix_module_managers')) {
                Artisan::call('migrate --path=/database/migrations/2020_06_10_193309_create_infix_module_managers_table.php');
             }
             
             Artisan::call('cache:clear');
             Artisan::call('view:clear');
             Artisan::call('config:cache');
             
            $table_list = [
                "countries",
                "custom_result_settings",
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
                "sm_classes",
                "sm_class_optional_subject",
                "sm_class_rooms",
                "sm_class_routines",
                "sm_class_routine_updates",
                "sm_class_sections",
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
                "sm_general_settings",
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
                "sm_sections",
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

            
            $name ='academic_id';
            $data = [];
			foreach($table_list as $row){
                if (!Schema::hasColumn($row, $name)) {
                        Schema::table($row, function ($table) use ($name) {
                            $table->integer($name)->default(1)->nullable();
                        });
                    }
                    else{
                        $data[] = $row;
                        
                    }
            }
            
            return redirect('/login');
        } catch (\Exception $e) {
             
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    //Update System
    public function UpdateSystem()
    {
        try {
            $data = SmGeneralSettings::first();
            return view('backEnd.systemSettings.updateSettings', compact('data'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    //Update System
    public function AboutSystem()
    {

        try {
            
            $setting = SmGeneralSettings::first('software_version')->software_version;
            return view('backEnd.systemSettings.aboutSystem',compact('setting'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }



    public function admin_UpdateSystem(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'content_file' => "required",
        ]);
        try {
            if (!file_exists('upgradeFiles')) {
                mkdir('upgradeFiles', 0777, true);
            }

            $fileName = "";
            if ($request->file('content_file') != "") {
                $file = $request->file('content_file');
                $fileName = time() . "." . $file->getClientOriginalExtension();
                $file->move('upgradeFiles/', $fileName);
                $fileName =  'upgradeFiles/' . $fileName;
            }


            $zip = new ZipArchive;
            $res = $zip->open($fileName);
            if ($res === TRUE) {
                $zip->extractTo('upgradeFiles/');
                $zip->close();
            } else {
                Toastr::error('Operation Failed, You have to select zip file', 'Failed');
                return redirect()->back();
            }
            $data = SmGeneralSettings::find(1);
            $data->system_version = $request->version_name;
            $data->save();
            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function UpgradeSettings(Request $request)
    {
        Toastr::success('Operation successful', 'Success');
        return redirect()->back();
    }

    public function ajaxSelectCurrency(Request $request)
    {
        try {
            $select_currency_symbol = SmCurrency::select('symbol')->where('code', '=', $request->id)->first();

            $currency_symbol['symbol'] = $select_currency_symbol->symbol;

            return response()->json([$currency_symbol]);
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    //ajax theme Style Active
    public function themeStyleActive(Request $request)
    {

        try {
            if ($request->id) {
                $modified = SmStyle::where('is_active', 1)->where('school_id', Auth::user()->school_id)->update(array('is_active' => 0));
               
                $selected = SmStyle::findOrFail($request->id);
                $selected->is_active = 1;
               $res  = $selected->save();
               if($res){
                session()->forget('all_styles');
                $all_styles = SmStyle::where('school_id', 1)->where('active_status', 1)->get() ;
                session()->put('all_styles', $all_styles);

                session()->forget('active_style');
                $active_style = SmStyle::where('school_id', 1)->where('is_active', 1)->first();
                session()->put('active_style', $active_style);
               }
                
                return response()->json([$modified]);
            } else {
                return '';
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    //ajax theme Style Active
    public function themeStyleRTL(Request $request)
    {
        
        try {
            
            if ($request->id) {
                
                $selected = SmGeneralSettings::where('school_id', Auth::user()->school_id)->first();
                $selected->ttl_rtl = $request->id;
                 $selected->save();
              
                session()->forget('text_direction');
                session()->put('text_direction', $request->id);
               
               
                return response()->json([$selected]);
            } else {
                return '';
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    //ajax session Active
    public function sessionChange(Request $request)
    {
        try {
            $school_id = Auth::user()->school_id;
            if ($request->id) {
                $data = SmAcademicYear::find($request->id);
               
                $selected = SmGeneralSettings::where('school_id',$school_id)->first();
                $selected->session_id = $request->id;
                $selected->session_year = $data->year;
                $selected->save();
                session()->put('sessionId', $selected->session_id);
                return response()->json([$selected]);
            } else {
                return '';
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    /* ******************************** homePageBackend ******************************** */
    public function homePageBackend()
    {
        try {
            $links      = SmHomePageSetting::find(1);
            $permisions = SmFrontendPersmission::where('parent_id', 1)->get();
            return view('backEnd.systemSettings.homePageBackend', compact('links', 'permisions'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function homePageUpdate(Request $request)
    {
        $request->validate([
            'title'             => 'required',
            'long_title'        => 'required',
            'short_description' => 'required',
            'permisions'        => 'required|array',
            'image' => "sometimes|nullable|mimes:jpg,jpeg,png",
        ]);

        try {
            $maxFileSize = SmGeneralSettings::first('file_size')->file_size;
            $file = $request->file('image');
            $fileSize =  filesize($file);
            $fileSizeKb = ($fileSize / 1000000);
            if($fileSizeKb >= $maxFileSize){
                Toastr::error( 'Max upload file size '. $maxFileSize .' Mb is set in system', 'Failed');
                return redirect()->back();
            }

            $permisionsArray = $request->permisions;
            $permisions      = SmFrontendPersmission::where('parent_id', 1)->update(['is_published' => 0]);
            foreach ($permisionsArray as $value) {
                $permisions = SmFrontendPersmission::where('id', $value)->update(['is_published' => 1]);
            }

            $image = "";
            if ($request->file('image') != "") {
                $file       = $request->file('image');
                $image_name = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                $path       = 'public/uploads/homepage';
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }

                $file->move($path . '/', $image_name);
                $image = $path . '/' . $image_name;
            }

            //Update Home Page
            $update                    = SmHomePageSetting::find(1);
            $update->title             = $request->title;
            $update->long_title        = $request->long_title;
            $update->short_description = $request->short_description;
            $update->link_label        = $request->link_label;
            $update->link_url          = $request->link_url;
            if ($request->file('image') != "") {
                $update->image = $image;
            }
            $result = $update->save();

            if ($result) {
                Toastr::success('Operation successful', 'Success');
                return redirect()->back();
            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    /* ******************************** homePageBackend ******************************** */

    /* ******************************** customLinks ******************************** */

    public function customLinks()
    {

        try {
            $links = SmCustomLink::find(1);
            return view('backEnd.systemSettings.customLinks', compact('links'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function customLinksUpdate(Request $request)
    {


        try {
            $links = SmCustomLink::find(1);
            $lists = ['title1', 'link_label1', 'link_href1', 'link_label2', 'link_href2', 'link_label3', 'link_href3', 'link_label4', 'title2', 'link_href4', 'link_label5', 'link_href5', 'link_label6', 'link_href6', 'link_label7', 'link_href7', 'link_label8', 'link_href8', 'title3', 'link_label9', 'link_href9', 'link_label10', 'link_href10', 'link_label11', 'link_href11', 'link_label12', 'link_href12', 'title4', 'link_label13', 'link_href13', 'link_label14', 'link_href14', 'link_label15', 'link_href15', 'link_label16', 'link_href16'];
            // $lists = ['title1', 'link_label1', 'link_href1', 'link_label2', 'link_href2', 'link_label3', 'link_href3', 'link_label4', 'title2', 'link_href4', 'link_label5', 'link_href5', 'link_label6', 'link_href6', 'link_label7', 'link_href7', 'link_label8', 'link_href8', 'title3', 'link_label9', 'link_href9', 'link_label10', 'link_href10', 'link_label11', 'link_href11', 'link_label12', 'link_href12', 'title4', 'link_label13', 'link_href13', 'link_label14', 'link_href14', 'link_label15', 'link_href15', 'link_label16', 'link_href16', 'facebook_url', 'twitter_url', 'dribble_url', 'behance_url'];

            foreach ($lists as $list) {
                if (isset($request->$list)) {
                    $links->$list = $request->$list;
                }
                $result = $links->save();
            }

            if ($result) {
                Toastr::success('Operation successful', 'Success');
                return redirect()->back();
            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    /* ******************************** customLinks ******************************** */

    public function getSystemVersion(Request $request)
    {

        try {
            $version = SmSystemVersion::find(1);
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data['SystemVersion'] = $version;
                return ApiBaseMethod::sendResponse($data, null);
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function getSystemUpdate(Request $request, $version_upgrade_id = null)
    {

        try {
            $data = [];
            if (Schema::hasTable('sm_update_files')) {
                $version = DB::table('sm_update_files')->where('version_name', $version_upgrade_id)->first();
                if (!empty($version->path)) {
                    $url = url('/') . '/' . $version->path;
                    header("Location: " . $url);
                    die();
                } else {
                    return redirect()->back();
                }
            }
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function apiPermission()
    {


        try {
            if (!Schema::hasColumn('sm_general_settings', 'api_url')) {
                Schema::table('sm_general_settings', function ($table) {
                    $table->integer('api_url')->default(0)->nullable();
                });
            }
            $settings = SmGeneralSettings::find(1);

            return view('backEnd.systemSettings.apiPermission', compact('settings'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function apiPermissionUpdate(Request $request)
    {

        try {
            if ($request->status == 'on') {
                $status = 1;
            } else {
                $status = 0;
            }
            $user = SmGeneralSettings::find(1);
            $user->api_url = $status;
            $user->save();
        
            return response()->json($user);
        } catch (\Exception $e) {
            dd($e);
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    /*** RTL TTL ***/

    public function create_a_dynamic_column($table_name, $column_name, $column_type, $column_limit)
    {
        try {
            if (!Schema::hasColumn($table_name, $column_name)) {
                Schema::table($table_name, function ($table, $column_name,$column_type, $column_limit) {
                    $table->$column_type($column_name, $column_limit)->nullable();
                });
                return true;
            } else {
                return true;
            }
        } catch (\Exception $e) {
            report($e);
            return false;
        }
    }

    public function enable_rtl(Request $request)
    {

        try {
            if ($this->create_a_dynamic_column('sm_general_settings', 'ttl_rtl', 'integer', 11)) {
                $s = SmGeneralSettings::find(1);
                $s->ttl_rtl = $request->status;
                $s->save();
                return response()->json($s);
            } else {
                $s['flag'] = false;
                $s['message'] = 'something went wrong!!';
                return response()->json($s);
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function buttonDisableEnable()
    {

        try {

            // $settings = SmGeneralSettings::where('school_id', Auth::user()->school_id)->first();
            return view('backEnd.systemSettings.buttonDisableEnable');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function changeWebsiteBtnStatus(Request $request)
    {


        try {
            $gettings = SmGeneralSettings::where('school_id', Auth::user()->school_id)->first();
            $gettings->website_btn = $request->status;
           $result = $gettings->save();
            //Session put generalSetting
           if($result){
            session()->forget('generalSetting');
            session()->put('generalSetting',$gettings);
           }
            
            return response()->json(null);
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function changeDashboardBtnStatus(Request $request)
    {


        try {
            $gettings = SmGeneralSettings::where('school_id', Auth::user()->school_id)->first();
            $gettings->dashboard_btn = $request->status;
            $result = $gettings->save();
            //Session put generalSetting
           if($result){
            session()->forget('generalSetting');
            session()->put('generalSetting',$gettings);
           }
            return response()->json(null);
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function changeReportBtnStatus(Request $request)
    {

        try {
            $gettings = SmGeneralSettings::where('school_id', Auth::user()->school_id)->first();
            $gettings->report_btn = $request->status;
            $result = $gettings->save();
            //Session put generalSetting
           if($result){
            session()->forget('generalSetting');
            session()->put('generalSetting',$gettings);
           }
            return response()->json(null);
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function changeStyleBtnStatus(Request $request)
    {

        try {
            $gettings = SmGeneralSettings::where('school_id', Auth::user()->school_id)->first();
            $gettings->style_btn = $request->status;
            $result = $gettings->save();
            //Session put generalSetting
           if($result){
            session()->forget('generalSetting');
            session()->put('generalSetting',$gettings);
           }
            return response()->json(null);
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function changeLtlRtlBtnStatus(Request $request)
    {

        try {
            $gettings = SmGeneralSettings::where('school_id', Auth::user()->school_id)->first();
            $gettings->ltl_rtl_btn = $request->status;
            $result = $gettings->save();
            //Session put generalSetting
           if($result){
            session()->forget('generalSetting');
            session()->put('generalSetting',$gettings);
           }
            return response()->json(null);
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function changeLanguageBtnStatus(Request $request)
    {

        try {
            $gettings = SmGeneralSettings::where('school_id', Auth::user()->school_id)->first();
            $gettings->lang_btn = $request->status;
            $result = $gettings->save();
            //Session put generalSetting
           if($result){
            session()->forget('generalSetting');
            session()->put('generalSetting',$gettings);
           }
            return response()->json(null);
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function updateWebsiteUrl(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'website_url' => "url",
        ]);

        if ($validator->fails()) {

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        try {

            $settings = SmGeneralSettings::where('school_id', Auth::user()->school_id)->first();
            $settings->website_url = $request->website_url;
            $result = $settings->save();
           
            //Session put generalSetting
           if($result){
            session()->forget('generalSetting');
            session()->put('generalSetting',$settings);
           }
            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function updateCreatedDate()
    {

        try {
            $path = base_path() . "/.env";
            $db_name = env('DB_DATABASE', null);
            $column = 'created_at';
            $table_list = DB::select("SELECT TABLE_NAME
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE COLUMN_NAME ='$column'
                AND TABLE_SCHEMA='$db_name'");
            $tables = [];
            foreach ($table_list as $row) {
                $tables[] = $row->TABLE_NAME;
            }
            return $db_name;
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    // manage currency
    public function manageCurrency()
    {

        try {
            // $session_ids = SmSession::where('active_status', 1)->where('school_id', '=', Auth::user()->school_id)->get();
            $currencies = SmCurrency::whereIn('school_id', [1, Auth::user()->school_id])->get();
            // $dateFormats = SmDateFormat::where('active_status', 1)->get();
            // $languages = SmLanguage::whereOr(['school_id', Auth::user()->school_id], ['school_id', 1])->get();
            // $countries = SmCountry::select('currency')->groupBy('currency')->get();
            // $academic_years = SmAcademicYear::where('school_id', '=', Auth::user()->school_id)->get();
            return view('backEnd.systemSettings.manageCurrency', compact('currencies'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function storeCurrency(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required | max:25',
            'code' => 'required | max:15',
            'symbol' => 'required | max:15',
        ]);



        // school wise uquine validation
        $is_duplicate = SmCurrency::where('school_id', Auth::user()->school_id)->where('name', $request->name)->where('code', $request->code)->first();
        if ($is_duplicate) {
            Toastr::error('Ops! Duplicate Found!', 'Failed');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // school wise uquine validation
        // $is_duplicate = SmCurrency::where('school_id', Auth::user()->school_id)->where('code', $request->code)->first();
        // if ($is_duplicate) {
        //     Toastr::error('Ops! Duplicate  Code Found!', 'Failed');
        //     return redirect()->back()->withErrors($validator)->withInput();
        // }

        // school wise uquine validation
        // $is_duplicate = SmCurrency::where('school_id', Auth::user()->school_id)->where('symbol', $request->symbol)->first();
        // if ($is_duplicate) {
        //     Toastr::error('Ops! Duplicate Symbol Found!', 'Failed');
        //     return redirect()->back()->withErrors($validator)->withInput();
        // }


        try {
            $s = new SmCurrency();
            $s->name = $request->name;
            $s->code = $request->code;
            $s->symbol = $request->symbol;
            $s->school_id = Auth::user()->school_id;
            $s->save();
            Toastr::success('Operation successful', 'Success');
            return redirect('manage-currency');

            $currencies = SmCurrency::whereOr(['school_id', Auth::user()->school_id], ['school_id', 1])->get();
            return view('backEnd.systemSettings.manageCurrency', compact('currencies'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function storeCurrencyUpdate(Request $request)
    {

        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required | max:25',
            'code' => 'required | max:15',
            'symbol' => 'required | max:15',
        ]);


        // school wise uquine validation
        $is_duplicate = SmCurrency::where('school_id', Auth::user()->school_id)->where('name', $request->name)->where('code', $request->code)->where('id', '!=', $request->id)->first();
        if ($is_duplicate) {
            Toastr::error('Ops! Duplicate Name Found!', 'Failed');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // school wise uquine validation
        // $is_duplicate = SmCurrency::where('school_id', Auth::user()->school_id)->where('code', $request->code)->where('id','!=', $request->id)->first();
        // if ($is_duplicate) {
        //     Toastr::error('Ops! Duplicate  Code Found!', 'Failed');
        //     return redirect()->back()->withErrors($validator)->withInput();
        // }

        // school wise uquine validation
        // $is_duplicate = SmCurrency::where('school_id', Auth::user()->school_id)->where('symbol', $request->symbol)->where('id','!=', $request->id)->first();
        // if ($is_duplicate) {
        //     Toastr::error('Ops! Duplicate Symbol Found!', 'Failed');
        //     return redirect()->back()->withErrors($validator)->withInput();
        // }



        try {
            $s = SmCurrency::findOrfail($request->id);
            $s->name = $request->name;
            $s->code = $request->code;
            $s->symbol = $request->symbol;
            $s->school_id = Auth::user()->school_id;
            $s->update();

            Toastr::success('Operation successful', 'Success');
            return redirect('manage-currency');

            $currencies = SmCurrency::whereOr(['school_id', Auth::user()->school_id], ['school_id', 1])->get();
            return view('backEnd.systemSettings.manageCurrency', compact('currencies'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect('manage-currency');
        }
    }
    public function manageCurrencyEdit($id)
    {

        try {
            $currencies = SmCurrency::whereOr(['school_id', Auth::user()->school_id], ['school_id', 1])->get();
            $editData = SmCurrency::where('id',$id)->first();

            return view('backEnd.systemSettings.manageCurrency', compact('editData', 'currencies'));
        } catch (\Exception $e) {
            // dd($e);
            Toastr::error('Operation Failed', 'Failed');
            return redirect('manage-currency');
        }
    }
    public function manageCurrencyDelete($id)
    {
        try {
            $currency = SmCurrency::findOrfail($id);
            $currency->delete();
            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {

            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function systemDestroyedByAuthorized()
    {
        try {
            return view('backEnd.systemSettings.manageCurrency', compact('editData', 'currencies'));
        } catch (\Exception $e) {

            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function schoolSettingsView(Request $request)
    {

        $editData = SmGeneralSettings::where('school_id', Auth::user()->school_id)->first();
        $school = SmSchool::where('id', '=', Auth::user()->school_id)->first();

        $academic_year = SmAcademicYear::findOrfail(@$editData->session_id);
        if (ApiBaseMethod::checkUrl($request->fullUrl())) {

            return ApiBaseMethod::sendResponse($editData, null);
        }
        return view('saas::systemSettings.schoolGeneralSettingsView', compact('editData', 'school', 'academic_year'));
    }


    public function viewAsSuperadmin()
    {       
    
         $school_id = Auth::user()->school_id;
        $role_id = Auth::user()->role_id;
        if ($school_id == 1 && $role_id == 1) {
            if (Session::get('isSchoolAdmin') == TRUE) {
                session(['isSchoolAdmin' => FALSE]);
                // Session::set('isSchoolAdmin', FALSE);
                Toastr::success('You are accessing as saas admin', 'Success');
                return redirect('superadmin-dashboard');
            } else {
                session(['isSchoolAdmin' => TRUE]);
                // Session::set('isSchoolAdmin', TRUE);
                Toastr::success('You are accessing as school admin', 'Success');
                return redirect('admin-dashboard');
            }
        }
    }






    public function SmsTemplate()
    {
        try {
            $template = SmsTemplate::first();
            return view('backEnd.systemSettings.sms_template', compact('template'));
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function SmsTemplateStore(Request $request, $id)
    {

        try {
            $data = SmsTemplate::find(1);
            
            $data->admission_pro = $request->admission_pro;
            $data->student_admit = $request->student_admit;
            $data->login_disable = $request->login_disable;
            $data->exam_schedule = $request->exam_schedule;
            $data->exam_publish = $request->exam_publish;
            $data->due_fees = $request->due_fees;
            $data->collect_fees = $request->collect_fees;
            $data->stu_promote = $request->stu_promote;
            $data->attendance_sms = $request->attendance_sms;
            $data->absent = $request->absent;
            $data->late_sms = $request->late_sms;
            $data->er_checkout = $request->er_checkout;
            $data->st_checkout = $request->st_checkout;
            $data->st_credentials = $request->st_credentials;
            $data->staff_credentials = $request->staff_credentials;
            $data->holiday = $request->holiday;
            $data->leave_app = $request->leave_app;
            $data->approve_sms = $request->approve_sms;
            $data->birth_st = $request->birth_st;
            $data->birth_staff = $request->birth_staff;
            $data->cheque_bounce = $request->cheque_bounce;
            $data->l_issue_b = $request->l_issue_b;
            $data->re_issue_book = $request->re_issue_book;
            $data->save();
            Toastr::success('Operation success', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function SmsTemplateNew()
    {
        try {
            $template = SmsTemplate::first();
            return view('backEnd.communicate.sms_template', compact('template'));
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function SmsTemplateNewStore(Request $request)
    {



        try {

            $template = SmsTemplate::find(1);
            $template->student_approve_message_sms = $request->student_approve_message_sms;
            $template->student_approve_message_sms_status = $request->student_approve_message_sms_status;

            $template->student_registration_message_sms = $request->student_registration_message_sms;
            $template->student_registration_message_sms_status = $request->student_registration_message_sms_status;

            $template->student_admission_message_sms = $request->student_admission_message_sms;
            $template->student_admission_message_sms_status = $request->student_admission_message_sms_status;

            $template->exam_schedule_message_sms = $request->exam_schedule_message_sms;
            $template->exam_schedule_message_sms_status = $request->exam_schedule_message_sms_status;

            $template->dues_fees_message_sms = $request->dues_fees_message_sms;
            $template->dues_fees_message_sms_status = $request->dues_fees_message_sms_status;

            $template->save();

            Toastr::success('Operation success', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function databaseUpgrade(){

        Toastr::success('Database is up to date', 'Success');
        return redirect()->back();

    }
}


    