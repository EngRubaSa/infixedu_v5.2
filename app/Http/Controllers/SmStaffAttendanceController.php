<?php

namespace App\Http\Controllers;
use App\Role;
use App\SmStaff;
use App\YearCheck;
use Carbon\Carbon;
use App\ApiBaseMethod;
use App\SmStaffAttendence;
use Illuminate\Http\Request;
use App\SmStaffAttendanceImport;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;
use App\Imports\StaffAttendanceBulk;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Modules\RolePermission\Entities\InfixRole;

class SmStaffAttendanceController extends Controller
{
    public function __construct()
	{
        $this->middleware('PM');
        // User::checkAuth();
	}

    public function staffAttendance(Request $request)
    {

        try{
            $roles = InfixRole::where('active_status', '=', '1')->where('id', '!=', 1)->where('id', '!=', 2)->where('id', '!=', 3)->where('id', '!=', 10)->where(function ($q) {
                $q->where('school_id', Auth::user()->school_id)->orWhere('type', 'System');
            })->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse($roles, null);
            }
            return view('backEnd.humanResource.staff_attendance', compact('roles'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function staffAttendanceSearch(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'role' => 'required',
            'attendance_date' => 'required'
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
            $date = $request->attendance_date;
            // $roles = InfixRole::where('id', '!=', 3)->where('id', '!=', 2)->where('school_id',Auth::user()->school_id)->get();
            $roles = InfixRole::where('active_status', '=', '1')->where('id', '!=', 1)->where('id', '!=', 2)->where('id', '!=', 3)->where('id', '!=', 10) ->whereOr(['school_id', Auth::user()->school_id], ['school_id', 1])->get();
            $role_id = $request->role;
            $staffs = SmStaff::where('role_id', $request->role)->where('school_id',Auth::user()->school_id)->get();
            if ($staffs->isEmpty()) {
                Toastr::error('No result found', 'Failed');
                return redirect('staff-attendance');
            }
            $already_assigned_staffs = [];
            $new_staffs = [];
            $attendance_type = "";
            foreach ($staffs as $staff) {
                $attendance = SmStaffAttendence::where('staff_id', $staff->id)->where('attendence_date', date('Y-m-d', strtotime($request->attendance_date)))->where('school_id',Auth::user()->school_id)->first();
                if ($attendance != "") {
                    $already_assigned_staffs[] = $attendance;
                    $attendance_type =  $attendance->attendence_type;
                } else {
                    $new_staffs[] =  $staff;
                }
            }

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['role_id'] = $role_id;
                $data['date'] = $date;
                $data['roles'] = $roles->toArray();
                $data['already_assigned_staffs'] = $already_assigned_staffs;
                $data['new_staffs'] = $new_staffs;
                $data['attendance_type'] = $attendance_type;
                return ApiBaseMethod::sendResponse($data, null);
            }

            return view('backEnd.humanResource.staff_attendance', compact('role_id', 'date', 'roles', 'already_assigned_staffs', 'new_staffs', 'attendance_type'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function staffAttendanceStore(Request $request)
    {


        try{
            foreach ($request->id as $staff) {
                $attendance = SmStaffAttendence::where('staff_id', $staff)->where('attendence_date', date('Y-m-d', strtotime($request->date)))->where('school_id',Auth::user()->school_id)->first();

                if ($attendance != "") {
                    $attendance->delete();
                }
                $attendance = new SmStaffAttendence();
                $attendance->staff_id = $staff;
                $attendance->school_id = Auth::user()->school_id;

                if (isset($request->mark_holiday)) {
                    $attendance->attendence_type = "H";
                } else {
                    $attendance->attendence_type = $request->attendance[$staff];
                    $attendance->notes = $request->note[$staff];
                }

                $attendance->attendence_date = date('Y-m-d', strtotime($request->date));
                $attendance->save();
            }

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse(null, 'Staff attendance been submitted successfully');
            }
            Toastr::success('Operation successful', 'Success');
            return redirect('staff-attendance');
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }


    public function staffAttendanceReport(Request $request)
    {

        try{
            // $roles = InfixRole::where('id', '!=', 3)->where('id', '!=', 2)->where('school_id',Auth::user()->school_id)->get();
            $roles = InfixRole::where('active_status', '=', '1')->where('id', '!=', 1)->where('id', '!=', 2)->where('id', '!=', 3)->where('id', '!=', 10) ->whereOr(['school_id', Auth::user()->school_id], ['school_id', 1])->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {

                return ApiBaseMethod::sendResponse($roles, null);
            }
            return view('backEnd.humanResource.staff_attendance_report', compact('roles'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function staffAttendanceReportSearch(Request $request)
    {

        $input = $request->all();
        $validator = Validator::make($input, [
            'role' => 'required',
            'month' => 'required',
            'year' => 'required'
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
            $year = $request->year;
            $month = $request->month;;
            $role_id = $request->role;;
            $current_day = date('d');

            $days = cal_days_in_month(CAL_GREGORIAN, $request->month, $request->year);
            $roles = InfixRole::where('id', '!=', 3)->where('id', '!=', 2)->where(function ($q) {
                $q->where('school_id', Auth::user()->school_id)->orWhere('type', 'System');
            })->get();

            $staffs = SmStaff::where('role_id', $request->role)->where('school_id',Auth::user()->school_id)->get();

            $attendances = [];
            foreach ($staffs as $staff) {
                $attendance = SmStaffAttendence::where('staff_id', $staff->id)->where('attendence_date', 'like', $request->year . '-' . $request->month . '%')->where('school_id',Auth::user()->school_id)->get();
                if (count($attendance) != 0) {
                    $attendances[] = $attendance;
                }
            }

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['attendances'] = $attendances;
                $data['days'] = $days;
                $data['year'] = $year;
                $data['month'] = $month;
                $data['current_day'] = $current_day;
                $data['roles'] = $roles;
                $data['role_id'] = $role_id;
                return ApiBaseMethod::sendResponse($data, null);
            }

            return view('backEnd.humanResource.staff_attendance_report', compact('attendances', 'staffs','days', 'year', 'month', 'current_day', 'roles',
                'role_id'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }


    public function staffAttendancePrint($role_id, $month, $year)
    {


        try{
            $current_day = date('d');

            $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            $roles = InfixRole::where('id', '!=', 3)->where('id', '!=', 2)->where(function ($q) {
                $q->where('school_id', Auth::user()->school_id)->orWhere('type', 'System');
            })->get();

            $staffs = SmStaff::where('role_id', $role_id)->where('school_id',Auth::user()->school_id)->get();
            $role = InfixRole::find($role_id);

            $attendances = [];
            foreach ($staffs as $staff) {
                $attendance = SmStaffAttendence::where('staff_id', $staff->id)->where('attendence_date', 'like', $year . '-' . $month . '%')->where('school_id',Auth::user()->school_id)->get();
                if (count($attendance) != 0) {
                    $attendances[] = $attendance;
                }
            }


            $customPaper = array(0, 0, 700.00, 1000.80);
            $pdf = PDF::loadView(
                'backEnd.humanResource.staff_attendance_print',
                [
                    'attendances' => $attendances,
                    'days' => $days,
                    'year' => $year,
                    'month' => $month,
                    'current_day' => $current_day,
                    'roles' => $roles,
                    'role_id' => $role_id,
                    'role' => $role
                ]
            )->setPaper('A4', 'landscape');
            return $pdf->stream('staff_attendance.pdf');





            // if (ApiBaseMethod::checkUrl($request->fullUrl())) {
            //     $data = [];
            //     $data['attendances'] = $attendances;
            //     $data['days'] = $days;
            //     $data['year'] = $year;
            //     $data['month'] = $month;
            //     $data['current_day'] = $current_day;
            //     $data['roles'] = $roles;
            //     $data['role_id'] = $role_id;
            //     return ApiBaseMethod::sendResponse($data, null);
            // }

            // return view('backEnd.humanResource.staff_attendance_print', compact('attendances', 'days', 'year', 'month', 'current_day', 'roles', 'role_id'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    function attendanceData($data)
    {

        try{
            return $this->staffAttendanceSearch($data);
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function teacherMyAttendanceSearchAPI(Request $request, $id = null)
    {

        $input = $request->all();

        $validator = Validator::make($input, [
            'month' => "required",
            'year' => "required",
        ]);

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try{
            $teacher = SmStaff::where('user_id', $id)->where('school_id',Auth::user()->school_id)->first();

            $year = $request->year;
            $month = $request->month;
            if ($month < 10) {
                $month = '0' . $month;
            }
            $current_day = date('d');

            $days = cal_days_in_month(CAL_GREGORIAN, $month, $request->year);
            $days2 = '';
            if ($month != 1) {
                $days2 = cal_days_in_month(CAL_GREGORIAN, $month - 1, $request->year);
            } else {
                $days2 = cal_days_in_month(CAL_GREGORIAN, $month, $request->year);
            }
             if ($month != 1) {
                $previous_month = $month - 1;
                $previous_date = $year . '-' . $previous_month . '-' . $days2;
             }else{
                 $previous_month = 12;
                 $previous_date = $year-1 . '-' . $previous_month . '-' . $days2;
             }

            $previousMonthDetails['date'] = $previous_date;
            $previousMonthDetails['day'] = $days2;
            $previousMonthDetails['week_name'] = date('D', strtotime($previous_date));

            $attendances = SmStaffAttendence::where('staff_id', $teacher->id)
                ->where('attendence_date', 'like', '%' . $request->year . '-' . $month . '%')
                ->select('attendence_type as attendance_type', 'attendence_date as attendance_date')
                ->where('school_id',Auth::user()->school_id)->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data['attendances'] = $attendances;
                $data['previousMonthDetails'] = $previousMonthDetails;
                $data['days'] = $days;
                $data['year'] = $year;
                $data['month'] = $month;
                $data['current_day'] = $current_day;
                $data['status'] = 'Present: P, Late: L, Absent: A, Holiday: H, Half Day: F';
                return ApiBaseMethod::sendResponse($data, null);
            }
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function staffAttendanceImport()
    {

        try{
            return view('backEnd.humanResource.staff_attendance_import');
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function downloadStaffAttendanceFile()
    {
        try{
            $studentsArray = ['staff_id', 'attendance_date', 'in_time', 'out_time'];

            return Excel::create('staff_attendance_sheet', function ($excel) use ($studentsArray) {
                $excel->sheet('staff_attendance_sheet', function ($sheet) use ($studentsArray) {
                    $sheet->fromArray($studentsArray);
                });
            })->download('xlsx');
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    // public function staffAttendanceBulkStore(Request $request)
    // {

    //     $request->validate([
    //         'attendance_date' => 'required',
    //         'file' => 'required|mimes:xlsx, csv'
    //     ]);


    //     try{
    //         $path = $request->file('file')->getRealPath();
    //         $data = Excel::load($path)->where('school_id',Auth::user()->school_id)->get();
    //         // return $request;
    //         // if ($data->count()) {
    //         if (!empty($data)) {

    //             // return $request;
    //             DB::beginTransaction();
    //             $staffs = SmStaff::where('active_status', 1)->where('school_id',Auth::user()->school_id)->get();
    //             $all_staff_ids = [];
    //             $present_staffs = [];
    //             foreach ($staffs as $staff) {
    //                 $all_staff_ids[] = $staff->id;
    //             }

    //             try {
    //                 SmStaffAttendanceImport::where('attendance_date', date('Y-m-d', strtotime($request->attendance_date)))->delete();
    //                 foreach ($data as $key => $value) {

    //                     if ($value->filter()->isNotEmpty()) {


    //                         if (date('d/m/Y', strtotime($request->attendance_date)) == date('d/m/Y', strtotime($value->attendance_date))) {

    //                             $staff = SmStaff::find($value->staff_id);


    //                             if ($staff != "") {
    //                                 $present_staffs[] = $staff->id;
    //                                 $import = new SmStaffAttendanceImport();
    //                                 $import->staff_id = $staff->id;
    //                                 $import->attendance_date = date('Y-m-d', strtotime($value->attendance_date));
    //                                 $import->attendance_type = 'P';
    //                                 $import->in_time = $value->in_time;
    //                                 $import->out_time = $value->out_time;
    //                                 $import->school_id = Auth::user()->school_id;
    //                                 $import->academic_id = getAcademicId();
    //                                 $import->save();
    //                             }
    //                         }
    //                     }
    //                 }

    //                 foreach ($all_staff_ids as $all_staff_id) {
    //                     if (!in_array($all_staff_id, $present_staffs)) {
    //                         $import = new SmStaffAttendanceImport();
    //                         $import->staff_id = $all_staff_id;
    //                         $import->attendance_type = 'A';
    //                         $import->attendance_date = date('Y-m-d', strtotime($request->attendance_date));
    //                         $import->school_id = Auth::user()->school_id;
    //                         $import->academic_id = getAcademicId();
    //                         $import->save();
    //                     }
    //                 }
    //             } catch (\Exception $e) {
    //                 DB::rollback();
    //                 Toastr::error('Operation Failed', 'Failed');
    //                 return redirect()->back();
    //             }
    //             DB::commit();
    //             Toastr::success('Operation successful', 'Success');
    //             return redirect()->back();
    //         }
    //     }catch (\Exception $e) {
    //        Toastr::error('Operation Failed', 'Failed');
    //        return redirect()->back();
    //     }

    // }
    public function staffAttendanceBulkStore(Request $request)
    {

        $request->validate([
            'attendance_date' => 'required',
            'file' => 'required|mimes:xlsx, csv'
        ]);

        $file_type = strtolower($request->file->getClientOriginalExtension());
        if ($file_type <> 'csv' && $file_type <> 'xlsx' && $file_type <> 'xls') {
            Toastr::warning('The file must be a file of type: xlsx, csv or xls', 'Warning');
            return redirect()->back();
        }else{
            try{
                $path = $request->file('file')->getRealPath();
                Excel::import(new StaffAttendanceBulk(), $request->file('file'), 's3', \Maatwebsite\Excel\Excel::XLSX);
                $data = SmStaffAttendanceImport::get();
                // return $data;
                if (!empty($data)) {

                    // return $request;
                    DB::beginTransaction();
                    $staffs = SmStaff::where('active_status', 1)->where('school_id',Auth::user()->school_id)->get();
                    $all_staff_ids = [];
                    $present_staffs = [];
                    foreach ($staffs as $staff) {
                        $all_staff_ids[] = $staff->id;
                    }

                    try {
                        SmStaffAttendanceImport::where('attendence_date', date('Y-m-d', strtotime($request->attendance_date)))->delete();
                        // SmStaffAttendence::where('attendence_date', date('Y-m-d', strtotime($value->attendence_date)))->where('school_id',Auth::user()->school_id)->delete();
                        

                        foreach ($data as $key => $val) {
                            SmStaffAttendence::where('attendence_date', date('Y-m-d', strtotime($val->attendence_date)))->where('school_id',Auth::user()->school_id)->delete();
                        }
                        // return $data;
                        foreach ($data as $key => $value) {

                            if (!empty($value)) {

                                
                                if (date('d/m/Y', strtotime($request->attendance_date)) == date('d/m/Y', strtotime($value->attendence_date))) {

                                    $staff = SmStaff::find($value->staff_id);

                                    $attendance = SmStaffAttendence::where('staff_id', $staff->id)->where('attendence_date', date('Y-m-d', strtotime($value->attendence_date)))->where('school_id',Auth::user()->school_id)->first();

                                    if ($attendance != "") {
                                        $attendance->delete();
                                    }

                                    // return $value->attendance_type;
                                    if ($staff != "") {
                                        $present_staffs[] = $staff->id;
                                        $import = new SmStaffAttendence();
                                        $import->staff_id = $staff->id;
                                        $import->attendence_date = date('Y-m-d', strtotime($request->attendance_date));
                                        $import->attendence_type =$value->attendance_type;
                                        $import->notes =$value->notes;
                                        // $import->in_time = $value->in_time;
                                        // $import->out_time = $value->out_time;
                                        $import->school_id = Auth::user()->school_id;
                                        $import->academic_id = getAcademicId();
                                        $import->save();
                                    }
                                }
                            }
                        }

                        foreach ($all_staff_ids as $all_staff_id) {
                            
                            if (!in_array($all_staff_id, $present_staffs)) {
                                $import = new SmStaffAttendence();
                                $import->staff_id = $all_staff_id;
                                $import->attendence_type = 'A';
                                $import->attendence_date = date('Y-m-d', strtotime($request->attendance_date));
                                $import->school_id = Auth::user()->school_id;
                                $import->academic_id = getAcademicId();
                                $import->save();
                            }
                        }
                    } catch (\Exception $e) {
                        dd($e);
                        DB::rollback();
                        Toastr::error('Operation Failed', 'Failed');
                        return redirect()->back();
                    }
                    DB::commit();
                    Toastr::success('Operation successful', 'Success');
                    return redirect()->back();
                }
            }catch (\Exception $e) {
                dd($e);
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
            }
        }
    }
}