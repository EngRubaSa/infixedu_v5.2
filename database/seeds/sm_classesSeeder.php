<?php

use App\SmClass;
use Illuminate\Database\Seeder;

class sm_classesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // SmClass::query()->truncate();


        $data = ['One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine','Ten'];
        foreach ($data as $row) {
            for ($i = 1; $i <= 4; $i++) {
                $s = new SmClass();
                $s->class_name = $row . ' ' . $i;
                $s->created_at = date('Y-m-d h:i:s');
                $s->school_id = $i;
                $s->save();
            }
        } 
 
    }
}
