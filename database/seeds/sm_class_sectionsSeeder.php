<?php

use App\SmClass;
use App\SmSection;
use App\SmClassSection;
use Illuminate\Database\Seeder;

class sm_class_sectionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // SmClassSection::query()->truncate(); 

        for ($i = 1; $i <= 4; $i++) {
            $classes= SmClass::where('school_id', $i)->get();
            foreach ($classes as  $class) {
                 $sections=SmSection::where('school_id', $i)->get();
                foreach ($sections as $section) {
                    $s = new SmClassSection();
                    $s->class_id = $class->id;
                    $s->section_id = $section->id;
                    $s->school_id = $i;
                    $s->created_at = date('Y-m-d h:i:s');
                    $s->save();

                }
            } 
        }
    }
}
