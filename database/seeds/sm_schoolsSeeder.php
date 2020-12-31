<?php

use App\User;
use App\SmStaff;
use App\SmSchool;
use App\SmGeneralSettings;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\SaasSubscription\Entities\SmPackagePlan;
use Modules\SaasSubscription\Entities\SmSubscriptionPayment;

class sm_schoolsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        if(moduleStatusCheck('Saas') == true){

       
        
        for($i=2; $i<=5; $i++){

            $email= $faker->email;
            $school= $faker->company. ' School';

            $store= new SmSchool();
            $store->school_name= $school;
            $store->email= $email;
            $store->created_at = date('Y-m-d h:i:s');
            $store->starting_date = date('Y-m-d');
            $store->is_email_verified = 1;
            $store->save();
            
            $general_setting = new SmGeneralSettings();
            $general_setting->school_name = $school;
            $general_setting->email = $email;
            $general_setting->address = '';
            $general_setting->school_code = '';
            $general_setting->school_id = $i;
            $general_setting->phone = '';
            $general_setting->time_zone_id = 1;
            $general_setting->save();


          
            $user            = new User();
            $user->role_id   = 1;
            $user->full_name = $faker->name;
            $user->email     = $email;
            $user->username  = $email;
            $user->password  = Hash::make('123456');
            $user->school_id = $i;
            $user->save();
            $user->toArray();

            //user details
            $staff                  = new SmStaff();
            $staff->user_id         = $user->id;
            $staff->role_id         = 1;
            $staff->staff_no        = 1;
            $staff->designation_id  = 1;
            $staff->department_id   = 1;
            $staff->first_name      = 'School';
            $staff->last_name       = 'Admin';
            $staff->full_name       = 'School Admin';
            $staff->date_of_birth   = '1980-12-26';
            $staff->date_of_joining = '2019-05-26';
            $staff->gender_id       = 1;
            $staff->school_id       = $i;
            $staff->email           = $email;
            $staff->staff_photo     = 'public/uploads/staff/1_infix_edu.jpg';
            $staff->casual_leave    = '12';
            $staff->medical_leave   = '15';
            $staff->metarnity_leave = '15';
            $staff->save();

        }

        }
 
        
    }
}