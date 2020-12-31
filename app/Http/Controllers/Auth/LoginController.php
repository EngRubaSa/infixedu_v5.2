<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\SmStaff;
use App\SmStyle;
use App\SmSchool;
use App\SmStudent;
use App\SmUserLog;
use App\SmLanguage;
use App\SmsTemplate;
use App\SmDateFormat;
use App\SmAcademicYear;
use App\SmGeneralSettings;
use App\InfixModuleManager;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;
use App\SmBackgroundSetting;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiter;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Events\Lockout;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Modules\RolePermission\Entities\InfixRole;
use Modules\RolePermission\Entities\InfixPermissionAssign;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
     */

    /**
     * Get the post register / login redirect path.
     *
     * @return string
     */
    public function redirectPath()
    {
        if (method_exists($this, 'redirectTo')) {
            return $this->redirectTo();
        }

        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/home';
    }

    /**
     * Determine if the user has too many failed login attempts.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function hasTooManyLoginAttempts(Request $request)
    {
        return $this->limiter()->tooManyAttempts(
            $this->throttleKey($request),
            $this->maxAttempts()
        );
    }

    /**
     * Increment the login attempts for the user.
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    protected function incrementLoginAttempts(Request $request)
    {
        $this->limiter()->hit(
            $this->throttleKey($request),
            $this->decayMinutes()
        );
    }

    /**
     * Redirect the user after determining they are locked out.
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendLockoutResponse(Request $request)
    {
        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($request)
        );

        throw ValidationException::withMessages([
            $this->username() => [Lang::get('auth.throttle', ['seconds' => $seconds])],
        ])->status(429);
    }

    /**
     * Clear the login locks for the given user credentials.
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    protected function clearLoginAttempts(Request $request)
    {
        $this->limiter()->clear($this->throttleKey($request));
    }

    /**
     * Fire an event when a lockout occurs.
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    protected function fireLockoutEvent(Request $request)
    {
        event(new Lockout($request));
    }

    /**
     * Get the throttle key for the given request.
     *
     * @param \Illuminate\Http\Request $request
     * @return string
     */
    protected function throttleKey(Request $request)
    {
        return Str::lower($request->input($this->username())) . '|' . $request->ip();
    }

    /**
     * Get the rate limiter instance.
     *
     * @return \Illuminate\Cache\RateLimiter
     */
    protected function limiter()
    {
        return app(RateLimiter::class);
    }

    /**
     * Get the maximum number of attempts to allow.
     *
     * @return int
     */
    public function maxAttempts()
    {
        return property_exists($this, 'maxAttempts') ? $this->maxAttempts : 5;
    }

    /**
     * Get the number of minutes to throttle for.
     *
     * @return int
     */
    public function decayMinutes()
    {
        return property_exists($this, 'decayMinutes') ? $this->decayMinutes : 1;
    }

    public function login(Request $request)
    {


        // return $request;
        $this->validateLogin($request);
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            $infixRole = InfixRole::find(Auth::user()->role_id);

            $module_links = [];
            if ($infixRole->is_saas == 1) {
                $permissions = InfixPermissionAssign::where('role_id', Auth::user()->role_id)->get(['id', 'module_id']);
            }
            if ($infixRole->is_saas == 0) {
                $permissions = InfixPermissionAssign::where('role_id', Auth::user()->role_id)->where('school_id', Auth::user()->school_id)->get(['id', 'module_id']);
            }
            foreach ($permissions as $permission) {
                $module_links[] = $permission->module_id;
            }
            //All user permission module id save in session
            session()->put('permission', $module_links);

            // System date format save in session 
            $date_format_id = SmGeneralSettings::where('id', 1)->first(['date_format_id'])->date_format_id;
            $system_date_format = SmDateFormat::where('id', $date_format_id)->first(['format'])->format;
            session()->put('system_date_format', $system_date_format);

            // System academic session id in session
            $session_id = SmGeneralSettings::where('school_id', Auth::user()->school_id)->first('session_id')->session_id;
            $sessionId = session()->put('sessionId', $session_id);

            $generalSettings = SmGeneralSettings::find(1);
            session()->put('generalSetting',$generalSettings);

            $all_module = [];
            $modules = InfixModuleManager::select('name')->get();
            foreach ($modules as $module) {
                $all_modules[] = $module->name;
            }

            session()->put('all_module', $all_modules);

            //Session put text decoration
            $ttl_rtl = SmGeneralSettings::where('school_id', Auth::user()->school_id)->first('ttl_rtl')->ttl_rtl;
            session()->put('text_direction', $ttl_rtl);

            //Session put generalSetting
            $generalSetting = SmGeneralSettings::find(1);
            session()->put('generalSetting',$generalSetting);

            $system_date_format = SmDateFormat::find($generalSetting->date_format_id);
            session()->put('system_date_foramt', $system_date_format);

            $active_style = SmStyle::where('school_id', 1)->where('is_active', 1)->first() ;
            SmStyle::where('school_id', 1)->where('is_active', 1)->first();
            session()->put('active_style', $active_style);

            $all_styles = SmStyle::where('school_id', 1)->where('active_status', 1)->get() ;
            session()->put('all_styles', $all_styles);

            //Session put activeLanguage
            $systemLanguage = SmLanguage::where('school_id', Auth::user()->school_id)->get();
            session()->put('systemLanguage',$systemLanguage);
            //session put academic years
            $academic_years = Auth::check() ? SmAcademicYear::where('active_status', 1)->where('school_id', Auth::user()->school_id)->get() : '';
            session()->put('academic_years',$academic_years);
            //session put sessions and selected language
            $selected_language = Auth::check() ? SmGeneralSettings::where('school_id', Auth::user()->school_id)->first() :
            SmGeneralSettings::where('school_id', 1)->first();
            session()->put('selected_language', $selected_language);
            $session = DB::table('sm_academic_years')->where('id', $selected_language->session_id)->first();
            session()->put('session', $session);

            if(Auth::user()->role_id == 2){
                $profile =  SmStudent::where('user_id', Auth::id())->first('student_photo');
                session()->put('profile', @$profile->student_photo);
            }

            else{
                $profile = SmStaff::where('user_id', Auth::id())->first();
                if ($profile)
                    session()->put('profile', $profile->staff_photo);
            }
            

            $school_config = Auth::check() ? SmGeneralSettings::where('school_id', Auth::user()->school_id)->first() :
            SmGeneralSettings::where('school_id', Auth::user()->school_id)->first();
            session()->put('school_config', $school_config);

            $dashboard_background = DB::table('sm_background_settings')->where([['is_default', 1], ['title', 'Dashboard Background']])->first();
            session()->put('dashboard_background', $dashboard_background);

            $email_template = SmsTemplate::where('id', 1)->first();
            session()->put('email_template', $email_template);

            $school = SmSchool::where('id', Auth::user()->school_id)->first();

            if ($school->active_status == 1) {

                if (Auth::user()->active_status != 0 && Auth::user()->access_status != 0) {


                    if ($school->is_enabled == 'yes') {

                        session(['role_id' => Auth::user()->role_id]);
                        $agent = new Agent();
                        $user_log = new SmUserLog();
                        $user_log->user_id = Auth::user()->id;
                        $user_log->role_id = Auth::user()->role_id;
                        $user_log->school_id = Auth::user()->school_id;
                        $user_log->ip_address = $request->ip();
                        $user_log->academic_id = getAcademicid() ?? 1;
                        $user_log->user_agent = $agent->browser() . ', ' . $agent->platform();
                        $user_log->save();

                        return $this->sendLoginResponse($request);
                    } else {

                        $this->guard()->logout();
                        Toastr::error('Your Institution is not Approved, Please contact with administrator.', 'Failed');
                        return redirect('login');
                    }
                } else {
                    $this->guard()->logout();
                    Toastr::error('You are not allowed, Please contact with administrator.', 'Failed');
                    return redirect('login');
                }
            } else {
                $this->guard()->logout();
                Toastr::error('Your Institution is not Approved, Please contact with administrator.', 'Failed');
                return redirect('login');
            }
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Validate the user login request.
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {

        if (moduleStatusCheck('Saas') == TRUE) {
            $request->validate(
                [
                    $this->username() => 'required|string',
                    'password' => 'required|string',
                    'school_id' => 'required',
                ],
                [
                    'school_id.required' => 'The School field is required!'
                ]
            );
        } else {
            $request->validate([
                $this->username() => 'required|string',
                'password' => 'required|string',
            ]);
        }
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param \Illuminate\Http\Request $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        return $this->guard()->attempt(
            $this->credentials($request),
            $request->filled('remember')
        );
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {

            return $request->only($this->username(), 'password', 'school_id');
        } else {

            return $request->only('username', 'password', 'school_id');
        }
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        return $this->authenticated($request, $this->guard()->user())
            ?: redirect()->intended($this->redirectPath());
    }

    /**
     * The user has been authenticated.
     *
     * @param \Illuminate\Http\Request $request
     * @param mixed $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        //
    }

    /**
     * Get the failed login response instance.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'email';
    }

    /**
     * Log the user out of the application.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    // public function logout(Request $request)
    // {
    //     $this->guard()->logout();
    //     $request->session()->invalidate();

    //     session_destroy();
    //     session(['role_id' => '']);

    //     return $this->loggedOut($request) ?: redirect('/');
    // }

    /**
     * The user has logged out of the application.
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    protected function loggedOut(Request $request)
    {
        //
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/after-login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function loginFormTwo()
    {


        if (Schema::hasTable('users')) {
            $login_background = SmBackgroundSetting::where([['is_default', 1], ['title', 'Login Background']])->first();
            if (empty($login_background)) {
                $css = "background: url(" . url('public/backEnd/login2/img/login-bg.png') . ")  no-repeat center; background-size: cover; ";
            } else {
                if (!empty($login_background->image)) {
                    $css = "background: url('" . url($login_background->image) . "')  no-repeat center;  background-size: cover;";

                } else {
                    $css = "background:" . $login_background->color;
                }
            }
            if (moduleStatusCheck('Saas') == TRUE) {

                $schools = SmSchool::where('id', '!=', 1)->orderBy('school_name', 'asc')->get();
                $sch = SmSchool::find(1);

                $users = User::where('role_id', 1)->whereIn('school_id', [1, 2, 3, 4])->get();
                $user1 = $users->where('school_id', 1)->first();
                $user2 = $users->where('school_id', 2)->first();
                $user3 = $users->where('school_id', 3)->first();
                $user4 = $users->where('school_id', 4)->first();
                return view('saas::auth.saas_login', compact('schools', 'sch', 'css', 'user1', 'user2', 'user3', 'user4'));
            } else {
                $users = User::whereIn('role_id', [1,2,3,4, 5, 6,7,8])->select('role_id','email')->get();
                
                $user_1 = $users->where('role_id',1)->first();
                $user_2 = $users->where('role_id',2)->first();
                $user_3 = $users->where('role_id',3)->first();
                $user_4 = $users->where('role_id',4)->first();
                $user_5 = $users->where('role_id',5)->first();
                $user_6 = $users->where('role_id',6)->first();
                $user_7 = $users->where('role_id',7)->first();
                $user_8 = $users->where('role_id',8)->first();
                $data = [
                  'user_1' => $user_1,
                  'user_2' => $user_2,
                  'user_3' => $user_3,
                  'user_4' => $user_4,
                  'user_5' => $user_5,
                  'user_6' => $user_6,
                  'user_7' => $user_7,
                  'user_8' => $user_8,
                ];

                return view('auth.loginCodeCanyon', compact('css', 'data','user_1','user_2','user_3','user_4','user_5','user_6','user_7','user_8'))->with($data);
            }
        } else {
            return redirect('install');
        }
    }

    public function loginCodeCanyon()
    {
        if (Schema::hasTable('users')) {
            $login_background = SmBackgroundSetting::where([['is_default', 1], ['title', 'Login Background']])->first();
            if (empty($login_background)) {
                $css = "background: url(" . url('public/backEnd/img/login-bg.jpg') . ")  no-repeat center; background-size: cover; ";
            } else {
                if (!empty($login_background->image)) {
                    $css = "background: url('" . url($login_background->image) . "')  no-repeat center;  background-size: cover;";

                } else {
                    $css = "background:" . $login_background->color;
                }
            }
            $users = User::whereIn('role_id', [1,2,3,4, 5, 6,7,8])->select('email')->get();
            $data = [
                'user_1' => $users->where('role_id',1)->first(),
                'user_2' => $users->where('role_id',2)->first(),
                'user_3' => $users->where('role_id',3)->first(),
                'user_4' => $users->where('role_id',4)->first(),
                'user_5' => $users->where('role_id',5)->first(),
                'user_6' => $users->where('role_id',6)->first(),
                'user_7' => $users->where('role_id',7)->first(),
                'user_8' => $users->where('role_id',8)->first(),
            ];
            return view('auth.loginCodeCanyon', compact('css'))->with($data);
        } else {
            return redirect('install');
        }
    }


    //user logout method
    public function logout(Request $request)
    {

        $user = Auth::user();


        if (@$user->is_saas == 1) {
            $user->school_id = SmSchool::first('id')->id;
            $user->save();
        }
        Session::flush();
        Auth::logout();
        return redirect('/login');
    }
}