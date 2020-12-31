<?php

use App\SmStaff;
use App\SmStaffAttendence;
use Illuminate\Database\Seeder;

use Faker\Factory as Faker;

class sm_staff_attendencesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        SmStaffAttendence::query()->truncate();
        $staffList = SmStaff::all();
        foreach ($staffList as $staff) {
            // $m = date('m');
            for ($m = 1; $m <= 2; $m++) {
                for ($d = 1; $d <= 30; $d++) {
                    if ($d <= 9) {
                        $d = '0' . $d;
                    }
                    $str = date('Y') . '-' . $m . '-' . $d;
                    if ($d % 3 == 0) {
                        $status = 'A';
                    } elseif ($d % 3 == 1) {
                        $status = 'L';
                    } else {
                        $status = 'P';
                    }
                    if ($m == 2 && $d == 28) {
                        break;
                    }

                    $sa                  = new SmStaffAttendence();
                    $sa->staff_id      = $staff->id;
                    $sa->attendence_type = $status;
                    $sa->notes           = 'Sample Attendance for ' . $str;
                    $sa->attendence_date = $str;
                    $sa->created_at = date('Y-m-d h:i:s');
                    $sa->save();
                }
            }
        }
    }
}
