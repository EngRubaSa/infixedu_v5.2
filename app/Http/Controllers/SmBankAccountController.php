<?php

namespace App\Http\Controllers;

use App\YearCheck;
use App\ApiBaseMethod;
use App\SmBankAccount;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;

class SmBankAccountController extends Controller
{
    public function __construct()
	{
        $this->middleware('PM');
        // User::checkAuth();
	}


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $bank_accounts = SmBankAccount::where('school_id',Auth::user()->school_id)->get();
            return view('backEnd.accounts.bank_account', compact('bank_accounts'));
        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'account_name' => "required|unique:sm_bank_accounts,account_name",
            'opening_balance' => "required"
        ]);
      try{
            $bank_account = new SmBankAccount();
            $bank_account->account_name = $request->account_name;
            $bank_account->opening_balance = $request->opening_balance;
            $bank_account->note = $request->note;
            $bank_account->academic_id = getAcademicId();
            $bank_account->school_id = Auth::user()->school_id;
            $result = $bank_account->save();
            if ($result) {
                Toastr::success('Operation successful', 'Success');
                return redirect()->back();
            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{
            // $bank_account = SmBankAccount::find($id);
             if (checkAdmin()) {
                $bank_account = SmBankAccount::find($id);
            }else{
                $bank_account = SmBankAccount::where('id',$id)->where('school_id',Auth::user()->school_id)->first();
            }
            $bank_accounts = SmBankAccount::where('school_id',Auth::user()->school_id)->get();
            return view('backEnd.accounts.bank_account', compact('bank_accounts', 'bank_account'));
        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Toastr::error('Operation Failed', 'Failed');
        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'account_name' => "required|unique:sm_bank_accounts,account_name," . $request->id,
            'opening_balance' => "required"
        ]);
        try{
            // $bank_account = SmBankAccount::find($request->id);
             if (checkAdmin()) {
                $bank_account = SmBankAccount::find($request->id);
            }else{
                $bank_account = SmBankAccount::where('id',$request->id)->where('school_id',Auth::user()->school_id)->first();
            }
            $bank_account->account_name = $request->account_name;
            $bank_account->opening_balance = $request->opening_balance;
            $bank_account->note = $request->note;
            $bank_account->academic_id = getAcademicId();
            $result = $bank_account->save();
            if ($result) {
                Toastr::success('Operation successful', 'Success');
                return redirect('bank-account');
            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy1($id)
    {
        try{
            $bank_account = SmBankAccount::destroy($id);
            if ($bank_account) {
                Toastr::success('Operation successful', 'Success');
                return redirect('bank-account');
            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function destroy(Request $request, $id)
    {

        try{
            $tables = \App\tableList::getTableList('bank_account_id', $id);
            try {
                if ($tables==null) {
                    // $bank_account = SmBankAccount::destroy($id);
                     if (checkAdmin()) {
                        $bank_account = SmBankAccount::destroy($id);
                    }else{
                        $bank_account = SmBankAccount::where('id',$id)->where('school_id',Auth::user()->school_id)->delete();
                    }
                    if ($bank_account) {
                        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                            if ($bank_account) {
                                return ApiBaseMethod::sendResponse(null, 'Deleted successfully');
                            } else {
                                return ApiBaseMethod::sendError('Something went wrong, please try again');
                            }
                        } else {
                            if ($bank_account) {
                                Toastr::success('Operation successful', 'Success');
                                return redirect()->back();
                            } else {
                                Toastr::error('Operation Failed', 'Failed');
                                return redirect()->back();
                            }
                        }
                    } else {
                        Toastr::error('Operation Failed', 'Failed');
                        return redirect()->back();
                    }
                } else {
                    $msg = 'This data already used in  : ' . $tables . ' Please remove those data first';
                    Toastr::error($msg, 'Failed');
                    return redirect()->back();
                }

            } catch (\Illuminate\Database\QueryException $e) {

                $msg = 'This data already used in  : ' . $tables . ' Please remove those data first';
                Toastr::error($msg, 'Failed');
                return redirect()->back();
            } catch (\Exception $e) {
                //dd($e->getMessage(), $e->errorInfo);
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }
}