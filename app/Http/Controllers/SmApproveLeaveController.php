<?php

namespace App\Http\Controllers;

use App\Role;
use App\SmGeneralSettings;
use App\SmStaff;
use App\SmParent;
use App\YearCheck;
use App\SmLeaveType;
use App\ApiBaseMethod;
use App\SmLeaveDefine;
use App\SmLeaveRequest;
use App\SmNotification;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\RolePermission\Entities\InfixRole;

class SmApproveLeaveController extends Controller
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

        try {
            $user = Auth::user();
            $staff = SmStaff::where('user_id', Auth::user()->id)->first();
            if (Auth::user()->role_id == 1) {
                $apply_leaves = SmLeaveRequest::where([['active_status', 1], ['approve_status', '!=', 'P']])->where('school_id', Auth::user()->school_id)->where('academic_id', getAcademicId())->get();
            } else {
                $apply_leaves = SmLeaveRequest::where([['active_status', 1], ['approve_status', '!=', 'P'], ['staff_id', '=', $staff->id]])->where('academic_id', getAcademicId())->get();
            }
            $leave_types = SmLeaveType::where('active_status', 1)->get();
            $roles = InfixRole::where('id', '!=', 1)->where('id', '!=', 2)->where('id', '!=', 3)->where(function ($q) {
                $q->where('school_id', Auth::user()->school_id)->orWhere('type', 'System');
            })->get();
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['apply_leaves'] = $apply_leaves->toArray();
                $data['apply_leaves'] = $leave_types->toArray();
                $data['roles'] = $roles->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }

            return view('backEnd.humanResource.approveLeaveRequest', compact('apply_leaves', 'leave_types', 'roles'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function pendingLeave(Request $request)
    {


        try {
            $user = Auth::user();
            $staff = SmStaff::where('user_id', Auth::user()->id)->first();
            if (Auth::user()->role_id == 1) {
                $apply_leaves = SmLeaveRequest::where([['active_status', 1], ['approve_status', '!=', 'A']])->where('school_id', Auth::user()->school_id)->get();
            } else {
                $apply_leaves = SmLeaveRequest::where([['active_status', 1], ['approve_status', '!=', 'A'], ['staff_id', '=', $staff->id]])->get();
            }
            $leave_types = SmLeaveType::where('active_status', 1)->get();
            $roles = InfixRole::where('id', '!=', 1)->where('id', '!=', 3)->where(function ($q) {
                $q->where('school_id', Auth::user()->school_id)->orWhere('type', 'System');
            })->get();

            $pendingRequest = SmLeaveRequest::where('sm_leave_requests.active_status', 1)
                ->select('sm_leave_requests.id', 'full_name', 'apply_date', 'leave_from', 'leave_to', 'reason', 'file', 'sm_leave_types.type', 'approve_status')
                ->join('sm_leave_defines', 'sm_leave_requests.leave_define_id', '=', 'sm_leave_defines.id')
                ->join('sm_staffs', 'sm_leave_requests.staff_id', '=', 'sm_staffs.id')
                ->leftjoin('sm_leave_types', 'sm_leave_requests.type_id', '=', 'sm_leave_types.id')
                ->where('sm_leave_requests.approve_status', '=', 'P')
                ->where('sm_leave_requests.school_id', '=', Auth::user()->school_id)
                ->get();
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['pending_request'] = $pendingRequest->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }
            return view('backEnd.humanResource.approveLeaveRequest', compact('apply_leaves', 'leave_types', 'roles'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function approvedLeave(Request $request)
    {

        try {
            $approved_request = SmLeaveRequest::where('sm_leave_requests.active_status', 1)
                ->select('sm_leave_requests.id', 'full_name', 'apply_date', 'leave_from', 'leave_to', 'reason', 'file', 'type', 'approve_status')
                ->join('sm_leave_defines', 'sm_leave_requests.leave_define_id', '=', 'sm_leave_defines.id')
                ->join('sm_staffs', 'sm_leave_requests.staff_id', '=', 'sm_staffs.id')
                ->join('sm_leave_types', 'sm_leave_requests.type_id', '=', 'sm_leave_types.id')
                ->where('sm_leave_requests.approve_status', '=', 'A')
                ->where('sm_leave_requests.school_id', '=', Auth::user()->school_id)

                ->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['approved_request'] = $approved_request->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function rejectLeave(Request $request)
    {
        try {
            $reject_request = SmLeaveRequest::where('sm_leave_requests.active_status', 1)
                ->select('sm_leave_requests.id', 'full_name', 'apply_date', 'leave_from', 'leave_to', 'reason', 'file', 'type', 'approve_status')
                ->join('sm_leave_defines', 'sm_leave_requests.leave_define_id', '=', 'sm_leave_defines.id')
                ->join('sm_staffs', 'sm_leave_requests.staff_id', '=', 'sm_staffs.id')
                ->join('sm_leave_types', 'sm_leave_requests.type_id', '=', 'sm_leave_types.id')
                ->where('sm_leave_requests.approve_status', '=', 'R')
                ->where('sm_leave_requests.school_id', '=', Auth::user()->school_id)

                ->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['reject_request'] = $reject_request->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function applyLeave(Request $request)
    {
        $input = $request->all();
        // return $request->input();
        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
            $validator = Validator::make($input, [
                'apply_date' => "required",
                'leave_type' => "required",
                'leave_from' => 'required|before_or_equal:leave_to',
                'leave_to' => "required",
                'staff_id' => "required",
                'reason' => "required",
                'attach_file' => "sometimes|nullable|mimes:pdf,doc,docx,jpg,jpeg,png,txt",
            ]);
        }

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
        }

        try {
            //return $request->input('apply_date');
            $fileName = "";
            if ($request->file('attach_file') != "") {
                $maxFileSize = SmGeneralSettings::first('file_size')->file_size;
                $file = $request->file('attach_file');
                $fileSize =  filesize($file);
                $fileSizeKb = ($fileSize / 1000000);
                if($fileSizeKb >= $maxFileSize){
                    Toastr::error( 'Max upload file size '. $maxFileSize .' Mb is set in system', 'Failed');
                    return redirect()->back();
                }
                $file = $request->file('attach_file');
                $fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                $file->move('public/uploads/leave_request/', $fileName);
                $fileName = 'public/uploads/leave_request/' . $fileName;
            }

            $apply_leave = new SmLeaveRequest();
            $apply_leave->staff_id = $request->input('staff_id');
            $apply_leave->role_id = 4;
            $apply_leave->apply_date = date('Y-m-d');
            $apply_leave->leave_define_id = $request->input('leave_type');
            $apply_leave->type_id = $request->input('leave_type');
            $apply_leave->leave_from = $request->input('leave_from');
            $apply_leave->leave_to = $request->input('leave_to');
            $apply_leave->approve_status = 'P';
            $apply_leave->reason = $request->input('reason');
            $apply_leave->school_id = Auth::user()->school_id;
            //return $request->teacher_id;
            if ($fileName != "") {
                $apply_leave->file = $fileName;
            }

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {

                $result = $apply_leave->save();

                return ApiBaseMethod::sendResponse($result, null);
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function updateLeave(Request $request)
    {

        try {
            //$leave_request = DB::table('sm_leave_requests')->where('id', $id)->first();
            // $leave_request_data = SmLeaveRequest::find($request->id);
            if (checkAdmin()) {
                $leave_request_data = SmLeaveRequest::find($request->id);
            }else{
                $leave_request_data = SmLeaveRequest::where('id',$request->id)->where('school_id',Auth::user()->school_id)->first();
            }
            $staff_id = $leave_request_data->staff_id;
            $role_id = $leave_request_data->role_id;
            $leave_request_data->approve_status = $request->status;
            $result = $leave_request_data->save();


            $notification = new SmNotification;
            $notification->user_id = $staff_id;
            $notification->role_id = $role_id;
            $notification->date = date('Y-m-d');
            $notification->message = 'Leave status updated';
            $notification->school_id = Auth::user()->school_id;
            $notification->save();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = '';
                return ApiBaseMethod::sendResponse($data, null);
            }
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
        $input = $request->all();
        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
            $validator = Validator::make($input, [
                'apply_date' => "required",
                'leave_type' => "required",
                'leave_from' => "required",
                'leave_to' => "required",
                'reason' => "required",
                'login_id' => "required",
                'role_id' => "required"
            ]);
        } else {
            $validator = Validator::make($input, [
                'staff_id' => "required",
                'apply_date' => "required",
                'leave_type' => "required",
                'leave_from' => "required",
                'leave_to' => "required",
                'reason' => "required",
                'attach_file' => "sometimes|nullable|mimes:pdf,doc,docx,jpg,jpeg,png,txt",
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
            if ($request->file('attach_file') != "") {
                $maxFileSize = SmGeneralSettings::first('file_size')->file_size;
                $file = $request->file('attach_file');
                $fileSize =  filesize($file);
                $fileSizeKb = ($fileSize / 1000000);
                if($fileSizeKb >= $maxFileSize){
                    Toastr::error( 'Max upload file size '. $maxFileSize .' Mb is set in system', 'Failed');
                    return redirect()->back();
                }
                $file = $request->file('attach_file');
                $fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                $file->move('public/uploads/leave_request/', $fileName);
                $fileName =  'public/uploads/leave_request/' . $fileName;
            }

            $user = Auth()->user();

            if ($user) {
                $login_id = $user->id;
                $role_id = $user->role_id;
            } else {
                $login_id = $request->login_id;
                $role_id = $request->role_id;
            }
            $leave_request_data = new SmLeaveRequest();
            $leave_request_data->staff_id = $login_id;
            $leave_request_data->role_id =  $role_id;
            $leave_request_data->apply_date = date('Y-m-d', strtotime($request->apply_date));
            $leave_request_data->type_id = $request->leave_type;
            $leave_request_data->leave_from = date('Y-m-d', strtotime($request->leave_from));
            $leave_request_data->leave_to = date('Y-m-d', strtotime($request->leave_to));
            $leave_request_data->approve_status = $request->approve_status;
            $leave_request_data->reason = $request->reason;
            $leave_request_data->file = $fileName;
            $leave_request_data->school_id = Auth::user()->school_id;
            $result = $leave_request_data->save();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Leave Request has been created successfully.');
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
            // $editData = SmLeaveRequest::find($id);
            if (checkAdmin()) {
                $editData = SmLeaveRequest::find($id);
            }else{
                $editData = SmLeaveRequest::where('id',$id)->where('school_id',Auth::user()->school_id)->first();
            }
            $staffsByRole = SmStaff::where('role_id', '=', $editData->role_id)->where('school_id', Auth::user()->school_id)->get();
            $roles = InfixRole::whereOr(['school_id', Auth::user()->school_id], ['school_id', 1])->get();
            $apply_leaves = SmLeaveRequest::where('active_status', 1)->where('school_id', Auth::user()->school_id)->get();
            $leave_types = SmLeaveType::where('active_status', 1)->where('school_id', Auth::user()->school_id)->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['editData'] = $editData->toArray();
                $data['staffsByRole'] = $staffsByRole->toArray();
                $data['apply_leaves'] = $apply_leaves->toArray();
                $data['leave_types'] = $leave_types->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }

            return view('backEnd.humanResource.approveLeaveRequest', compact('editData', 'staffsByRole', 'apply_leaves', 'leave_types', 'roles'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        Toastr::error('Operation Failed', 'Failed');
        return redirect()->back();
    }


    public function staffNameByRole(Request $request)
    {

        try {

            if ($request->id != 3) {
                $allStaffs = SmStaff::where('role_id', '=', $request->id)->where('school_id', Auth::user()->school_id)->get();
                $staffs = [];
                foreach ($allStaffs as $staffsvalue) {
                    $staffs[] = SmStaff::find($staffsvalue->id);
                }
            } else {
                $staffs = SmParent::where('active_status', 1)->where('school_id', Auth::user()->school_id)->where('academic_id', getAcademicId())->get();
            }

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse($staffs, null);
            }

            return response()->json([$staffs]);
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function updateApproveLeave(Request $request)
    {

        try {


            // $leave_request_data = SmLeaveRequest::find($request->id);
            if (checkAdmin()) {
                $leave_request_data = SmLeaveRequest::find($request->id);
            }else{
                $leave_request_data = SmLeaveRequest::where('id',$request->id)->where('school_id',Auth::user()->school_id)->first();
            }
            $staff_id = $leave_request_data->staff_id;
            $role_id = $leave_request_data->role_id;
            $leave_request_data->approve_status = $request->approve_status;
            $leave_request_data->academic_id = getAcademicId();
            $result = $leave_request_data->save();


            $notification = new SmNotification;
            $notification->user_id = $leave_request_data->id;
            $notification->role_id = $role_id;
            $notification->date = date('Y-m-d');
            $notification->message = 'Leave status updated';
            $notification->school_id = Auth::user()->school_id;
            $notification->academic_id = getAcademicId();
            $notification->save();


            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Leave Request has been updates successfully.');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again.');
                }
            } else {
                if ($result) {
                    Toastr::success('Operation successful', 'Success');
                    return redirect('approve-leave');
                } else {
                    Toastr::error('Operation Failed', 'Failed');
                    return redirect()->back();
                }
            }
        } catch (\Exception $e) {
            // dd($e);
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function viewLeaveDetails(Request $request, $id)
    {
        try {


            if (checkAdmin()) {
                $leaveDetails = SmLeaveRequest::find($id);
            }else{
                $leaveDetails = SmLeaveRequest::where('id',$id)->where('school_id',Auth::user()->school_id)->first();
            }
            $staff_leaves = SmLeaveDefine::where('role_id', $leaveDetails->role_id)->get();
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['leaveDetails'] = $leaveDetails->toArray();
                $data['staff_leaves'] = $staff_leaves->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }

            return view('backEnd.humanResource.viewLeaveDetails', compact('leaveDetails', 'staff_leaves'));
        } catch (\Exception $e) {
            // dd($e);
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}