<?php
namespace App\Http\Controllers;
use App\SmClass;
use App\SmStudent;
use App\tableList;
use App\YearCheck;
use App\SmBaseSetup;
use App\SmFeesAssign;
use App\SmFeesMaster;
use App\ApiBaseMethod;
use App\SmFeesPayment;
use App\SmFeesDiscount;
use App\SmStudentCategory;
use Illuminate\Http\Request;
use App\SmFeesAssignDiscount;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SmFeesDiscountController extends Controller
{
    public function __construct()
	{
        $this->middleware('PM');
        // User::checkAuth();
	}

    public function index(Request $request)
    {

        try{
            $fees_discounts = SmFeesDiscount::where('school_id',Auth::user()->school_id)->where('academic_id', getAcademicId())->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse($fees_discounts, null);
            }

            return view('backEnd.feesCollection.fees_discount', compact('fees_discounts'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => "required|max:200|unique:sm_fees_discounts",
            'code' => "required|unique:sm_fees_discounts",
            'amount' => "required|integer|min:0"
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
            $fees_discount = new SmFeesDiscount();
            $fees_discount->name = $request->name;
            $fees_discount->code = $request->code;
            $fees_discount->type = $request->type;
            $fees_discount->amount = $request->amount;
            $fees_discount->description = $request->description;
            $fees_discount->school_id = Auth::user()->school_id;
            $fees_discount->academic_id = getAcademicId();
            $result = $fees_discount->save();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Fees discount has been created successfully');
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
            // $fees_discount = SmFeesDiscount::find($id);
             if (checkAdmin()) {
                $fees_discount = SmFeesDiscount::find($id);
            }else{
                $fees_discount = SmFeesDiscount::where('id',$id)->where('school_id',Auth::user()->school_id)->first();
            }
            $fees_discounts = SmFeesDiscount::where('school_id',Auth::user()->school_id)->where('academic_id', getAcademicId())->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['fees_discount'] = $fees_discount->toArray();
                $data['fees_discounts'] = $fees_discounts->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }

            return view('backEnd.feesCollection.fees_discount', compact('fees_discounts', 'fees_discount'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }
    public function update(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => "required|max:200|unique:sm_fees_discounts,name," . $request->id,
            'code' => "required|unique:sm_fees_discounts,code," . $request->id,
            'amount' => "required|integer|min:0"
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
            // $fees_discount = SmFeesDiscount::find($request->id);
            if (checkAdmin()) {
                $fees_discount = SmFeesDiscount::find($request->id);
            }else{
                $fees_discount = SmFeesDiscount::where('id',$request->id)->where('school_id',Auth::user()->school_id)->first();
            }
            $fees_discount->name = $request->name;
            $fees_discount->code = $request->code;
            $fees_discount->type = $request->type;
            $fees_discount->amount = $request->amount;
            $fees_discount->description = $request->description;
            $fees_discount->academic_id = getAcademicId();
            $result = $fees_discount->save();



                   if ($fees_discount->type=='once') {
                            $fees_assigns=SmFeesAssign::where('fees_discount_id',$request->id)->get();
                            foreach($fees_assigns as $key => $fees_assign){
                                $fees_assign_total=$fees_assign->fees_amount+$fees_assign->applied_discount;
                                if ($fees_assign->feesGroupMaster->amount==$fees_assign_total) {

                                     if ($fees_assign->feesGroupMaster->amount>=$fees_discount->amount) {
                                        $discount=$fees_discount->amount;
                                        $payable_fees=$fees_assign->feesGroupMaster->amount-$fees_discount->amount;
                                    
                                    }else{
                                            $discount=$fees_assign->fees_amount;
                                            $payable_fees=0.00;
                                    }
                                   
                                    $student_fees_assign=SmFeesAssign::find($fees_assign->id);
                                    $student_fees_assign->fees_amount=$payable_fees;
                                    $student_fees_assign->applied_discount=$discount;
                                    $student_fees_assign->save();
                                }
                                  
                            }
                            
                        } else {
                           
                            $fees_assigns=SmFeesAssign::where('fees_discount_id',$request->id)->get();
                            foreach($fees_assigns as $key => $fees_assign){

                               $fees_assign_total=$fees_assign->fees_amount+$fees_assign->applied_discount;
                                    if ($fees_assign->feesGroupMaster->amount==$fees_assign_total) {

                                            if ($fees_assign->feesGroupMaster->amount>=$fees_discount->amount) {
                                                $discount=$fees_discount->amount;
                                                $payable_fees=$fees_assign->feesGroupMaster->amount-$fees_discount->amount;
                                            
                                            }else{
                                                    $discount=$fees_assign->fees_amount;
                                                    $payable_fees=0.00;
                                            }
                                        
                                            $student_fees_assign=SmFeesAssign::find($fees_assign->id);
                                            $student_fees_assign->fees_amount=$payable_fees;
                                            $student_fees_assign->applied_discount=$discount;
                                            $student_fees_assign->save();
                                        }
                            }
                        }





            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Fees discount has been updated successfully');
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
            dd($e);
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }
    public function delete(Request $request, $id)
    {

        try{
        $id_key = 'fees_discount_id';

        $tables = tableList::getTableList($id_key,$id);

        try {

            $check_fees_discount_in_discount_assign=SmFeesAssignDiscount::where('fees_discount_id',$request->id)->first();
            $check_fees_discount_in_payment=SmFeesPayment::where('fees_discount_id',$request->id)->first();

            if ($check_fees_discount_in_discount_assign!=null && $check_fees_discount_in_payment!=null) {
                $msg = 'This data already used in  : ' . $tables . ' Please remove those data first';
                Toastr::error($msg, 'Failed');
                return redirect()->back();
            }

            // $delete_query = SmFeesDiscount::destroy($request->id);
            if (checkAdmin()) {
                $delete_query = SmFeesDiscount::destroy($request->id);
            }else{
                $delete_query = SmFeesDiscount::where('id',$request->id)->where('school_id',Auth::user()->school_id)->delete();
            }
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($delete_query) {
                    return ApiBaseMethod::sendResponse(null, 'Fees Discount has been deleted successfully');
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
        } catch (\Illuminate\Database\QueryException $e) {
            $msg = 'This data already used in  : ' . $tables . ' Please remove those data first';
            Toastr::error('This item already used', 'Failed');
            return redirect()->back();
          }
        } catch (\Exception $e) {
            //dd($e->getMessage(), $e->errorInfo);
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
            // return redirect()->back()->with('message-danger-delete', 'Something went wrong, please try again');
        }
    }

    public function feesDiscountAssign(Request $request, $id)
    {
            
        try{
            $fees_discount_id = $id;
            $classes = SmClass::where('active_status', 1)->where('school_id',Auth::user()->school_id)->where('academic_id', getAcademicId())->get();
            $groups = DB::table('sm_student_groups')->where('active_status', '=', '1')->where('school_id', Auth::user()->school_id)->get();
            $categories = SmStudentCategory::where('school_id',Auth::user()->school_id)->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['fees_discount_id'] = $fees_discount_id;
                $data['classes'] = $classes->toArray();
                $data['groups'] = $groups->toArray();
                $data['categories'] = $categories->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }
            return view('backEnd.feesCollection.fees_discount_assign', compact('classes', 'categories', 'groups', 'fees_discount_id'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }


    public function feesDiscountAssignSearch(Request $request)
    {
        try{
            $genders = SmBaseSetup::where('active_status', '=', '1')->where('base_group_id', '=', '1')->where('school_id',Auth::user()->school_id)->get();
            $classes = SmClass::where('active_status', 1)->where('school_id',Auth::user()->school_id)->where('academic_id', getAcademicId())->get();
            $groups = DB::table('sm_student_groups')->where('active_status', '=', '1')->where('school_id', Auth::user()->school_id)->get();
            $categories = SmStudentCategory::where('school_id',Auth::user()->school_id)->get();
            $fees_discount_id = $request->fees_discount_id;
            $students = SmStudent::query();
            $students->where('active_status', 1);
            if ($request->class != "") {
                $students->where('class_id', $request->class);
            }
            if ($request->section != "") {
                $students->where('section_id', $request->section);
            }
            if ($request->category != "") {
                $students->where('student_category_id', $request->category);
            }
            if ($request->group != "") {
                $students->where('student_group_id', $request->group);
            }
            $students = $students->where('school_id',Auth::user()->school_id)->get();

            $fees_discount = SmFeesDiscount::find($request->fees_discount_id);

            $pre_assigned = [];
            foreach ($students as $student) {
                $assigned_student = SmFeesAssignDiscount::select('student_id')->where('student_id', $student->id)->where('fees_discount_id', $request->fees_discount_id)->first();
                
                if ($assigned_student != "") {
                    if (!in_array($assigned_student->student_id, $pre_assigned)) {
                        $pre_assigned[] = $assigned_student->student_id;
                    }
                }
            }

            //rayhan//
            $already_paid = [];
            foreach ($students as $student) {
                $already_paid_student = SmFeesPayment::select('student_id')->where('student_id', $student->id)->where('fees_discount_id', $request->fees_discount_id)->first();

                if ($already_paid_student != "") {
                    if (!in_array($already_paid_student->student_id, $already_paid)) {
                        $already_paid[] = $already_paid_student->student_id;
                    }
                }
            }
            //rayhan//

            $class_id = $request->class;
            $category_id = $request->category;
            $group_id = $request->group;
            $gender_id = $request->gender;

            // $fees_assign_groups = SmFeesMaster::where('fees_group_id', $request->fees_group_id)->whereBetween('created_at', [YearCheck::AcStartDate(), YearCheck::AcEndDate()])->where('school_id',Auth::user()->school_id)->get();
            $assigned_fees_types=[];
            $assigned_fees_groups=[];
            foreach ($students as $key => $student) {
                $assigned_fees_type=SmFeesAssign::where('student_id',$student->id)
                ->join('sm_fees_masters','sm_fees_masters.id','=','sm_fees_assigns.fees_master_id')
                ->join('sm_fees_types','sm_fees_types.id','=','sm_fees_masters.fees_type_id')
                ->where('sm_fees_assigns.applied_discount','=',null)
                ->select('sm_fees_masters.id','sm_fees_types.id as fees_type_id','name','amount','sm_fees_assigns.student_id','applied_discount')
                ->get();
                $assigned_fees_types[$student->id]= $assigned_fees_type;
            }
            foreach ($students as $key => $student) {
                $assigned_fees_group=DB::table('sm_fees_assigns')->where('student_id',$student->id)
                ->join('sm_fees_masters','sm_fees_masters.id','=','sm_fees_assigns.fees_master_id')
                ->join('sm_fees_groups','sm_fees_groups.id','=','sm_fees_masters.fees_group_id')
                ->where('sm_fees_assigns.applied_discount','=',null)
                ->distinct ('fees_group_id')
                ->select('sm_fees_masters.id','sm_fees_groups.id as group_id','name','amount','sm_fees_assigns.student_id')
                ->get();
                $assigned_fees_groups[$student->id]= $assigned_fees_group;
            }
            // return $assigned_fees_types;
        return view('backEnd.feesCollection.fees_discount_assign', compact('assigned_fees_types','assigned_fees_groups','classes','groups', 'categories', 'students', 'fees_discount', 'genders','fees_discount_id', 'pre_assigned', 'already_paid' ,'class_id', 'category_id', 'gender_id'));
            // return view('backEnd.feesCollection.fees_discount_assign', compact('classes', 'categories', 'groups', 'students', 'fees_discount', 'fees_discount_id', 'pre_assigned', 'class_id', 'category_id', 'group_id'));
        }catch (\Exception $e) {
            //  dd($e);
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function feesDiscountAssignStore(Request $request)
    {

// return $request;

     $fees_master_id=[];
        foreach ($request->students as $key => $student) {
            $fees_master_id[$student]=$request->fees_master_ids[$key];
        }
    // dd($fees_master_id);
     $fees_master_id= array_filter($fees_master_id);
            foreach ($request->students as $student) {
                $assign_discount = SmFeesAssignDiscount::where('fees_discount_id', $request->fees_discount_id)->where('student_id', $student)->delete();

                $fees_assigns=SmFeesAssign::where('fees_discount_id', $request->fees_discount_id)->where('student_id', $student)->get();
                // return $fees_assign;
                    foreach ($fees_assigns as $key => $fees_assign) {
                       
                            $set_fees_amount=$fees_assign->fees_amount+$fees_assign->applied_discount;
                            $fees_assign->fees_amount=$set_fees_amount;
                            $fees_assign->applied_discount=null;
                            $fees_assign->save();
                    }

            }
            $discount_id=intval($request->fees_discount_id);
            $discount_info=SmFeesDiscount::find($request->fees_discount_id);
            
            if ($request->checked_ids != "") {
                foreach ($request->checked_ids as $key=> $student) {

                    $assign_discount = new SmFeesAssignDiscount();
                    $assign_discount->student_id = $student;
                    $assign_discount->fees_discount_id = $request->fees_discount_id;
                    $assign_discount->applied_amount = $discount_info->amount;
                    
                    if ($discount_info->type=='once') {
                       $assign_discount->fees_type_id = $fees_master_id[$student];
                    } else {
                       $assign_discount->fees_group_id = $fees_master_id[$student];
                    }
                    
                    $assign_discount->school_id = Auth::user()->school_id;
                    $assign_discount->academic_id = getAcademicId();
                    // dd($assign_discount);
                    $assign_discount->save();
                        if ($discount_info->type=='once') {
                            if ($fees_master_id != null) {
                               
                            $fees_assign=SmFeesAssign::where('fees_master_id',$fees_master_id[$student])->where('student_id',$student)->where('applied_discount','=',null)->first();
                            if ($fees_assign) {
                                    if ($fees_assign->fees_amount >= $discount_info->amount) {
                                        $discount=$discount_info->amount;
                                        $payable_fees=$fees_assign->fees_amount - $discount;
                                        //  dd($payable_fees);
                                        $assign_discount->applied_amount = $discount_info->amount;
                                        $assign_discount->unapplied_amount = 0.00;
                                        $assign_discount->save();
                                    }else{
                                            $discount=$fees_assign->fees_amount;
                                            $payable_fees=0.00;
                                    
                                            $assign_discount->applied_amount = $fees_assign->fees_amount;
                                            $assign_discount->unapplied_amount =$discount_info->amount-$fees_assign->fees_amount;
                                            $assign_discount->save();
                                    }
                                    $fees_assign->applied_discount+=$discount;
                                    $fees_assign->fees_discount_id = $discount_id;
                                    $fees_assign->fees_amount = $payable_fees;
                                    $fees_assign->save();
                                }
                            
                            }
                        } else {
                           
                            if ($request->fees_master_id != null) {
                                $get_masters=DB::table('sm_fees_masters')->where('fees_group_id',$fees_master_id[$student])->get();
                                                                                    //Receiving group id from $request->fees_master_ids[$key]
                                foreach ($get_masters as $key => $master) {
                                    $fees_assign=SmFeesAssign::where('fees_master_id',$master->id)->where('student_id',$student)->where('applied_discount','=',null)->first();
                                       
                                        if ($fees_assign) {
                                            if ($fees_assign->fees_amount>=$discount_info->amount) {
                                                $discount=$discount_info->amount;
                                                $payable_fees=$fees_assign->fees_amount-$discount_info->amount;
                                            
                                                $assign_discount->applied_amount = $discount_info->amount;
                                                $assign_discount->unapplied_amount = 0.00;
                                                $assign_discount->save();
                                            }else{
                                                    $discount=$fees_assign->fees_amount;
                                                    $payable_fees=0.00;
                                            
                                                    $assign_discount->applied_amount = $fees_assign->fees_amount;
                                                    $assign_discount->unapplied_amount =$discount_info->amount-$fees_assign->fees_amount;
                                                    $assign_discount->save();
                                            }
                                            $fees_assign->applied_discount+=$discount;
                                            $fees_assign->fees_discount_id = $discount_id;
                                            $fees_assign->fees_amount=$payable_fees;
                                            $fees_assign->save();
                                        }
                                    
                                }
                            }
                        }



                }
            }else {
                return response()->json(['no'=>'fail'], 200);
            }
            $html = "";

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse($html, null);
            }
            return response()->json([$html]);
        
    }

    public function feesDiscountAmountSearch(Request $request)
    {
             $discount_unapplied_amount=DB::table('sm_fees_assign_discounts')->where('fees_discount_id',$request->fees_discount_id)->where('student_id',$request->student_id)->first();
           if (intval($request->fees_amount) > $discount_unapplied_amount->unapplied_amount ) {
                $html = $discount_unapplied_amount->unapplied_amount;
            } else {
               $html=$request->fees_amount;
            }
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse($html, null);
            }
            return response()->json([$html]);

 
    }
}