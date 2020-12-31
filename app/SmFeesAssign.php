<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmFeesAssign extends Model
{
    public function feesGroupMaster(){
    	return $this->belongsTo('App\SmFeesMaster', 'fees_master_id', 'id');
    }

    public function feesPayments(){
    	return $this->hasMany('App\SmFeesPayment', 'id', 'fees_type_id');
    }

    public function getDiscountSumAttribute()
    {
        return $this->feesPayments()->where('student_id', $this->student_id)->where('fees_type_id', $this->feesGroupMaster->feesTypes->id)->sum('discount_amount');
    }
    public function getTotalPaidAttribute()
    {
        return $this->feesPayments()->where('student_id', $this->student_id)->where('fees_type_id', $this->feesGroupMaster->feesTypes->id)->sum('amount');
        // return $this->student_id;
    }
    public function getTotalFineAttribute()
    {
        return $this->feesPayments()->where('student_id', $this->student_id)->where('fees_type_id', $this->feesGroupMaster->feesTypes->id)->sum('fine');
    }

    public static function discountSum($student_id, $type_id, $perpose)
    {
        try {
            $sum = SmFeesPayment::where('student_id', $student_id)->where('fees_type_id', $type_id)->sum($perpose);
                return $sum;
        } catch (\Exception $e) {
            $data=[];
            return $data;
        }
    }

    public static function createdBy($student_id, $discount_id){
    	
        try {
            $created_by = SmFeesPayment::where('student_id', $student_id)->where('fees_discount_id', $discount_id)->first();
                return $created_by;
        } catch (\Exception $e) {
            $data=[];
            return $data;
        }
    }

    public static function feesPayment($type_id, $student_id){
        try {
            $payments = SmFeesPayment::where('fees_type_id', $type_id)->where('student_id', $student_id)->get();
                return $payments;
        } catch (\Exception $e) {
            $data=[];
            return $data;
        }
    }

    public function studentInfo(){
        return $this->belongsTo('App\SmStudent', 'student_id', 'id');
    }
}
