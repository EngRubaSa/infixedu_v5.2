<?php

namespace App\Http\Controllers;

use App\YearCheck;
use App\SmLeaveType;
use App\ApiBaseMethod;
use App\SmLeaveDefine;
use App\SmLeaveRequest;
use App\SmGeneralSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SmLeaveRequestController extends Controller
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

        try {
            $user = Auth::user();
            

            if ($user) {
                $my_leaves = SmLeaveDefine::where('role_id', $user->role_id)->where('school_id',Auth::user()->school_id)->get();
                $apply_leaves = SmLeaveRequest::where('role_id', $user->role_id)->where('active_status', 1)
                ->where('school_id',Auth::user()->school_id)->has('leaveDefine')->where('staff_id',Auth::user()->id)->get();
                $leave_types = $my_leaves->where('active_status', 1);
            } else {
                $my_leaves = SmLeaveDefine::where('role_id', $request->role_id)->where('school_id', Auth::user()->school_id)->get();
                $apply_leaves = SmLeaveRequest::where('role_id', $request->role_id)->where('active_status', 1)->where('school_id', Auth::user()->school_id)->get();
                $leave_types = $my_leaves->where('active_status', 1);
            }
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['my_leaves'] = $my_leaves->toArray();
                $data['apply_leaves'] = $apply_leaves->toArray();
                $data['leave_types'] = $leave_types->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }
            return view('backEnd.humanResource.apply_leave', compact('apply_leaves', 'leave_types', 'my_leaves'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function store(Request $request)
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $input = $request->all();
        $validator = Validator::make($input, [
            'apply_date' => "required",
            'leave_type' => "required",
            'leave_from' => 'required|before_or_equal:leave_to',
            'leave_to' => "required",
            'attach_file' => "sometimes|nullable|mimes:pdf,doc,docx,jpg,jpeg,png",
        ]);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $maxFileSize = SmGeneralSettings::first('file_size')->file_size;
            // return $maxFileSize;
            $file = $request->file('attach_file');
            $fileSize =  filesize($file);
            $fileSizeKb = ($fileSize / 1000000);
            if($fileSizeKb >= $maxFileSize){
                Toastr::error( 'Max upload file size '. $maxFileSize .' Mb is set in system', 'Failed');
                return redirect()->back();
            }
            $fileName = "";
            if ($request->file('attach_file') != "") {
                $file = $request->file('attach_file');
                $fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                $file->move('public/uploads/leave_request/', $fileName);
                $fileName = 'public/uploads/leave_request/' . $fileName;
            }

            $apply_leave = new SmLeaveRequest();
            $apply_leave->staff_id = Auth::user()->id;
            $apply_leave->role_id = Auth::user()->role_id;
            $apply_leave->apply_date = date('Y-m-d', strtotime($request->apply_date));
            $apply_leave->leave_define_id = $request->leave_type;
            $apply_leave->type_id = $request->leave_type;
            $apply_leave->leave_from = date('Y-m-d', strtotime($request->leave_from));
            $apply_leave->leave_to = date('Y-m-d', strtotime($request->leave_to));
            $apply_leave->approve_status = 'P';
            $apply_leave->reason = $request->reason;
            if ($request->file('attach_file') != "") {
                $apply_leave->file = $fileName;
            }
            $apply_leave->school_id = Auth::user()->school_id;
            $apply_leave->academic_id = getAcademicId();
            $result = $apply_leave->save();


            if ($result) {
                Toastr::success('Operation successful', 'Success');
                return redirect()->back();
            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            // dd($e);
            Toastr::error($e->getMessage(), 'Failed');
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {


        try {
            $user = Auth::user();
            if ($user) {
                $my_leaves = SmLeaveDefine::where('role_id', $user->role_id)->where('school_id', Auth::user()->school_id)->get();
                $apply_leaves = SmLeaveRequest::where('role_id', $user->role_id)->where('active_status', 1)->where('school_id', Auth::user()->school_id)->get();
                $leave_types = SmLeaveDefine::where('role_id', $user->role_id)->where('active_status', 1)->where('school_id', Auth::user()->school_id)->get();
            } else {
                $my_leaves = SmLeaveDefine::where('role_id', $request->role_id)->where('school_id', Auth::user()->school_id)->get();
                $apply_leaves = SmLeaveRequest::where('role_id', $request->role_id)->where('active_status', 1)->where('school_id', Auth::user()->school_id)->get();
                $leave_types = SmLeaveDefine::where('role_id', $request->role_id)->where('active_status', 1)->where('school_id', Auth::user()->school_id)->get();
            }

            $apply_leave = SmLeaveRequest::find($id);

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['my_leaves'] = $my_leaves->toArray();
                $data['apply_leaves'] = $apply_leaves->toArray();
                $data['leave_types'] = $leave_types->toArray();
                $data['apply_leave'] = $apply_leave->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }

            return view('backEnd.humanResource.apply_leave', compact('apply_leave', 'apply_leaves', 'leave_types', 'my_leaves'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function update(Request $request)
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $input = $request->all();
        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
            $validator = Validator::make($input, [
                'id' => "required",
                'apply_date' => "required",
                'leave_type' => "required",
                'leave_from' => 'required|before_or_equal:leave_to',
                'leave_to' => "required",
                'login_id' => "required",
                'role_id' => "required",
                'file' => "sometimes|nullable|mimes:pdf,doc,docx,jpg,jpeg,png,txt",
            ]);
        } else {
            $validator = Validator::make($input, [
                'apply_date' => "required",
                'leave_type' => "required",
                'leave_from' => 'required|before_or_equal:leave_to',
                'leave_to' => "required",
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
            $fileName = "";
            if ($request->file('file') != "") {
                $maxFileSize = SmGeneralSettings::first('file_size')->file_size;
                $file = $request->file('file');
                $fileSize =  filesize($file);
                $fileSizeKb = ($fileSize / 1000000);
                if($fileSizeKb >= $maxFileSize){
                    Toastr::error( 'Max upload file size '. $maxFileSize .' Mb is set in system', 'Failed');
                    return redirect()->back();
                }
                $apply_leave = SmLeaveRequest::find($request->id);
                if (file_exists($apply_leave->file)) unlink($apply_leave->file);
                $file = $request->file('file');
                $fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                $file->move('public/uploads/leave_request/', $fileName);
                $fileName = 'public/uploads/leave_request/' . $fileName;
            }


            $user = Auth()->user();

            if ($user) {
                $login_id = $user->id;
                $role_id = $user->role_id;
            } else {
                $login_id = $request->login_id;
                $role_id = $request->role_id;
            }

            $apply_leave = SmLeaveRequest::find($request->id);
            $apply_leave->staff_id = $login_id;
            $apply_leave->role_id = $role_id;
            $apply_leave->apply_date = date('Y-m-d', strtotime($request->apply_date));
            $apply_leave->leave_define_id = $request->leave_type;
            $apply_leave->leave_from = date('Y-m-d', strtotime($request->leave_from));
            $apply_leave->leave_to = date('Y-m-d', strtotime($request->leave_to));
            $apply_leave->approve_status = 'P';
            $apply_leave->reason = $request->reason;
            if ($fileName != "") {
                $apply_leave->file = $fileName;
            }
            $result = $apply_leave->save();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Leave Request has been updated successfully');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again.');
                }
            } else {
                if ($result) {
                    Toastr::success('Operation successful', 'Success');
                    return redirect('apply-leave');
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

    public function viewLeaveDetails(Request $request, $id)
    {

        try {
            $leaveDetails = SmLeaveRequest::find($id);

            $apply = "";

            // $apply_leaves = SmLeaveRequest::all();
            // $leave_types = SmLeaveType::all();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['leaveDetails'] = $leaveDetails->toArray();
                $data['apply'] = $apply;
                return ApiBaseMethod::sendResponse($data, null);
            }
            return view('backEnd.humanResource.viewLeaveDetails', compact('leaveDetails', 'apply'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {

        $tables = \App\tableList::getTableList('leave_request_id', $id);

        try {
            if ($tables == null) {
                $apply_leave = SmLeaveRequest::find($id);
                if ($apply_leave->file != "") {

                    if (file_exists($apply_leave->file)) unlink($apply_leave->file);
                }
                $result = $apply_leave->delete();

                if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                    if ($result) {
                        return ApiBaseMethod::sendResponse(null, 'Request has been deleted successfully');
                    } else {
                        return ApiBaseMethod::sendError('Something went wrong, please try again.');
                    }
                } else {
                    if ($result) {
                        Toastr::success('Operation successful', 'Success');
                        if (Auth::user()->role_id == 1) {
                            return redirect('pending-leave');
                        } else {
                            return redirect('apply-leave');
                        }
                        
                        
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
        } catch (\Exception $e) {
            $msg = 'This data already used in  : ' . $tables . ' Please remove those data first';
            Toastr::error($msg, 'Failed');
            return redirect()->back();
        }
    }
}