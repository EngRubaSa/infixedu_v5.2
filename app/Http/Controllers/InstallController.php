<?php

namespace App\Http\Controllers;

use App\User;
use App\SmStaff;
use App\SmSchool;
use App\Envato\Envato;
use GuzzleHttp\Client;
use App\SmGeneralSettings;
use App\InfixModuleManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Session;
use GuzzleHttp\Exception\ConnectException;

class InstallController extends Controller
{

    public function __construct()
    {
        // $this->middleware('PM');
        // User::checkAuth();
    }



    public function systemVerifyPurchases(Request $request)
    {

        $request->validate([
            'envatouser'                => "required",
            'purchasecode'              => "required",
            'installationdomain'        => "required"
        ]);
        $envatouser = htmlspecialchars($request->input('envatouser'));
        $purchasecode = htmlspecialchars($request->input('purchasecode'));
        $domain = htmlspecialchars($request->input('installationdomain'));
        $UserData = Envato::verifyPurchase($purchasecode);
        if (!empty($UserData['verify-purchase']['item_id']) && (User::$item == $UserData['verify-purchase']['item_id'])) {
            try {
                $client = new Client();
                $product_info = $client->request('GET', 'https://sp.uxseven.com/api/installation/' . $purchasecode . '/' . $domain . '/' . $envatouser);
                $product_info = $product_info->getBody()->getContents();
                $product_info = json_decode($product_info);
                if ($product_info->flag == false) {
                    return redirect()->back()->with("message-danger", $product_info->message);
                } else {
                    $setting = InfixModuleManager::where('name', $request->name)->first();
                    $setting->purchase_code = $purchasecode;
                    $setting->save();
                    return redirect('login');
                }
            } catch (\Exception $e) {
                return redirect()->back()->with("message-danger", $e->getMessage());
            }
        } else {
            return redirect()->back()->with("message-danger", 'Ops, Your purchase code is not valid !');
        }
    }


    public function systemVerify(Request $request)
    {

        $request->validate([
            'envatouser'                => "required",
            'purchasecode'              => "required",
            'installationdomain'        => "required"
        ]);
        $envatouser = htmlspecialchars($request->input('envatouser'));
        $purchasecode = htmlspecialchars($request->input('purchasecode'));
        $domain = htmlspecialchars($request->input('installationdomain'));
        if (filter_var(gethostbyname($domain), FILTER_VALIDATE_IP)) {
            $UserData = Envato::verifyPurchase($purchasecode);
            if (!empty($UserData['verify-purchase']['item_id']) && (User::$item == $UserData['verify-purchase']['item_id'])) {
                try {
                    $client = new Client();
                    $product_info = $client->request('GET', 'https://sp.uxseven.com/api/installation/' . $purchasecode . '/' . $domain . '/' . $envatouser);
                    $product_info = $product_info->getBody()->getContents();
                    $product_info = json_decode($product_info);
                    if ($product_info->flag == false) {
                        return redirect()->back()->with("message-danger", $product_info->message);
                    } else {
                        $setting = SmGeneralSettings::find(1);
                        $setting->system_purchase_code = $purchasecode;
                        $setting->system_domain = $domain;
                        $setting->save();
                        return redirect('login');
                    }
                } catch (\Exception $e) {
                    return redirect()->back()->with("message-danger", $e->getMessage());
                }
            } else {
                return redirect()->back()->with("message-danger", 'Ops, Your purchase code is not valid !');
            }
        } else {
            return redirect()->back()->with("message-danger", 'Ops, Your domain is not valid !');
        }
    }



    public function is_valid_domain_name($domain_name)
    {

        try {
            if (filter_var(gethostbyname($domain_name), FILTER_VALIDATE_IP)) {
                return TRUE;
            } else return FALSE;
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    //##Step01
    public function index()
    {
      
        if (Schema::hasTable('sm_general_settings') && Schema::hasTable('users')) {
            $users = DB::table('users')->get();
            if ($users->count() > 0) {
                return redirect('login');
            } else {
                Session::put('step1', 1);
                return view('install.welcome_to_infix');
            }
        } else {
             
            Session::put('step1', 1);
            return view('install.welcome_to_infix');
        }
    }

    //##Step2
    public function verify()
    {
        return view('install.verify');
    }

    public function Moduleverify($name = null)
    {
        if (Config::get('app.app_pro')) {
            return view('install.ProModuleverify', compact('name'));
        } else {
            return view('install.Moduleverify', compact('name'));
        }
    }

    public function ModuleverifyPurchases(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'purchasecode' => 'required',
        ]);

        $name = $request->name;
        $new_column_name = strtolower($request->name);


        $php_extension_ssl = Envato::aci($request->purchase_code);

        if ($php_extension_ssl != "" || $php_extension_ssl != NULL) {
            try {
                // added a new column in sm general settings
                if (!Schema::hasColumn('sm_general_settings', $new_column_name)) {
                    Schema::table('sm_general_settings', function ($table, $new_column_name) {
                        $table->integer($new_column_name)->default(1)->nullable();
                    });
                }
            } catch (\Exception $e) {
                DB::rollback();
                Toastr::error($e->getMessage(), 'Failed');
                Log::info($e->getMessage());
                return redirect()->back();
            }
            try {
                DB::beginTransaction();
                $exConfig = SmGeneralSettings::find(1);
                $exConfig->$new_column_name = 1;
                $exConfig->save();
            } catch (\Exception $e) {
                DB::rollback();
                Toastr::error($e->getMessage(), 'Failed');
                Log::info($e->getMessage());
                return redirect()->back();
            }

            try {
                $dataPath = 'Modules/' . $name . '/' . $name . '.json';        // // Get the contents of the JSON file
                $strJsonFileContents = file_get_contents($dataPath);
                $array = json_decode($strJsonFileContents, true);

                $version = $array[$name]['versions'][0];
                $url = $array[$name]['url'][0];
                $notes = $array[$name]['notes'][0];

                DB::beginTransaction();
                $s = InfixModuleManager::where('name', $name)->first();
                if (empty($s)) {
                    $s = new InfixModuleManager();
                }
                $s->name = $name;
                $s->notes = $notes;
                $s->version = $version;
                $s->update_url = $url;
                $s->installed_domain = url('/');
                $s->activated_date = date('Y-m-d');
                $s->purchase_code = $request->purchase_code;
                $r = $s->save();
                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                Toastr::error($e->getMessage(), 'Failed');
                Log::info($e->getMessage());
                return redirect()->back();
            }
            Session::flash("message-success", "Congratulations! Purchase code is verified.");
            Toastr::success('Your purchase code is not valid', 'Success');
            return redirect('login');
        } else {
            Toastr::error('Your purchase code is not valid', 'Failed');
            return redirect()->back();
        }
    }


    public function CheckPurchaseVerificationPage()
    {
        if (Session::get('step1') != 1) {
            return redirect('install');
        }
        if (Config::get('app.app_pro')) {
            Session::put('step2', 2);
            return view('install.pro_verification_page');
        } else {
            Session::put('step2', 2);
            return view('install.check_purchase_page');
        }
    }



    //##Step03
    public function CheckVerifiedInput(Request $request)
    {

        if (Session::get('step1') != 1 && Session::get('step2') != 2) {
            return redirect('install');
        }
        if (Config::get('app.app_pro')) {
            $request->validate([
                'email' => 'required|email',
                'code' => 'required',
                'domain' => 'required',
            ]);
            $email = htmlspecialchars($request->input('email'));
            $code = htmlspecialchars($request->input('code'));
            // $domain = htmlspecialchars($request->input('domain'));

            if (in_array('curl', get_loaded_extensions())) {
                $client = new Client();
                $verify = $client->request('GET', 'http://sp.uxseven.com/api/pro-user/' . $code . '/' . $email);
                $verify = $verify->getBody()->getContents();
                $verify = json_decode($verify);
                if (!empty($verify->products)) {
                    Session::put('step3', 3);
                    Session::flash("message-success", "Congratulations! Purchase code is verified.");
                    return redirect('check-environment');
                } else {
                    return redirect()->back()->with("message-danger", "Your purchase code is not valid, Please try again");
                }
            } else {
                $message_danger = "Ops! CURL is not available on your web server. Please install it.";
                Session::flash("message-danger", $message_danger);
                return redirect()->back()->with("message-danger", $message_danger);
            }
        } //if pro user verification
        else {
            $request->validate([
                'envatouser' => 'required',
                'purchasecode' => 'required',
                'installationdomain' => 'required',
            ]);
            try {
                if ($this->is_valid_domain_name($request->installationdomain)) {
                    $envatouser = htmlspecialchars($request->input('envatouser'));
                    $purchasecode = htmlspecialchars($request->input('purchasecode'));
                    $domain = htmlspecialchars($request->input('installationdomain'));

                    $UserData = Envato::verifyPurchase($purchasecode);
                   

                    if (!empty($UserData['verify-purchase']['item_id']) && (User::$item == $UserData['verify-purchase']['item_id'])) {
                        
                        Session::put('envatouser', $envatouser);
                        Session::put('purchasecode', $purchasecode);
                        Session::put('domain', $domain);
                        Session::put('item_id', $UserData['verify-purchase']['item_id']);

                        if (in_array('curl', get_loaded_extensions())) {
                            try {
                                $client = new Client();
                                $product_info = $client->request('GET', 'https://sp.uxseven.com/api/installation/' . $purchasecode . '/' . $domain . '/' . $envatouser);
                                $product_info = $product_info->getBody()->getContents();
                                $product_info = json_decode($product_info);
                                
                                if ($product_info->flag == false) {
                                    return redirect()->back()->with("message-danger", $product_info->message);
                                } else {
                                    Session::put('step3', 3);
                                    Session::flash("message-success", "Congratulations! Purchase code is verified." . $product_info->message);
                                    return redirect('check-environment');
                                }
                            } catch (\GuzzleHttp\Exception\ConnectException $e) {
                                Session::put('step3', 3);
                                Session::flash("message-success", "Congratulations! Purchase code is verified.");
                                return redirect('check-environment');
                                return redirect()->back()->with("message-danger", $e->getMessage());
                            } catch (\Exception $e) {
                                return redirect()->back()->with("message-danger", "Operation Failed! Please contact us");
                            }
                        } else {
                            Session::flash("message-danger", "Ops! CURL is not available on your web server. Please install it.");
                        }
                    } else {
                        Session::flash("message-danger", "Ops! Purchase Code is not valid. Please try again.");
                        return redirect()->back()->with("message-danger", "Ops! Purchase Code is not valid. Please try again.");
                    }


                    return redirect()->back()->with("message-danger", "Ops! Purchase Code is not valid. Please try again.");
                } else {
                    return redirect()->back()->with("message-danger", "Ops! Invalid Domain. Please try again.");
                }
            } catch (\Exception $e) {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        }
        // }
    }

    //##Step04
    public function checkEnvironmentPage()
    {
        // if (Schema::hasTable('sm_general_settings') && Schema::hasTable('users')) {
        //     return redirect('login');
        // } else {


        if (Session::get('step1') != 1) {
            return redirect('install');
        } elseif (Session::get('step2') != 2) {
            return redirect('check-purchase-verification');
        } elseif (Session::get('step3') != 3) {
            return redirect('check-purchase-verification');
        } else {
            try {
                $path = '';
                $folders = array(
                    $path . "/route",
                    $path . "/resources",
                    $path . "/public",
                    $path . "/storage",
                );

                Session::put('step4', 4);
                return view('install.checkEnvironmentPage')->with('folders', $folders);
            } catch (\Exception $e) {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        }
        // }
    }


    //##Step06
    public function confirmation()
    {

        try {
            return view('install.confirmation');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }








    //##Step05
    public function checkEnvironment(Request $request)
    {
        // if (Schema::hasTable('sm_general_settings') && Schema::hasTable('users')) {
        //     return redirect('login');
        // } else {
        if (Session::get('step1') != 1) {
            return redirect('install');
        } elseif (Session::get('step2') != 2) {
            return redirect('check-purchase-verification');
        } elseif (Session::get('step3') != 3) {
            return redirect('check-purchase-verification');
        } elseif (Session::get('step4') != 4) {
            return redirect('check-environment');
        } else {
            try {
                if (phpversion() >= '7.1' && OPENSSL_VERSION_NUMBER > 0x009080bf && extension_loaded('mbstring') && extension_loaded('tokenizer') && extension_loaded('xml') && extension_loaded('ctype')  && extension_loaded('json')) {
                    Session::put('step5', 5);
                    return redirect('system-setup-page');
                } else {
                    Session::flash("message-danger", "Ops! Extension are disabled.  Please check requirements!");
                    return redirect()->back()->with("message-danger", "Ops! Extension are disabled.  Please check requirements!");
                }
            } catch (\Exception $e) {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        }
        // }
    }



    //##Step06
    public function systemSetupPage()
    {

        if (Schema::hasTable('sm_general_settings') && Schema::hasTable('users')) {
            return redirect('login');
        } else {
            if (Session::get('step1') != 1) {
                return redirect('install');
            } elseif (Session::get('step2') != 2) {
                return redirect('check-purchase-verification');
            } elseif (Session::get('step3') != 3) {
                return redirect('check-purchase-verification');
            } elseif (Session::get('step4') != 4) {
                return redirect('check-environment');
            } else {
                try {
                    Session::put('step6', 6);
                    return view('install.systemSetupPage');
                } catch (\Exception $e) {
                    Toastr::error('Operation Failed', 'Failed');
                    return redirect()->back();
                }
            }
        }
    }



    //##Step07
    public function confirmInstalling(Request $request)
    {
        set_time_limit(2700);
        $this->validate($request, [
            'institution_name' => 'required',
            'system_admin_email' => 'required',
            'system_admin_password' => 'min:6|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'min:6'
        ]);

        try {
            Artisan::call('migrate:refresh');
            // shell_exec('php artisan passport:install');
            if ($request->install_mode == 1) {
                Artisan::call('db:seed');
            }
        } catch (\Exception $e) {
            $sql = base_path('database/infixeduv5_2.sql');
            DB::unprepared(file_get_contents($sql));
        }


        try {
            Session::put('system_admin_email', $request->system_admin_email);
            Session::put('system_admin_password', $request->system_admin_password);

            // Artisan::call('migrate:refresh');
            // if ($request->install_mode == 1) {
            //     Artisan::call('db:seed');
            // }


            if (Schema::hasTable('migrations')) {
                $migration = DB::table('migrations')->get();
                if (count($migration) > 0) {
                    $id = 1;
                    $setting = SmGeneralSettings::find($id);
                    if ($setting == "") {
                        $setting = new SmGeneralSettings();
                    }
                    $setting->school_name = @$request->input('institution_name');
                    $setting->email = @$request->input('system_admin_email');
                    $setting->system_purchase_code = Session::get('purchasecode');
                    $setting->system_activated_date = date('Y-m-d');
                    $setting->system_domain = Session::get('domain');
                    // $to_do->academic_id = getAcademicId();
                    $setting->save();

                    $is= SmSchool::find(1);
                    $is->school_name= $request->institution_name;
                    $is->email= $request->system_admin_email;
                    $is->save(); 
                }

                $user = User::find(1);
                if (empty($user)) {
                    $user = new User();
                }

                $user->role_id = 1;
                $user->username = $request->input('system_admin_email');
                $user->full_name = 'System Administrator';
                $user->email = $request->input('system_admin_email');
                $user->password = Hash::make($request->input('system_admin_password'));
                $user->save();

                $staff = SmStaff::find(1);
                if (empty($staff)) {
                    $staff = new SmStaff();
                }
                $staff->user_id  = $user->id;
                $staff->first_name  = 'System';
                $staff->last_name  = 'Administrator';
                $staff->full_name  = 'System Administrator';
                $staff->email  = $request->input('system_admin_email');
                $staff->save();
                return redirect('confirmation');
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect('system-setup-page');
        }
    }


    public function verifiedCode()
    {

        // dd('k');
        try {
            if (Schema::hasTable('sm_general_settings')) {
                $GetData = DB::table('sm_general_settings')->find(1);
                if (!empty($GetData)) {
                    $UserData = Envato::verifyPurchase($GetData->system_purchase_code);
                    if (!empty($UserData['verify-purchase']['item_id']) && (User::$item == $UserData['verify-purchase']['item_id'])) {
                        return redirect('/login');
                    }
                } else {
                    return view('install.verified_code');
                }
            } else {
                return redirect('install');
            }
        } catch (\Exception $e) {
            // dd($e);
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function verifiedCodeStore(Request $request)
    {

        try {
            $envatouser = htmlspecialchars($request->input('envatouser'));
            $purchasecode = htmlspecialchars($request->input('purchasecode'));
            $domain = htmlspecialchars($request->input('installationdomain'));

            $obj = Envato::verifyPurchase($purchasecode);


            if (!empty($obj)) {
                foreach ($obj as $data) {
                    if (!empty($data['item_id'])) {

                        $setting = SmGeneralSettings::first();
                        $setting->system_domain = $domain;
                        $setting->envato_user = $envatouser;
                        $setting->system_purchase_code = $purchasecode;
                        $setting->envato_item_id = $data['item_id'];
                        $setting->system_activated_date = date('Y-m-d');
                        $setting->save();

                        $url = Session::get('url');

                        return redirect($url);
                    }
                }
            } else {
                Session::flash("message-danger", "Ops! Purchase Code is not vaild. Please try again.");
                return redirect()->back();
            }
            Session::flash("message-danger", "Ops! Purchase Code is not vaild. Please try again.");
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}