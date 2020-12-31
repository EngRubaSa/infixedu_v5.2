<?php 
namespace App\Providers;
use App\SmStaff;
use App\SmStudent;
use App\SmStyle;
use App\SmParent;
use App\SmSchool;
use App\SmLanguage;
use App\SmsTemplate;
use App\SmCustomLink;
use App\SmDateFormat;
use App\SmAcademicYear;
use App\SmNotification;
use App\SmGeneralSettings;
use App\SmSocialMediaIcon;
use App\SmFrontendPersmission;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Builder;
use Illuminate\Support\ServiceProvider;
// use App\Providers\TelescopeServiceProvider;
use Modules\ParentRegistration\Entities\SmRegistrationSetting;


class AppServiceProvider extends ServiceProvider
{

    public function boot()
    {
        // Passport::routes();
        try{
            Builder::defaultStringLength(191);
            view()->composer('backEnd.parentPanel.parent_dashboard', function ($view) {
                $data =[
                    'childrens' => SmParent::myChildrens(),
                ];
                $view->with($data);
            });
            view()->composer('backEnd.partials.parents_sidebar', function ($view) {
                $data =[
                    'childrens' => SmParent::myChildrens(),
                ];
                $view->with($data);
            });

            view()->composer('backEnd.master', function ($view) {
                if (Schema::hasTable('sm_general_settings')) {
                    $data =[
                        'notifications' => SmNotification::notifications(),
                    ];
                    $view->with($data);
                }
            });


        }
        catch(\Exception $e){
            
            return false;
        }

    }

    public function register()
    {
            

    }
}