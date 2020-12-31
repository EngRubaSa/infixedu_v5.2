<?php

use App\SmHumanDepartment;
use Illuminate\Database\Seeder;
class sm_human_departmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
     
        DB::table('sm_human_departments')->insert([
            [
               'name' => 'Academic',
               'created_at' => date('Y-m-d h:i:s')
            ],
            [
               'name' => 'Arts',
               'created_at' => date('Y-m-d h:i:s')
            ],
            [
               'name' => 'Commerce',
               'created_at' => date('Y-m-d h:i:s')
            ],
            [
               'name' => 'Library',
               'created_at' => date('Y-m-d h:i:s')
            ],
            [
               'name' => 'Sports',
               'created_at' => date('Y-m-d h:i:s')
            ],
            [
               'name' => 'Science',
               'created_at' => date('Y-m-d h:i:s')
            ],
            [
               'name' => 'Exam',
               'created_at' => date('Y-m-d h:i:s')
            ],
            [
               'name' => 'Finance',
               'created_at' => date('Y-m-d h:i:s')
            ],
            [
               'name' => 'Health',
               'created_at' => date('Y-m-d h:i:s')
            ],
            [
               'name' => 'Technology',
               'created_at' => date('Y-m-d h:i:s')
            ],
            [
               'name' => 'Music and Theater',
               'created_at' => date('Y-m-d h:i:s')
            ]
         ]);

    }
}
