<?php

namespace App\Http\Controllers;
use App\YearCheck;
use App\SmLeaveType;
use App\ApiBaseMethod;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SmLeaveTypeController extends Controller
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
    public function index(Request $request)
    {
        try{
            $leave_types = SmLeaveType::where('school_id',Auth::user()->school_id)->where('academic_id', getAcademicId())->get();
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse($leave_types->toArray(), null);
            }
            return view('backEnd.humanResource.leave_type', compact('leave_types'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'type' => "required",
            'total_days' => "required|numeric|min:0"
        ]);

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
         // school wise uquine validation
         $is_duplicate = SmLeaveType::where('school_id', Auth::user()->school_id)->where('type', $request->type)->where('academic_id', getAcademicId())->first();
         if ($is_duplicate) {
             Toastr::error('Duplicate name found!', 'Failed');
             return redirect()->back()->withErrors($validator)->withInput();
         }
        try{
            $leave_type = new SmLeaveType();
            $leave_type->type = $request->type;
            $leave_type->total_days = $request->total_days;
            $leave_type->school_id = Auth::user()->school_id;
            $leave_type->academic_id = getAcademicId();
            $result = $leave_type->save();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Type has been created successfully');
                }
                return ApiBaseMethod::sendError('Something went wrong, please try again.');
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {

        try{
             if (checkAdmin()) {
                $leave_type = SmLeaveType::find($id);
            }else{
                $leave_type = SmLeaveType::where('id',$id)->where('school_id',Auth::user()->school_id)->first();
            }
            $leave_types = SmLeaveType::where('school_id',Auth::user()->school_id)->where('academic_id', getAcademicId())->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['leave_type'] = $leave_type->toArray();
                $data['leave_types'] = $leave_types->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }
            return view('backEnd.humanResource.leave_type', compact('leave_types', 'leave_type'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function update(Request $request, $id)
    {
        $input = $request->all();
        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
            $validator = Validator::make($input, [
                'type' => "required",
                'total_days' => "required|numeric|min:0",
                'id' => "required"
            ]);
        } else {
            $validator = Validator::make($input, [
                'total_days' => "required|numeric|min:0",
                'type' => "required|unique:sm_leave_types,type," . $request->id
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
        // school wise uquine validation
        $is_duplicate = SmLeaveType::where('school_id', Auth::user()->school_id)->where('type', $request->type)->where('id', '!=', $request->id)->where('academic_id', getAcademicId())->first();
        if ($is_duplicate) {
            Toastr::error('Duplicate name found!', 'Failed');
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try{
            // $leave_type = SmLeaveType::find($request->id);
             if (checkAdmin()) {
                $leave_type = SmLeaveType::find($request->id);
            }else{
                $leave_type = SmLeaveType::where('id',$request->id)->where('school_id',Auth::user()->school_id)->first();
            }
            $leave_type->type = $request->type;
            $leave_type->total_days = $request->total_days;
            $result = $leave_type->save();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Type has been updated successfully');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again.');
                }
            } else {
                if ($result) {
                    Toastr::success('Operation successful', 'Success');
                    return redirect('leave-type');
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {

    try{
        $tables = \App\tableList::getTableList('type_id', $id);
        // return $tables;
        try {
            if ($tables==null) {
                // $leave_type = SmLeaveType::destroy($id);
                 if (checkAdmin()) {
                        $leave_type = SmLeaveType::destroy($id);
                    }else{
                        $leave_type = SmLeaveType::where('id',$id)->where('school_id',Auth::user()->school_id)->delete();
                    }
                if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                    if ($leave_type) {
                        return ApiBaseMethod::sendResponse(null, 'Type has been deleted successfully');
                    } else {
                        return ApiBaseMethod::sendError('Something went wrong, please try again.');
                    }
                } else {
                    if ($leave_type) {
                        Toastr::success('Operation successful', 'Success');
                        return redirect()->back();
                    } else {
                        Toastr::error('Operation Failed', 'Failed');
                        return redirect()->back();
                    }
                }
            }else{
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