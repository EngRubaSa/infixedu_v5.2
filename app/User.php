<?php

namespace App;

use App\Envato\Envato;
use GuzzleHttp\Client;
use App\SmGeneralSettings;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    public static $email = "info@spondonit.com";  //23876323 //22014245 //23876323
    public static $item = "23876323";  //23876323 //22014245 //23876323
    public static $api = "https://sp.uxseven.com/api/system-details";
    public static $apiModule = "https://sp.uxseven.com/api/module/";



    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'username', 'phone', 'password','is_administrator'
    ];



    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function student()
    {
        return $this->belongsTo('App\SmStudent', 'id', 'user_id');
    }
    public function staff()
    {
        return $this->belongsTo('App\SmStaff', 'id', 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(SmStudentCategory::class,'category_id','id');
    }
    public function group()
    {
        return $this->belongsTo(SmStudentGroup::class,'group_id','id');
    }

    public function parent()
    {
        return $this->belongsTo('App\SmParent', 'id', 'user_id');
    }

    public function school()
    {
        return $this->belongsTo('App\SmSchool', 'school_id', 'id');
    }

    public function roles()
    {
        return $this->belongsTo('Modules\RolePermission\Entities\InfixRole', 'role_id', 'id');
    }

    public function getProfileAttribute()
    {
        $role_id = Auth::user()->role_id;
        $student = SmStudent::where('user_id', Auth::user()->id)->first();
        $parent = SmParent::where('user_id', Auth::user()->id)->first();
        $staff = SmStaff::where('user_id', Auth::user()->id)->first();
        if ($role_id == 2)
            $profile = $student ? $student->student_photo : 'public/backEnd/img/admin/message-thumb.png';
        elseif ($role_id == 3)
            $profile = $parent ? $parent->fathers_photo : 'public/backEnd/img/admin/message-thumb.png';
        else
            $profile = $staff ? $staff->staff_photo : 'public/backEnd/img/admin/message-thumb.png';

        return $profile;
    }

    public static function checkAuth()
    {
        return true;
        if (!Schema::hasTable('sm_general_settings')) {
            return redirect('install');
        }
        $gcc = new SmGeneralSettings;
        $php_extension_dll = SmGeneralSettings::find(1);
        $str = $gcc::$students;
        $php_extension_ssl = Envato::aci($php_extension_dll->$str);
        if (isset($php_extension_ssl[$gcc::$users][$gcc::$parents])) {
            return User::$item == $php_extension_ssl[$gcc::$users][$gcc::$parents];
        } else {
            return FALSE;
        }
    }



    public static function checkPermission($name)
    {
        return true;
        $time_limit = 101;
        $is_data = InfixModuleManager::where('name', $name)->where('purchase_code', '!=', '')->first();
        if (!empty($is_data) && $is_data->email != null && $is_data->purchase_code != null) {
            $code = @$is_data->purchase_code;
            $email = @$is_data->email;
            $is_verify = SmGeneralSettings::where($name, 1)->first();
            if (!empty($is_verify)) {
                if (Config::get('app.app_pro')) {
                    try {
                        $client = new Client();
                        $product_info = $client->request('GET', User::$apiModule  . $code . '/' . $email);
                        $product_info = $product_info->getBody()->getContents();
                        $product_info = json_decode($product_info);
                        if (!empty($product_info->products[0])) {
                            $time_limit = 100;
                        } else {
                            $time_limit = 101;
                        }
                    } catch (\Exception $e) {
                        $time_limit = 102;
                    }
                } else {
                    $php_extension_ssl = Envato::aci($is_data->purchase_code);
                    if (!empty($php_extension_ssl['verify-purchase'])) {
                        $time_limit = 100;
                    } else {
                        $time_limit = 103;
                    }
                }
            }
        }
        return $time_limit;
    }
}