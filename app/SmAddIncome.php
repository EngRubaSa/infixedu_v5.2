<?php

namespace App;

use App\SmItemSell;
use App\SmAddExpense;
use App\SmFeesPayment;
use App\SmItemReceive;
use App\SmHrPayrollGenerate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class SmAddIncome extends Model
{
    public function incomeHeads(){
    	return $this->belongsTo('App\SmIncomeHead','income_head_id', 'id');
    }

    public function ACHead(){
    	return $this->belongsTo('App\SmChartOfAccount','income_head_id', 'id');
    }

    public function account(){
    	return $this->belongsTo('App\SmBankAccount', 'account_id', 'id');
    }

    public function paymentMethod(){
    	return $this->belongsTo('App\SmPaymentMethhod', 'payment_method_id', 'id');
    }

    public static function monthlyIncome($i){
        try {
            $m_add_incomes = SmAddIncome::where('school_id',Auth::user()->school_id)->where('active_status', 1)->where('date', 'like', date('Y-m-').$i)->sum('amount');

            $m_fees_payments = SmFeesPayment::where('school_id',Auth::user()->school_id)->where('active_status', 1)->where('payment_date', 'like', date('Y-m-').$i)->sum('amount');

            $m_item_sells = SmItemSell::where('school_id',Auth::user()->school_id)->where('active_status', 1)->where('sell_date', 'like', date('Y-m-').$i)->sum('total_paid');

            $m_total_income = $m_add_incomes + $m_fees_payments + $m_item_sells;

            return $m_total_income;
        } catch (\Exception $e) {
            $data=[];
            return $data;
        }
    } 

    public static function monthlyExpense($i){
        try {
            $m_add_expenses = SmAddExpense::where('school_id',Auth::user()->school_id)->where('active_status', 1)->where('date', 'like', date('Y-m-').$i)->sum('amount');
                $m_item_receives = SmItemReceive::where('school_id',Auth::user()->school_id)->where('active_status', 1)->where('receive_date', 'like', date('Y-m-').$i)->sum('total_paid');
                $m_payroll_payments = SmHrPayrollGenerate::where('school_id',Auth::user()->school_id)->where('active_status', 1)->where('payroll_status', 'P')->where('created_at', 'like', date('Y-m-').$i)->sum('net_salary');
                $m_total_expense = $m_add_expenses + $m_item_receives + $m_payroll_payments;
                return $m_total_expense;
        } catch (\Exception $e) {
            $data=[];
            return $data;
        }
    } 


    public static function yearlyIncome($i){
        try {
            $y_add_incomes = SmAddIncome::where('school_id',Auth::user()->school_id)->where('active_status', 1)->where('date', 'like', date('Y-'.$i).'%')->sum('amount');
            $y_fees_payments = SmFeesPayment::where('school_id',Auth::user()->school_id)->where('active_status', 1)->where('payment_date', 'like', date('Y-'.$i).'%')->sum('amount');
            $y_item_sells = SmItemSell::where('school_id',Auth::user()->school_id)->where('active_status', 1)->where('sell_date', 'like', date('Y-'.$i).'%')->sum('total_paid');
            $y_total_income = $y_add_incomes + $y_fees_payments + $y_item_sells;
         
            return $y_total_income;
        } catch (\Exception $e) {
            $data=[];
            return $data;
        }
    } 

    public static function yearlyExpense($i){
        try {
            $m_add_expenses = SmAddExpense::where('school_id',Auth::user()->school_id)->where('active_status', 1)->where('date', 'like', date('Y-'.$i).'%')->sum('amount');
            $m_item_receives = SmItemReceive::where('school_id',Auth::user()->school_id)->where('active_status', 1)->where('receive_date', 'like', date('Y-'.$i).'%')->sum('total_paid');
            $m_payroll_payments = SmHrPayrollGenerate::where('school_id',Auth::user()->school_id)->where('active_status', 1)->where('payroll_status', 'P')->where('created_at', 'like', date('Y-'.$i).'%')->sum('net_salary');
            $m_total_expense = $m_add_expenses + $m_item_receives;
            return $m_total_expense;
        } catch (\Exception $e) {
            $data=[];
            return $data;
        }
    } 
}
