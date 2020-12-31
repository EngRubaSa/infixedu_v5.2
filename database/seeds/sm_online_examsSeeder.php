<?php

use App\SmAssignSubject;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\SmOnlineExam;

class sm_online_examsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $i = 1;
        $question_details = SmAssignSubject::where('class_id', 1)->where('section_id', 1)->get();
        foreach ($question_details as $question_detail) {
            $store = new SmOnlineExam();
            $store->subject_id = $question_detail->subject_id;
            $store->class_id = $question_detail->class_id;
            $store->section_id = $question_detail->section_id;
            $store->title = $faker->realText($maxNbChars = 30, $indexSize = 1);
            $store->date = date('Y-m-d');
            $store->start_time = '10:00 AM';
            $store->end_time = '11:00 AM';
            $store->end_date_time = date('Y-m-d') . " 11:00 AM";
            $store->percentage = 50;
            $store->instruction = $faker->realText($maxNbChars = 100, $indexSize = 1);
            $store->status = 1;
            $store->created_at = date('Y-m-d h:i:s');
            $store->save();
        }
    }
}
