<?php

use Illuminate\Database\Seeder;
use App\SmSection;

class sm_sectionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // SmSection::query()->truncate();
        $data=['A','B','C','D','E'];
        foreach ($data as $row) {
            for ($i = 1; $i <= 4; $i++) {
                $s= new SmSection();
                $s->section_name=$row.' '.$i;
                $s->created_at = date('Y-m-d h:i:s');
                $s->school_id=$i;
                $s->save();
            }
        } 
    }
}
