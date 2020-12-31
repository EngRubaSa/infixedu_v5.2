<?php

namespace App\Http\Controllers;
use App\Role;
use App\SmStaff;
use App\YearCheck;
use Carbon\Carbon;
use App\SmAddExpense;
use App\SmLeaveRequest;
use App\SmChartOfAccount;
use App\SmPaymentMethhod;
use App\SmGeneralSettings;
use App\SmStaffAttendence;
use App\SmHrPayrollGenerate;
use Illuminate\Http\Request;
use App\SmHrPayrollEarnDeduc;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Modules\RolePermission\Entities\InfixRole;

class SmPayrollController extends Controller
{
	public function __construct()
	{
        $this->middleware('PM');
        // User::checkAuth();
	}

	public function index(Request $request)
	{

		try{
			$roles = InfixRole::where('active_status', '=', '1')->where('id', '!=', 1)->where('id', '!=', 2)->where('id', '!=', 3)->where('id', '!=', 10)->where(function ($q) {
                $q->where('school_id', Auth::user()->school_id)->orWhere('type', 'System');
            })->get();
			return view('backEnd.humanResource.payroll.index', compact('roles'));
		}catch (\Exception $e) {
		   Toastr::error('Operation Failed', 'Failed');
		   return redirect()->back();
		}
	}

	public function searchStaffPayr(Request $request)
	{

		$request->validate([
			'role_id' => "required",
			'payroll_month' => "required",
			'payroll_year' => "required"

		]);


		try{
			$role_id = $request->role_id;
			$payroll_month = $request->payroll_month;
			$payroll_year = $request->payroll_year;

			$staffs = SmStaff::where('active_status', '=', '1')->where('role_id', '=', $request->role_id)->where('school_id',Auth::user()->school_id)->get();

			$roles = InfixRole::where('active_status', '=', '1')->where('id', '!=', 1)->where('id', '!=', 2)->where('id', '!=', 3)->where(function ($q) {
                $q->where('school_id', Auth::user()->school_id)->orWhere('type', 'System');
            })->get();
			return view('backEnd.humanResource.payroll.index', compact('staffs', 'roles', 'payroll_month', 'payroll_year', 'role_id'));
		}catch (\Exception $e) {
		   Toastr::error('Operation Failed', 'Failed');
		   return redirect()->back();
		}
	}


	public function generatePayroll(Request $request, $id, $payroll_month, $payroll_year)
	{


		try{
			$staffDetails = SmStaff::find($id);
			$month = date('m', strtotime($payroll_month));
			$attendances = SmStaffAttendence::where('staff_id', $id)->where('attendence_date', 'like', $payroll_year . '-' . $month . '%')->where('school_id',Auth::user()->school_id)->get();

			$p = 0;
			$l = 0;
			$a = 0;
			$f = 0;
			$h = 0;
			foreach ($attendances as $value) {
				if ($value->attendence_type == 'P') {
					$p++;
				} elseif ($value->attendence_type == 'L') {
					$l++;
				} elseif ($value->attendence_type == 'A') {
					$a++;
				} elseif ($value->attendence_type == 'F') {
					$f++;
				} elseif ($value->attendence_type == 'H') {
					$h++;
				}
			}

			$approve_leaves = SmLeaveRequest::where('approve_status', 'A')->where('staff_id', $id)->where('school_id',Auth::user()->school_id)->get();

			return view('backEnd.humanResource.payroll.generatePayroll', compact('staffDetails', 'payroll_month', 'payroll_year', 'p', 'l', 'a', 'f', 'h'));
		}catch (\Exception $e) {
		   Toastr::error('Operation Failed', 'Failed');
		   return redirect()->back();
		}
	}

	public function savePayrollData(Request $request)
	{
		$request->validate([
			'net_salary' => "required"

		]);
		try{
			$payrollGenerate = new SmHrPayrollGenerate();
			$payrollGenerate->staff_id = $request->staff_id;
			$payrollGenerate->payroll_month = $request->payroll_month;
			$payrollGenerate->payroll_year = $request->payroll_year;
			$payrollGenerate->basic_salary = $request->basic_salary;
			$payrollGenerate->total_earning = $request->total_earning;
			$payrollGenerate->total_deduction = $request->total_deduction;
			$payrollGenerate->gross_salary = $request->final_gross_salary;
			$payrollGenerate->tax = $request->tax;
			$payrollGenerate->net_salary = $request->net_salary;
			$payrollGenerate->payroll_status = 'G';
			$payrollGenerate->created_by = Auth()->user()->id;
			$payrollGenerate->school_id = Auth::user()->school_id;
			$payrollGenerate->academic_id = getAcademicId();
			$result = $payrollGenerate->save();
			$payrollGenerate->toArray();

			if ($result) {
				$earnings = count($request->earningsType);
				for ($i = 0; $i < $earnings; $i++) {
					if (!empty($request->earningsType[$i]) && !empty($request->earningsValue[$i])) {
						$payroll_earn_deducs = new SmHrPayrollEarnDeduc;
						$payroll_earn_deducs->payroll_generate_id = $payrollGenerate->id;
						$payroll_earn_deducs->type_name = $request->earningsType[$i];
						$payroll_earn_deducs->amount = $request->earningsValue[$i];
						$payroll_earn_deducs->earn_dedc_type = 'E';
						$payroll_earn_deducs->created_by = Auth()->user()->id;
						$payroll_earn_deducs->school_id = Auth::user()->school_id;
						$payroll_earn_deducs->academic_id = getAcademicId();
						$result = $payroll_earn_deducs->save();
					}
				}

				$deductions = count($request->deductionstype);
				for ($i = 0; $i < $deductions; $i++) {
					if (!empty($request->deductionstype[$i]) && !empty($request->deductionsValue[$i])) {
						$payroll_earn_deducs = new SmHrPayrollEarnDeduc;
						$payroll_earn_deducs->payroll_generate_id = $payrollGenerate->id;
						$payroll_earn_deducs->type_name = $request->deductionstype[$i];
						$payroll_earn_deducs->amount = $request->deductionsValue[$i];
						$payroll_earn_deducs->earn_dedc_type = 'D';
						$payroll_earn_deducs->school_id = Auth::user()->school_id;
						$payroll_earn_deducs->academic_id = getAcademicId();
						$result = $payroll_earn_deducs->save();
					}
				}
				Toastr::success('Operation successful', 'Success');
				return redirect('payroll');
			} else {
				Toastr::error('Operation Failed', 'Failed');
				return redirect()->back();
			}
		}catch (\Exception $e) {
		   Toastr::error('Operation Failed', 'Failed');
		   return redirect()->back();
		}
	}

	public function paymentPayroll(Request $request, $id, $role_id)
	{

		try{
			$chart_of_accounts = SmChartOfAccount::where('type','E')->where('school_id',Auth::user()->school_id)->get();
			$payrollDetails = SmHrPayrollGenerate::find($id);
			$paymentMethods = SmPaymentMethhod::where('id', '!=', '4')->where('id', '!=', '5')->where('id', '!=', '6')->where('school_id',Auth::user()->school_id)->get();
			return view('backEnd.humanResource.payroll.paymentPayroll', compact('payrollDetails', 'paymentMethods', 'role_id','chart_of_accounts'));
		}catch (\Exception $e) {
		   Toastr::error('Operation Failed', 'Failed');
		   return redirect()->back();
		}
	}

	public function savePayrollPaymentData(Request $request)
	{
		

		try{
			$payroll_month = $request->payroll_month;
			$payroll_year = $request->payroll_year;

			$payments = SmHrPayrollGenerate::find($request->payroll_generate_id);
			$payments->payment_date = date('Y-m-d', strtotime($request->payment_date));
			$payments->payment_mode = $request->payment_mode;
			$payments->note = $request->note;
			$payments->payroll_status = 'P';
			$payments->updated_by = Auth()->user()->id;
			$payments->academic_id = getAcademicId();
			$result = $payments->update();


			if($result){

				$store = new SmAddExpense();
				$store->name = 'Staff Payroll';
				$store->expense_head_id = $request->expense_head_id;
				$store->payment_method_id = $request->payment_mode;
				$store->date = Carbon::now();
				$store->amount = $payments->net_salary;
				$store->description = 'Staff Payroll Payment';
				$store->save();

				}
			
			$staffs = SmStaff::where('active_status', '=', '1')->where('role_id', '=', $request->role_id)->where('school_id',Auth::user()->school_id)->get();
			$roles = InfixRole::where(function ($q) {
                $q->where('school_id', Auth::user()->school_id)->orWhere('type', 'System');
            })->get();
			return view('backEnd.humanResource.payroll.index', compact('staffs', 'roles', 'payroll_month', 'payroll_year'));
		}catch (\Exception $e) {
		   Toastr::error('Operation Failed', 'Failed');
		   return redirect()->back();
		}
	}

	public function viewPayslip($id)
	{

		try{
			$schoolDetails = SmGeneralSettings::find(1);
			$payrollDetails = SmHrPayrollGenerate::find($id);

			$payrollEarnDetails = SmHrPayrollEarnDeduc::where('active_status', '=', '1')->where('payroll_generate_id', '=', $id)->where('earn_dedc_type', '=', 'E')->where('school_id',Auth::user()->school_id)->get();

			$payrollDedcDetails = SmHrPayrollEarnDeduc::where('active_status', '=', '1')->where('payroll_generate_id', '=', $id)->where('earn_dedc_type', '=', 'D')->where('school_id',Auth::user()->school_id)->get();

			return view('backEnd.humanResource.payroll.viewPayslip', compact('payrollDetails', 'payrollEarnDetails', 'payrollDedcDetails', 'schoolDetails'));
		}catch (\Exception $e) {
		   Toastr::error('Operation Failed', 'Failed');
		   return redirect()->back();
		}
	}

	public function payrollReport(Request $request)
	{
		try{
			$roles = InfixRole::where('active_status', '=', '1')->where('id', '!=', 1)->where('id', '!=', 2)->where('id', '!=', 3)->where(function ($q) {
                $q->where('school_id', Auth::user()->school_id)->orWhere('type', 'System');
            })->get();
			return view('backEnd.reports.payroll', compact('roles'));
		}catch (\Exception $e) {
		   Toastr::error('Operation Failed', 'Failed');
		   return redirect()->back();
		}
	}

	public function searchPayrollReport(Request $request)
	{
		$request->validate([
			'role_id' => "required",
			'payroll_month' => "required",
			'payroll_year' => "required"

		]);
		try{
			$role_id = $request->role_id;
			$payroll_month = $request->payroll_month;
			$payroll_year = $request->payroll_year;

			$query = '';
			if ($request->role_id != "") {
				$query = "AND s.role_id = '$request->role_id'";
			}
			if ($request->payroll_month != "") {
				$query .= "AND pg.payroll_month = '$request->payroll_month'";
			}

			if ($request->payroll_year != "") {
				$query .= "AND pg.payroll_year = '$request->payroll_year'";
			}

			$school_id = Auth::user()->school_id;
			

			$staffsPayroll = DB::select(DB::raw("SELECT pg.*, s.full_name, r.name, d.title
												FROM sm_hr_payroll_generates pg
												LEFT JOIN sm_staffs s ON pg.staff_id = s.id
												LEFT JOIN roles r ON s.role_id = r.id
												LEFT JOIN sm_designations d ON s.designation_id = d.id
												WHERE pg.active_status AND pg.payroll_status='P' AND pg.school_id = '$school_id'
								
												$query"));

			$roles = InfixRole::where('active_status', '=', '1')->where('id', '!=', 1)->where('id', '!=', 2)->where('id', '!=', 3)->where(function ($q) {
                $q->where('school_id', Auth::user()->school_id)->orWhere('type', 'System');
            })->get();
			return view('backEnd.reports.payroll', compact('staffsPayroll', 'roles', 'payroll_month', 'payroll_year', 'role_id'));
		}catch (\Exception $e) {
		   Toastr::error('Operation Failed', 'Failed');
		   return redirect()->back();
		}
	}
}