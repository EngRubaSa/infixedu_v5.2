<?php

namespace App\Http\Controllers;
use App\tableList;
use App\YearCheck;
use App\ApiBaseMethod;
use App\SmStudentCategory;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SmStudentCategoryController extends Controller
{
    public function __construct()
	{
        $this->middleware('PM');
        // User::checkAuth();
	}

    public function index(Request $request)
    {

        try {
            // $student_types = SmStudentCategory::where('academic_id', getAcademicId())->get();
            $student_types = SmStudentCategory::where('school_id',Auth::user()->school_id)->where('academic_id', getAcademicId())->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse($student_types, null);
            }

            return view('backEnd.studentInformation.student_category', compact('student_types'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'category' => 'required',
        ]);

        $is_duplicate = SmStudentCategory::where('school_id', Auth::user()->school_id)->where('category_name', $request->category)->first();
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

        try {
            $student_type = new SmStudentCategory();
            $student_type->category_name = $request->category;
            $student_type->school_id = Auth::user()->school_id;
            $student_type->academic_id = getAcademicId();
            $result = $student_type->save();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Category been created successfully');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again');
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
             if (checkAdmin()) {
                $student_type = SmStudentCategory::find($id);
            }else{
                $student_type = SmStudentCategory::where('id',$id)->where('school_id',Auth::user()->school_id)->first();
            }
            $student_types = SmStudentCategory::where('school_id',Auth::user()->school_id)->get();
            return view('backEnd.studentInformation.student_category', compact('student_types', 'student_type'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function update(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'category' => 'required',
        ]);

        $is_duplicate = SmStudentCategory::where('school_id', Auth::user()->school_id)->where('id','!=', $request->id)->where('category_name', $request->category)->first();
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

        try {
            if (checkAdmin()) {
                $student_type = SmStudentCategory::find($request->id);
            }else{
                $student_type = SmStudentCategory::where('id',$request->id)->where('school_id',Auth::user()->school_id)->first();
            }
            $student_type->category_name = $request->category;
            $result = $student_type->save();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Category been updated successfully');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again');
                }
            } else {
                if ($result) {
                    Toastr::success('Operation successful', 'Success');
                    return redirect('student-category');
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

    public function delete(Request $request, $id)
    {

        try{
            $tables = \App\tableList::getTableList('student_category_id', $id);
            try {
                if ($tables==null) {
                    if (checkAdmin()) {
                        $delete_query = SmStudentCategory::find($id)->delete();
                    }else{
                        $delete_query = SmStudentCategory::destroy('id',$id)->where('school_id',Auth::user()->school_id)->delete();
                    }
                    if ($delete_query) {
                        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                            if ($delete_query) {
                                return ApiBaseMethod::sendResponse(null, 'Room type has been deleted successfully');
                            } else {
                                return ApiBaseMethod::sendError('Something went wrong, please try again');
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
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }
}