<?php

namespace App\Http\Controllers;

use App\YearCheck;
use App\ApiBaseMethod;
use App\SmStudentGroup;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SmStudentGroupController extends Controller
{
    public function __construct()
	{
        $this->middleware('PM');
        // User::checkAuth();
	}

    public function index(Request $request)
    {


        try{
            $student_groups = SmStudentGroup::where('school_id',Auth::user()->school_id)->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse($student_groups, null);
            }

            return view('backEnd.studentInformation.student_group', compact('student_groups'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'group' => 'required',
        ]);

        $is_duplicate = SmStudentGroup::where('school_id', Auth::user()->school_id)->where('group', $request->group)->first();
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
            $student_group = new SmStudentGroup();
            $student_group->group = $request->group;
            $student_group->school_id = Auth::user()->school_id;
            $student_group->academic_id = getAcademicId();
            $result = $student_group->save();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Group been created successfully');
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
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function edit(Request $request, $id)
    {

        try{
             if (checkAdmin()) {
                $student_group = SmStudentGroup::find($id);
            }else{
                $student_group = SmStudentGroup::where('id',$id)->where('school_id',Auth::user()->school_id)->first();
            }
            $student_groups = SmStudentGroup::where('school_id',Auth::user()->school_id)->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['student_group'] = $student_group->toArray();
                $data['student_groups'] = $student_groups->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }
            return view('backEnd.studentInformation.student_group', compact('student_groups', 'student_group'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }
    public function update(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'group' => 'required',
        ]);

        $is_duplicate = SmStudentGroup::where('school_id', Auth::user()->school_id)->where('id','!=', $request->id)->where('group', $request->group)->first();
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
                $student_group = SmStudentGroup::find($request->id);
            }else{
                $student_group = SmStudentGroup::where('id',$request->id)->where('school_id',Auth::user()->school_id)->first();
            }
            $student_group->group = $request->group;
            $result = $student_group->save();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Group been updated successfully');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again');
                }
            } else {
                if ($result) {
                    Toastr::success('Operation successful', 'Success');
                    return redirect('student-group');
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
            $tables = \App\tableList::getTableList('student_group_id', $id);
            try {
                if ($tables==null) {
                        if (checkAdmin()) {
                             $student_group = SmStudentGroup::destroy($id);
                        }else{
                            $student_group = SmStudentGroup::where('id',$id)->where('school_id',Auth::user()->school_id)->first();
                        }
                    if ($student_group) {
                        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                            if ($student_group) {
                                return ApiBaseMethod::sendResponse(null, 'Room type has been deleted successfully');
                            } else {
                                return ApiBaseMethod::sendError('Something went wrong, please try again');
                            }
                        } else {
                            if ($student_group) {
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
                //dd($e->getMessage(), $e->errorInfo);
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }
}