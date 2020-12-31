<?php

namespace App;

use App\SmStaff;
use App\SmParent;
use App\SmStudent;
use Illuminate\Database\Eloquent\Model;

class SmBook extends Model
{
    public function bookCategory(){
    	return $this->belongsTo('App\SmBookCategory', 'book_category_id', 'id');
    }
    
    public function bookSubject(){
    	return $this->belongsTo('App\SmSubject', 'subject', 'id');
    }

    public static function getMemberDetails($memberID)
    {
        
        try {
            return SmStudent::select('full_name', 'email', 'mobile')->where('id', '=', $memberID)->first();
        } catch (\Exception $e) {
            $data=[];
            return $data;
        }
    }

    public static function getMemberStaffsDetails($memberID){
        
        try {
            return SmStaff::select('full_name', 'email', 'mobile')->where('user_id', '=', $memberID)->first();
        } catch (\Exception $e) {
            $data=[];
            return $data;
        }
    }

    public static function getParentDetails($memberID){
        
        try {
            return $getMemberDetails = SmParent::select('full_name', 'email', 'mobile')->where('user_id', '=', $memberID)->first();
        } catch (\Exception $e) {
            $data=[];
            return $data;
        }
    }

    
}