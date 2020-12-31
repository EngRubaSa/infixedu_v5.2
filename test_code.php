<?php



if($purchase_package->approve_status == 'approved'){


    if($purchase_package->buy_type == 'instantly'){

        $total_days  = $purchase_package->package->duration_days;

        $now_time = date('Y-m-d');
        $now_time =  date('Y-m-d', strtotime($now_time. ' + 1 days'));

        $datediff    = strtotime($now_time) - strtotime($purchase_package->start_date);
        $used_days   = round($datediff / (60 * 60 * 24));
        $remain_days = $total_days - $used_days;

        if($remain_days < 0){
            $remain_days = 0;
        }
       


       
        // 
        if($purchase_package->id == $last_record->id && $last_record->buy_type == 'instantly'){

            if($now_time >= $purchase_package->start_date && $now_time <= $purchase_package->end_date){

                $status = 'Active';
                $color = 'text-success';
                $active_package = $purchase_package->package_id;

            }else{
                $status = 'Inactive';
                $color = 'text-danger';

            }

        }else{

            // start i think this part not require
            $now_time = date('Y-m-d');
            $now_time =  date('Y-m-d', strtotime($now_time. ' + 1 days'));

            if($now_time >= $purchase_package->start_date && $now_time <= $purchase_package->end_date  && $last_record->buy_type == 'buy_now'){

                $status = 'Active';
                $color = 'text-success';
            // end i think this part not require

            }else{

                $status = 'Inactive';
                $color = 'text-danger';

            }
        }
    }

    if($purchase_package->buy_type == 'buy_now'){

        $now_time = date('Y-m-d');
        $now_time =  date('Y-m-d', strtotime($now_time. ' + 1 days'));

        if($now_time >= $purchase_package->start_date && $now_time <= $purchase_package->end_date  && $last_record->buy_type == 'buy_now'){

            $total_days  = $purchase_package->package->duration_days;
            $datediff    = strtotime($now_time) - strtotime($purchase_package->start_date);
            $used_days   = round($datediff / (60 * 60 * 24));
            $remain_days = $total_days - $used_days;

            if($remain_days < 0){
                $remain_days = 0;
            }

            $active_package = $purchase_package->package_id;

            $status = 'Active';
            $color = 'text-success';

        }else{

            $remain_days = $purchase_package->package->duration_days;
            $status = 'Inactive';
            $color = 'text-danger';

        }
    }
}else{

    if($purchase_package->payment_type == 'trial'){

        $total_days  = $purchase_package->package->trial_days;

        $now_time = date('Y-m-d');
        $now_time =  date('Y-m-d', strtotime($now_time. ' + 1 days'));
        $datediff    = strtotime($now_time) - strtotime($purchase_package->start_date);
        $used_days   = round($datediff / (60 * 60 * 24));
        $remain_days = $total_days - $used_days;

        if($remain_days < 0){
            $remain_days = 0;
        }

        if($purchase_packages->count() == 1){
            $active_package = $purchase_package->package_id;
            $status = 'Active/Trial';
            $color = 'text-success';
        }else{
            $status = 'Inactive/Trial';
            $color = 'text-danger';
        }
        
    }else{

        $remain_days = $purchase_package->package->duration_days;
        if($remain_days < 0){
            $remain_days = 0;
        }   

        $status = 'Inactive';
        $color = 'text-danger';

    }

}