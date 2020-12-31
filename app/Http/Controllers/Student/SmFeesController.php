<?php

namespace App\Http\Controllers\Student;

use Stripe;
use App\SmStudent;
use App\YearCheck;
use App\SmFeesAssign;
use App\SmFeesMaster;
use App\SmFeesPayment;
use App\SmNotification;
use App\SmPaymentMethhod;
use App\SmBankPaymentSlip;
use App\SmGeneralSettings;
use Illuminate\Http\Request;
use App\SmFeesAssignDiscount;
use App\SmPaymentGatewaySetting;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Unicodeveloper\Paystack\Paystack;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class SmFeesController extends Controller
{
    public $paystack;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('PM');
        // User::checkAuth();
        $this->paystack = new Paystack();
    }



    public function studentFees()
    {
        try {
            $student = Auth::user()->student;

            $payment_gateway = SmPaymentMethhod::where('active_status', 1)->where('school_id', Auth::user()->school_id)->first();

            $fees_assigneds = SmFeesAssign::where('student_id', $student->id)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $fees_discounts = SmFeesAssignDiscount::where('student_id', $student->id)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();

            $applied_discount = [];
            foreach ($fees_discounts as $fees_discount) {
                $fees_payment = SmFeesPayment::select('fees_discount_id')->where('fees_discount_id', $fees_discount->id)->where('school_id', Auth::user()->school_id)->first();
                if (isset($fees_payment->fees_discount_id)) {
                    $applied_discount[] = $fees_payment->fees_discount_id;
                }
            }

            $paystack_info = DB::table('sm_payment_gateway_settings')->where('gateway_name', 'Paystack')->where('school_id', Auth::user()->school_id)->first();


            $data['bank_info'] = SmPaymentMethhod::where('method', 'Bank')->where('school_id', Auth::user()->school_id)->first();
            $data['cheque_info'] = SmPaymentMethhod::where('method', 'Cheque')->where('school_id', Auth::user()->school_id)->first();

            return view('backEnd.studentPanel.fees_pay', compact('student', 'fees_assigneds', 'fees_discounts', 'applied_discount', 'payment_gateway', 'paystack_info', 'data'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function redirectToGateway(Request $request)
    {
        try {


            $paystack_info = DB::table('sm_payment_gateway_settings')->where('gateway_name', 'Paystack')->where('school_id', Auth::user()->school_id)->first();



            Config::set('paystack.publicKey', $paystack_info->gateway_publisher_key);
            Config::set('paystack.secretKey', $paystack_info->gateway_secret_key);
            Config::set('paystack.merchantEmail', $paystack_info->gateway_username);



            Session::put('fees_type_id', $request->fees_type_id);
            Session::put('student_id', $request->student_id);
            Session::put('fees_master_id', $request->fees_master_id);
            Session::put('amount', $request->amount);
            Session::put('payment_mode', $request->payment_mode);

            $this->paystack = new Paystack();


            return $this->paystack->getAuthorizationUrl()->redirectNow();
        } catch (\Exception $e) {
            // dd($e->getMessage());
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    /**PSm
     * Obtain Paystack payment information
     * @return void
     */
    public function handleGatewayCallback()
    {
        try {
            //$paymentDetails = $this->paystack->getPaymentData();
            $user = Auth::User();

            // $student = SmStudent::where('user_id', $id)->where('school_id',Auth::user()->school_id)->first();

            $amount = Session::get('amount');
            $amount = $amount / 100;
            $fees_master_id = Session::get('fees_master_id');
            $fees_payment = new SmFeesPayment();
            $fees_payment->student_id = Session::get('student_id');
            $fees_payment->fees_type_id = Session::get('fees_type_id');

            $fees_payment->discount_amount = 0;
            $fees_payment->fine = 0;
            $fees_payment->amount = $amount;
            $fees_payment->payment_date = date('Y-m-d', strtotime(date('Y-m-d')));
            $fees_payment->payment_mode = 'PS';
            $fees_payment->school_id = Auth::user()->school_id;
            $result = $fees_payment->save();


            $get_master_id=SmFeesMaster::join('sm_fees_assigns','sm_fees_assigns.fees_master_id','=','sm_fees_masters.id')
            ->where('sm_fees_masters.fees_type_id',$fees_payment->fees_type_id)
            ->where('sm_fees_assigns.student_id',$fees_payment->student_id)->first();

          

            $fees_assign=SmFeesAssign::where('fees_master_id',$get_master_id->fees_master_id)->where('student_id',$fees_payment->student_id)->first();
            $fees_assign->fees_amount-=$amount;
            $fees_assign->save();
            // $notification=new SmNotification();
            // $notification->date=$fees_payment->created_at;
            // $notification->url=$fees_payment->created_at;

            if ($result) {
                if ($user->role_id == 2) {
                    Toastr::success('Operation successful', 'Success');
                    return redirect('student-fees');
                    // return redirect('student-fees')->with('message-success', 'Fees payment has been collected  successfully');
                } else {
                    Toastr::success('Operation successful', 'Success');
                    return redirect('parent-fees/' . Session::get('student_id'));
                    // return redirect('parent-fees/'.Session::get('student_id'))->with('message-success', 'Fees payment has been collected  successfully');
                }
            } else {
                if ($user->role_id == 2) {
                    Toastr::error('Operation Failed', 'Failed');
                    return redirect('student-fees');
                    // return redirect('student-fees')->with('message-danger', 'Something went wrong, please try again');
                } else {
                    Toastr::error('Operation Failed', 'Failed');
                    return redirect('student-fees');
                    // return redirect('student-fees')->with('message-danger', 'Something went wrong, please try again');
                }
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function feesPaymentStripe($fees_type, $student_id, $amount)
    {
        $stripe_info = SmPaymentGatewaySetting::where('gateway_name', 'stripe')->where('school_id', Auth::user()->school_id)->first();
        return view('backEnd.studentPanel.stripe_payment', compact('stripe_info', 'fees_type', 'student_id', 'amount'));
    }

    public function feesPaymentStripeStore(Request $request)
    {

        // try {


        $payment_setting = SmPaymentGatewaySetting::where('gateway_name', 'Stripe')->where('school_id', Auth::user()->school_id)->first();

        Stripe\Stripe::setApiKey($payment_setting->gateway_secret_key);

        Stripe\Charge::create([
            "amount" => $request->amount * 100,
            "currency" => "usd",
            "source" => $request->stripeToken,
            "description" => "Test payment from InfixEdu."
        ]);

        $user = Auth::User();

        // $student = SmStudent::where('user_id', $id)->where('school_id',Auth::user()->school_id)->first();


        $fees_payment = new SmFeesPayment();
        $fees_payment->student_id = $request->student_id;
        $fees_payment->fees_type_id = $request->fees_type;

        $fees_payment->discount_amount = 0;
        $fees_payment->fine = 0;
        $fees_payment->amount = $request->amount;
        $fees_payment->payment_date = date('Y-m-d', strtotime(date('Y-m-d')));
        $fees_payment->payment_mode = 'ST';
        $fees_payment->school_id = Auth::user()->school_id;
        $result = $fees_payment->save();

        // $notification=new SmNotification();
        // $notification->date=$fees_payment->created_at;
        // $notification->url=$fees_payment->created_at;

        $get_master_id=SmFeesMaster::join('sm_fees_assigns','sm_fees_assigns.fees_master_id','=','sm_fees_masters.id')
            ->where('sm_fees_masters.fees_type_id',$request->fees_type)
            ->where('sm_fees_assigns.student_id',$request->student_id)->first();

        $fees_assign=SmFeesAssign::where('fees_master_id',$get_master_id->fees_master_id)->where('student_id',$fees_payment->student_id)->first();
        $fees_assign->fees_amount-=$request->amount;
        $fees_assign->save();

        if ($result) {
            if ($user->role_id == 2) {
                Toastr::success('Operation successful', 'Success');
                return redirect('student-fees');
                // return redirect('student-fees')->with('message-success', 'Fees payment has been collected  successfully');
            } else {
                Toastr::success('Operation successful', 'Success');
                return redirect('parent-fees/' . $request->student_id);
                // return redirect('parent-fees/'.Session::get('student_id'))->with('message-success', 'Fees payment has been collected  successfully');
            }
        } else {
            if ($user->role_id == 2) {
                Toastr::error('Operation Failed', 'Failed');
                return redirect('student-fees');
                // return redirect('student-fees')->with('message-danger', 'Something went wrong, please try again');
            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect('parent-fees/' . $request->student_id);
                // return redirect('student-fees')->with('message-danger', 'Something went wrong, please try again');
            }
        }
        // } catch (\Exception $e) {
        //     Toastr::error('Operation Failed', 'Failed');
        //     return redirect()->back();
        // }

    }

    public function feesGenerateModalChild(Request $request, $amount, $student_id, $type)
    {
        // dd($student_id);
        try {
            $amount = $amount;
            $fees_type_id = $type;
            $student_id = $student_id;
            $std_info = SmStudent::where('id',$student_id)->select('class_id','section_id')->first();
            // dd($std_info);
            $discounts = SmFeesAssignDiscount::where('student_id', $student_id)->where('school_id', Auth::user()->school_id)->get();

                $applied_discount = [];
                foreach ($discounts as $fees_discount) {
                    $fees_payment = SmFeesPayment::select('fees_discount_id')->where('fees_discount_id', $fees_discount->id)->where('school_id', Auth::user()->school_id)->first();
                    if (isset($fees_payment->fees_discount_id)) {
                        $applied_discount[] = $fees_payment->fees_discount_id;
                    }
                }


            $data['bank_info'] = SmPaymentGatewaySetting::where('gateway_name', 'Bank')->where('school_id', Auth::user()->school_id)->first();
            $data['cheque_info'] = SmPaymentGatewaySetting::where('gateway_name', 'Cheque')->where('school_id', Auth::user()->school_id)->first();


            $method['bank_info'] = SmPaymentMethhod::where('method', 'Bank')->where('school_id', Auth::user()->school_id)->first();
            $method['cheque_info'] = SmPaymentMethhod::where('method', 'Cheque')->where('school_id', Auth::user()->school_id)->first();


            return view('backEnd.studentPanel.fees_generate_modal_child', compact('amount', 'discounts', 'fees_type_id', 'student_id', 'std_info','applied_discount', 'data', 'method'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function childBankSlipStore(Request $request)
    {
        // return $request;
        $request->validate([
            'slip' => "sometimes|nullable|mimes:pdf,doc,docx,jpg,jpeg,png,txt",
            'amount'=> "required",
        ]);
        try {

            $fileName = "";
            if ($request->file('slip') != "") {
                $maxFileSize = SmGeneralSettings::first('file_size')->file_size;
                $file = $request->file('slip');
                $fileSize =  filesize($file);
                $fileSizeKb = ($fileSize / 1000000);
                if($fileSizeKb >= $maxFileSize){
                    Toastr::error( 'Max upload file size '. $maxFileSize .' Mb is set in system', 'Failed');
                    return redirect()->back();
                }
                $file = $request->file('slip');
                $fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                $file->move('public/uploads/bankSlip/', $fileName);
                $fileName = 'public/uploads/bankSlip/' . $fileName;
            }

            $date = strtotime($request->date);

            $newformat = date('Y-m-d', $date);

            $payment = new SmBankPaymentSlip();
            $payment->date = $newformat;
            $payment->amount = $request->amount;
            $payment->note = $request->note;
            $payment->slip = $fileName;
            $payment->fees_type_id = $request->fees_type_id;
            $payment->student_id = $request->student_id;
            $payment->payment_mode = $request->payment_mode;
            $payment->class_id = $request->class_id;
            $payment->section_id = $request->section_id;
            $payment->school_id = Auth::user()->school_id;
            $payment->academic_id = getAcademicId();

            $payment->save();

            Toastr::success('Payment Added, Please Wait for approval', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
           
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function feesGenerateModalChildView($id,$type_id)
    {

        $fees_payments = SmBankPaymentSlip::where('student_id',$id)->where('fees_type_id',$id)->get();
        return view('backEnd.studentPanel.view_bank_payment', compact('fees_payments'));
    }

    public function feesGenerateModalBankView($sid,$ft_id)
    {

        $fees_payments = SmBankPaymentSlip::where('student_id',$sid)->where('fees_type_id',$ft_id)->get();
        // return $fees_payments;
        $amount = SmBankPaymentSlip::where('student_id',$sid)->where('fees_type_id',$ft_id)->sum('amount');
        return view('backEnd.studentPanel.view_bank_payment', compact('fees_payments','amount'));
    }

    public function childBankSlipDelete(Request $request)
    {

        try {
            $visitor = SmBankPaymentSlip::find($request->id);
            if ($visitor->slip != "") {
                $path = url('/') . $visitor->slip;
                if (file_exists($path)) {
                    unlink($path);
                }
            }
            $result = $visitor->delete();

            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}