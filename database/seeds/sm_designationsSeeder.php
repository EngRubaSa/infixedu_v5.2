<?php

use App\SmDesignation;
use Illuminate\Database\Seeder;

class sm_designationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        SmDesignation::query()->truncate();
        
      
        DB::table('sm_designations')->insert([
            
            [
                'title' => 'Assistant Head Master',
                'created_at' => date('Y-m-d h:i:s')
            ],
            [
                'title' => 'Assistant Teacher',
                'created_at' => date('Y-m-d h:i:s')
            ],
            [
                'title' => 'Senior Teacher',
                'created_at' => date('Y-m-d h:i:s')
            ],
            [
                'title' => 'Senior Assistant Teacher',
                'created_at' => date('Y-m-d h:i:s')
            ],
            [
                'title' => 'Faculty',
                'created_at' => date('Y-m-d h:i:s')
            ],
            [
                'title' => 'Accountant',
                'created_at' => date('Y-m-d h:i:s')
            ],
            [
                'title' => 'Librarian',
                'created_at' => date('Y-m-d h:i:s')
            ],
            [
                'title' => 'Admin',
                'created_at' => date('Y-m-d h:i:s')
            ],
            [
                'title' => 'Receptionist',
                'created_at' => date('Y-m-d h:i:s')
            ],
            [
                'title' => 'Principal',
                'created_at' => date('Y-m-d h:i:s')
            ],
            [
                'title' => 'Director',
                'created_at' => date('Y-m-d h:i:s')
            ]


        ]);
    }
}
