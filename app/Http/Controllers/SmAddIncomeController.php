<?php

namespace App\Http\Controllers;

use App\YearCheck;
use App\SmAddIncome;
use App\ApiBaseMethod;
use App\SmBankAccount;
use App\SmChartOfAccount;
use App\SmPaymentMethhod;
use App\SmGeneralSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SmAddIncomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('PM');
        // User::checkAuth();
    }


    public function index(Request $request)
    {
        try {
            $add_incomes = SmAddIncome::where('active_status', '=', 1)->where('school_id', Auth::user()->school_id)->get();
            $income_heads = SmChartOfAccount::where('type', "I")->where('active_status', '=', 1)->where('school_id', Auth::user()->school_id)->get();
            $bank_accounts = SmBankAccount::where('active_status', '=', 1)->where('school_id', Auth::user()->school_id)->get();
            $payment_methods = SmPaymentMethhod::where('active_status', '=', 1)->where('school_id', Auth::user()->school_id)->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['add_incomes'] = $add_incomes->toArray();
                $data['income_heads'] = $income_heads->toArray();
                $data['bank_accounts'] = $bank_accounts->toArray();
                $data['payment_methods'] = $payment_methods->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }

            return view('backEnd.accounts.add_income', compact('add_incomes', 'income_heads', 'bank_accounts', 'payment_methods'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function store(Request $request)
    {
        $input = $request->all();
        if ($request->payment_method == "3") {
            $validator = Validator::make($input, [
                'income_head' => "required|integer",
                'name' => "required",
                'date' => "required",
                'accounts' => "required|integer",
                'payment_method' => "required|integer",
                'amount' => "required|integer",
                'file' => "sometimes|nullable|mimes:pdf,doc,docx,jpg,jpeg,png",
            ]);
        } else {
            $validator = Validator::make($input, [
                'income_head' => "required|integer",
                'name' => "required",
                'date' => "required",
                'payment_method' => "required|integer",
                'amount' => "required|integer",
                'file' => "sometimes|nullable|mimes:pdf,doc,docx,jpg,jpeg,png,txt",
            ]);
        }

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        try {
            $maxFileSize = SmGeneralSettings::first('file_size')->file_size;
            $file = $request->file('file');
            $fileSize =  filesize($file);
            $fileSizeKb = ($fileSize / 1000000);
            if($fileSizeKb >= $maxFileSize){
                Toastr::error( 'Max upload file size '. $maxFileSize .' Mb is set in system', 'Failed');
                return redirect()->back();
            }
            $fileName = "";
            if ($request->file('file') != "") {
                $file = $request->file('file');
                $fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                $file->move('public/uploads/add_income/', $fileName);
                $fileName =  'public/uploads/add_income/' . $fileName;
            }
            $date = strtotime($request->date);

            $newformat = date('Y-m-d', $date);

            DB::statement('SET FOREIGN_KEY_CHECKS=0;');


            $add_income = new SmAddIncome();
            $add_income->name = $request->name;
            $add_income->income_head_id = $request->income_head;
            $add_income->date = $newformat;
            $add_income->payment_method_id = $request->payment_method;
            if ($request->payment_method == "3") {
                $add_income->account_id = $request->accounts;
            }

            $add_income->amount = $request->amount;
            $add_income->file = $fileName;
            $add_income->description = $request->description;
            $add_income->school_id = Auth::user()->school_id;
            $add_income->academic_id = getAcademicId();
            $result = $add_income->save();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Income has been created successfully');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again.');
                }
            } else {
                if ($result) {
                    Toastr::success('Operation successful', 'Success');
                    return redirect()->back();
                } else {
                    Toastr::error('Operation Failed', 'Failed');
                    return redirect()->back();
                }
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function edit(Request $request, $id)
    {

        try {
            // $add_income = SmAddIncome::find($id);
             if (checkAdmin()) {
                $add_income = SmAddIncome::find($id);
            }else{
                $add_income = SmAddIncome::where('id',$id)->where('school_id',Auth::user()->school_id)->first();
            }
            $add_incomes = SmAddIncome::where('active_status', 1)->where('school_id', Auth::user()->school_id)->get();
            $income_heads = SmChartOfAccount::where('active_status', '=', 1)->where('school_id', Auth::user()->school_id)->get();
            $bank_accounts = SmBankAccount::where('active_status', '=', 1)->where('school_id', Auth::user()->school_id)->get();
            $payment_methods = SmPaymentMethhod::where('active_status', '=', 1)->where('school_id', Auth::user()->school_id)->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['add_income'] = $add_income->toArray();
                $data['add_incomes'] = $add_incomes->toArray();
                $data['income_heads'] = $income_heads->toArray();
                $data['bank_accounts'] = $bank_accounts->toArray();
                $data['payment_methods'] = $payment_methods->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }
            return view('backEnd.accounts.add_income', compact('add_income', 'add_incomes', 'income_heads', 'bank_accounts', 'payment_methods'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function update(Request $request)
    {
        $input = $request->all();
        if ($request->payment_method == "3") {
            $validator = Validator::make($input, [
                'income_head' => "required",
                'name' => "required",
                'date' => "required",
                'accounts' => "required",
                'payment_method' => "required",
                'amount' => "required",
                'file' => "sometimes|nullable|mimes:pdf,doc,docx,jpg,jpeg,png,txt",
            ]);
        } else {
            $validator = Validator::make($input, [
                'income_head' => "required",
                'name' => "required",
                'date' => "required",
                'payment_method' => "required",
                'amount' => "required",
                'file' => "sometimes|nullable|mimes:pdf,doc,docx,jpg,jpeg,png,txt",
            ]);
        }

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        try {
            $maxFileSize = SmGeneralSettings::first('file_size')->file_size;
            $file = $request->file('file');
            $fileSize =  filesize($file);
            $fileSizeKb = ($fileSize / 1000000);
            if($fileSizeKb >= $maxFileSize){
                Toastr::error( 'Max upload file size '. $maxFileSize .' Mb is set in system', 'Failed');
                return redirect()->back();
            }
            $fileName = "";
            if ($request->file('file') != "") {

                $add_income = SmAddIncome::find($request->id);
                if ($add_income->file != "") {
                    unlink($add_income->file);
                }

                $file = $request->file('file');
                $fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                $file->move('public/uploads/add_income/', $fileName);
                $fileName =  'public/uploads/add_income/' . $fileName;
            }

            $date = strtotime($request->date);

            $newformat = date('Y-m-d', $date);

            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            // $add_income = SmAddIncome::find($request->id);
             if (checkAdmin()) {
                $add_income = SmAddIncome::find($request->id);
            }else{
                $add_income = SmAddIncome::where('id',$request->id)->where('school_id',Auth::user()->school_id)->first();
            }
            $add_income->name = $request->name;
            $add_income->income_head_id = $request->income_head;
            $add_income->date = $newformat;
            $add_income->payment_method_id = $request->payment_method;
            if ($request->payment_method == "3") {
                $add_income->account_id = $request->accounts;
            }
            $add_income->amount = $request->amount;
            if ($request->file('file') != "") {
                $add_income->file = $fileName;
            }
            $add_income->description = $request->description;
            $add_income->school_id = Auth::user()->school_id;
            $add_income->academic_id = getAcademicId();
            $result = $add_income->save();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Income has been updated successfully');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again.');
                }
            } else {
                if ($result) {
                    Toastr::success('Operation successful', 'Success');
                    return redirect()->route('add_income');
                } else {
                    Toastr::error('Operation Failed', 'Failed');
                    return redirect()->back();
                }
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function delete(Request $request)
    {

        try {
            // $add_income = SmAddIncome::find($request->id);
             if (checkAdmin()) {
                $add_income = SmAddIncome::find($request->id);
            }else{
                $add_income = SmAddIncome::where('id',$request->id)->where('school_id',Auth::user()->school_id)->first();
            }
            if ($add_income->file != "") {
                unlink($add_income->file);
            }
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            $result = $add_income->delete();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Income has been deleted successfully');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again.');
                }
            } else {
                if ($result) {
                    Toastr::success('Operation successful', 'Success');
                    return redirect()->route('add_income');
                } else {
                    Toastr::error('Operation Failed', 'Failed');
                    return redirect()->back();
                }
            }
        } catch (\Exception $e) {
            // return $e;
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}