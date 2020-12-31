<?php

namespace App\Http\Controllers;

use App\tableList;
use App\YearCheck;
use App\SmAddIncome;
use App\SmAddExpense;
use App\ApiBaseMethod;
use App\SmChartOfAccount;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SmChartOfAccountController extends Controller
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
        try {
            $chart_of_accounts = SmChartOfAccount::where('school_id',Auth::user()->school_id)->get();
            return view('backEnd.accounts.chart_of_account', compact('chart_of_accounts'));
        } catch (\Exception $e) {
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
        $validator = $request->validate([
            'head' => 'required|min:2',
            'type' => 'required',
        ]);
        $input = $request->all();
    

        $is_duplicate = SmChartOfAccount::where('school_id', Auth::user()->school_id)->where('head', $request->head)->where('type', $request->type)->first();
        if ($is_duplicate) {
            Toastr::error('Duplicate name found!', 'Failed');
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            
            $chart_of_account = new SmChartOfAccount();
            $chart_of_account->head = $request->head;
            $chart_of_account->type = $request->type;
            $chart_of_account->school_id = Auth::user()->school_id;
            $chart_of_account->academic_id = getAcademicId();
            $result = $chart_of_account->save();
            if ($result) {
                Toastr::success('Operation successful', 'Success');
                return redirect()->back();
            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        } catch (\Exception $e) {
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

        try {
             if (checkAdmin()) {
                $chart_of_account = SmChartOfAccount::find($id);
            }else{
                $chart_of_account = SmChartOfAccount::where('id',$id)->where('school_id',Auth::user()->school_id)->first();
            }
            $chart_of_accounts = SmChartOfAccount::where('school_id',Auth::user()->school_id)->get();
            return view('backEnd.accounts.chart_of_account', compact('chart_of_account', 'chart_of_accounts'));
        } catch (\Exception $e) {
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
        //
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

        $validator = $request->validate([
            'head' => 'required|min:2',
            'type' => 'required',
        ]);
     

        $is_duplicate = SmChartOfAccount::where('school_id', Auth::user()->school_id)->where('id','!=', $request->id)->where('head', $request->head)->where('type', $request->type)->first();
        if ($is_duplicate) {
            Toastr::error('Duplicate name found!', 'Failed');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            if (checkAdmin()) {
                $chart_of_account = SmChartOfAccount::find($request->id);
            }else{
                $chart_of_account = SmChartOfAccount::where('id',$request->id)->where('school_id',Auth::user()->school_id)->first();
            }
            $chart_of_account->head = $request->head;
            $chart_of_account->type = $request->type;
            $result = $chart_of_account->save();
            if ($result) {
                Toastr::success('Operation successful', 'Success');
                return redirect()->route('chart-of-account');
            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        } catch (\Exception $e) {
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

    public function destroy(Request $request, $id)
    {

        try{
            // $tables1 = \App\tableList::getTableList('income_head_id', $id );
            // $tables2 = \App\tableList::getTableList('expense_head_id', $id);

            // return $tables1 .'-'.$tables2;
            $tables1 = tableList::getTableList('income_head_id', $id);
            $tables2 = tableList::getTableList('expense_head_id', $id);
            try {

                    // return $id;

                    // return $income_head_id;
                    if ($tables1 ==null && $tables2 ==null){
                        // $chart_of_accounts = SmChartOfAccount::destroy($id);
                        if (checkAdmin()) {
                            $chart_of_account = SmChartOfAccount::destroy($id);
                        }else{
                            $chart_of_account = SmChartOfAccount::where('id',$id)->where('school_id',Auth::user()->school_id)->delete();
                        }
                        if ($chart_of_account) {
                            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                                if ($chart_of_account) {
                                    return ApiBaseMethod::sendResponse(null, 'Chart Of Account has been deleted successfully');
                                } else {
                                    return ApiBaseMethod::sendError('Something went wrong, please try again');
                                }
                            } else {
                                Toastr::success('Operation successful', 'Success');
                                return redirect()->back();
                            }
                        } else {
                            Toastr::error('Operation Failed', 'Failed');
                            return redirect()->back();
                        }
                    }else{
                        $msg = 'This data already used in  : ' . $tables1 .' '. $tables2 .' Please remove those data first';
                        Toastr::error($msg, 'Failed');
                        return redirect()->back();
                    }

            } catch (\Illuminate\Database\QueryException $e) {

                $msg = 'This data already used in  : '. $tables1 .' '. $tables2 .' Please remove those data first';
                Toastr::error($msg, 'Failed');
                return redirect()->back();
            } catch (\Exception $e) {
                // dd($e->getMessage(), $e->errorInfo);
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }
}