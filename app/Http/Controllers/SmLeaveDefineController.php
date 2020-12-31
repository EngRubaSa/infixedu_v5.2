<?php

namespace App\Http\Controllers;
use App\Role;
use App\YearCheck;
use App\SmLeaveType;
use App\ApiBaseMethod;
use App\SmLeaveDefine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\RolePermission\Entities\InfixRole;

class SmLeaveDefineController extends Controller
{
    public function __construct()
	{
        $this->middleware('PM');
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
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
            $leave_types = SmLeaveType::where('active_status', 1)->where('school_id',Auth::user()->school_id)->where('academic_id', getAcademicId())->get();
            $roles = InfixRole::where('active_status', '=', '1')->where('id', '!=', 1)/* ->where('id', '!=', 2) */->where('id', '!=', 3)->where('id', '!=', 10)->where(function ($q) {
                $q->where('school_id', Auth::user()->school_id)->orWhere('type', 'System');
            })->get();
            $leave_defines = SmLeaveDefine::where('active_status', 1)->where('school_id',Auth::user()->school_id)->where('academic_id', getAcademicId())->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['leave_types'] = $leave_types->toArray();
                $data['roles'] = $roles->toArray();
                $data['leave_defines'] = $leave_defines->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }
            return view('backEnd.humanResource.leave_define', compact('leave_types', 'roles', 'leave_defines'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'role' => "required",
            'leave_type' => 'required',
            'days' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        try{
            $leaves = SmLeaveDefine::where('role_id', $request->role)->where('type_id', $request->leave_type)->where('academic_id', getAcademicId())->first();

            if ($leaves == "") {
                $leave_define = new SmLeaveDefine();
                $leave_define->role_id = $request->role;
                $leave_define->type_id = $request->leave_type;
                $leave_define->days = $request->days;
                $leave_define->school_id = Auth::user()->school_id;
                $leave_define->academic_id = getAcademicId();
                $results = $leave_define->save();

                if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                    if ($results) {
                        return ApiBaseMethod::sendResponse(null, 'Visitor has been created successfully.');
                    }
                    return ApiBaseMethod::sendError('Something went wrong, please try again.');
                } else {
                    if ($results) {
                        Toastr::success('Operation successful', 'Success');
                        return redirect()->back();
                    }
                    // dd($e);
                    Toastr::error('Operation Failed', 'Failed');
                    return redirect()->back();
                }
            } else {
                if (ApiBaseMethod::checkUrl($request->fullUrl())) {

                    return ApiBaseMethod::sendError('The type already assigned for the role');
                } else {
                    
                    Toastr::error('The type already assigned for the role', 'Failed');
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
            $leave_types = SmLeaveType::where('active_status', 1)->where('school_id',Auth::user()->school_id)->where('academic_id', getAcademicId())->get();
            $roles = InfixRole::where('active_status', 1)->where(function ($q) {
                $q->where('school_id', Auth::user()->school_id)->orWhere('type', 'System');
            })->get();
            $leave_defines = SmLeaveDefine::where('active_status', 1)->where('school_id',Auth::user()->school_id)->where('academic_id', getAcademicId())->get();
            $leave_define = SmLeaveDefine::find($id);

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['leave_types'] = $leave_types->toArray();
                $data['roles'] = $roles->toArray();
                $data['leave_defines'] = $leave_defines->toArray();
                $data['leave_define'] = $leave_define->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }

            return view('backEnd.humanResource.leave_define', compact('leave_types', 'roles', 'leave_defines', 'leave_define'));
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
                'role' => "required",
                'leave_type' => 'required',
                'days' => 'required|numeric',
                'id' => "required"
            ]);
        } else {
            $validator = Validator::make($input, [
                'role' => "required",
                'leave_type' => 'required',
                'days' => 'required|numeric'
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

        try{
            // $leave_define = SmLeaveDefine::find($request->id);
             if (checkAdmin()) {
                $leave_define = SmLeaveDefine::find($request->id);
            }else{
                $leave_define = SmLeaveDefine::where('id',$request->id)->where('school_id',Auth::user()->school_id)->first();
            }
            $leave_define->role_id = $request->role;
            $leave_define->type_id = $request->leave_type;
            $leave_define->days = $request->days;
            $results = $leave_define->save();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($results) {
                    return ApiBaseMethod::sendResponse(null, 'Leave Define has been Updated successfully');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again.');
                }
            } else {
                if ($results) {
                    Toastr::success('Operation successful', 'Success');
                    return redirect('leave-define');
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

        // return $id;


        try{
            $tables = \App\tableList::getTableList('leave_define_id', $id);
            //return $tables;
            try {
                if ($tables==null) {
                    // $result = SmLeaveDefine::destroy($id);
                    if (checkAdmin()) {
                        $result = SmLeaveDefine::destroy($id);
                    }else{
                        $result = SmLeaveDefine::where('id',$id)->where('school_id',Auth::user()->school_id)->delete();
                    }
                    if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                        if ($result) {
                            return ApiBaseMethod::sendResponse(null, 'Leave Define has been deleted successfully');
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
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }
}