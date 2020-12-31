<?php

namespace App\Http\Controllers;
use App\tableList;
use App\YearCheck;
use App\SmFeesType;
use App\SmFeesGroup;
use App\SmFeesMaster;
use App\ApiBaseMethod;
use App\SmFeesPayment;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SmFeesTypeController extends Controller
{
    public function __construct()
	{
        $this->middleware('PM');
        // User::checkAuth();
	}

    public function index(Request $request)
    {

        try{
            $fees_types = SmFeesType::with('fessGroup')->where('school_id',Auth::user()->school_id)->where('academic_id', getAcademicId())->get();
            $fees_groups = SmFeesGroup::where('school_id',Auth::user()->school_id)->where('academic_id', getAcademicId())->get();
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse($fees_types, null);
            }

            return view('backEnd.feesCollection.fees_type', compact('fees_types','fees_groups'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => "required|max:50",
            'fees_group' => "required|"
        ]);
        $is_duplicate = SmFeesType::where('school_id', Auth::user()->school_id)->where('name', $request->name)->where('fees_group_id', $request->fees_group)->where('academic_id', getAcademicId())->first();
        if ($is_duplicate) {
            Toastr::error('Duplicate name found!', 'Failed');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        try{
            $fees_type = new SmFeesType();
            $fees_type->name = $request->name;
            $fees_type->fees_group_id = $request->fees_group;
            $fees_type->description = $request->description;
            $fees_type->school_id = Auth::user()->school_id;
            $fees_type->academic_id = getAcademicId();
            $result = $fees_type->save();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Fees type has been created successfully.');
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
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function edit(Request $request, $id)
    {
        try{
             if (checkAdmin()) {
                $fees_type = SmFeesType::find($id);
            }else{
                $fees_type = SmFeesType::where('id',$id)->where('school_id',Auth::user()->school_id)->first();
            }
            $fees_types = SmFeesType::where('school_id',Auth::user()->school_id)->where('academic_id', getAcademicId())->get();
            $fees_groups = SmFeesGroup::where('school_id',Auth::user()->school_id)->where('academic_id', getAcademicId())->get();
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['fees_type'] = $fees_type->toArray();
                $data['fees_types'] = $fees_types->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }
            return view('backEnd.feesCollection.fees_type', compact('fees_type', 'fees_types','fees_groups'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }
    public function update(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' =>  'required|max:50',
            'fees_group' => "required|"
        ]);

        $is_duplicate = SmFeesType::where('school_id', Auth::user()->school_id)->where('id','!=', $request->id)->where('name', $request->name)->where('fees_group_id', $request->fees_group)->where('academic_id', getAcademicId())->first();
        if ($is_duplicate) {
            Toastr::error('Duplicate name found!', 'Failed');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try{
             if (checkAdmin()) {
                $fees_type = SmFeesType::find($request->id);
            }else{
                $fees_type = SmFeesType::where('id',$request->id)->where('school_id',Auth::user()->school_id)->first();
            }
            $fees_type->name = $request->name;
            $fees_type->fees_group_id = $request->fees_group;
            $fees_type->description = $request->description;
            $result = $fees_type->save();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Fees type has been updated successfully.');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again.');
                }
            } else {
                if ($result) {
                    Toastr::success('Operation successful', 'Success');
                    return redirect()->route('fees_type');
                } else {
                    Toastr::error('Operation Failed', 'Failed');
                    return redirect()->back();
                }
            }
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }
    public function delete(Request $request, $id)
    {
       try{
        $id_key = 'fees_type_id';

        $tables = tableList::getTableList($id_key,$id);

        try {
            if ($tables==null) {
                $check_fees_type_in_master=SmFeesMaster::where('fees_type_id',$id)->first();
                $check_fees_type_in_payment=SmFeesPayment::where('fees_type_id',$id)->first();
                if ($check_fees_type_in_master!=null && $check_fees_type_in_payment!=null) {
                    Toastr::warning('Operation Failed', 'Used Data');
                    return redirect('fees-type');
                }
                 if (checkAdmin()) {
                     $delete_query = SmFeesType::destroy($id);
                }else{
                    $delete_query = SmFeesType::where('id',$id)->where('school_id',Auth::user()->school_id)->delete();
                }
                if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                    if ($delete_query) {
                        return ApiBaseMethod::sendResponse(null, 'Fees Type has been deleted successfully');
                    } else {
                        return ApiBaseMethod::sendError('Something went wrong, please try again.');
                    }
                } else {
                    if ($delete_query) {
                        Toastr::success('Operation successful', 'Success');
                        return redirect()->back();
                    } else {
                        Toastr::error('Operation Failed', 'Failed');
                        return redirect()->back();
                    }
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
          }
        } catch (\Exception $e) {
            //dd($e->getMessage(), $e->errorInfo);
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
            // return redirect()->back()->with('message-danger-delete', 'Something went wrong, please try again');
        }
    }
}