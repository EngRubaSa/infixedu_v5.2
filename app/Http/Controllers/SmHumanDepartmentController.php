<?php

namespace App\Http\Controllers;

use App\tableList;
use App\YearCheck;
use App\ApiBaseMethod;
use App\SmHumanDepartment;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PhpMyAdmin\MoTranslator\ReaderException;

class SmHumanDepartmentController extends Controller
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
            $departments = SmHumanDepartment::where('school_id', Auth::user()->school_id)->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse($departments, null);
            }
            return view('backEnd.humanResource.human_resource_department', compact('departments'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => "required|max:200"
        ]);

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        try {
            $department = new SmHumanDepartment();
            $department->name = $request->name;
            $department->school_id = Auth::user()->school_id;
            $result = $department->save();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Department has been created successfully');
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {

        try {
            // $department = SmHumanDepartment::find($id);
             if (checkAdmin()) {
                $department = SmHumanDepartment::find($id);
            }else{
                $department = SmHumanDepartment::where('id',$id)->where('school_id',Auth::user()->school_id)->first();
            }
            $departments = SmHumanDepartment::where('school_id', Auth::user()->school_id)->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['department'] = $department->toArray();
                $data['departments'] = $departments->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }
            return view('backEnd.humanResource.human_resource_department', compact('department', 'departments'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function update(Request $request, $id)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => "required|max:200"
        ]);

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // $department = SmHumanDepartment::find($request->id);
             if (checkAdmin()) {
                $department = SmHumanDepartment::find($request->id);
            }else{
                $department = SmHumanDepartment::where('id',$request->id)->where('school_id',Auth::user()->school_id)->first();
            }
            $department->name = $request->name;
            $result = $department->save();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Department has been updated successfully');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again.');
                }
            } else {
                if ($result) {
                    Toastr::success('Operation successful', 'Success');
                    return redirect('department');
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy1(Request $request, $id)
    {

        try {
            $id_key = 'department_id';

            $tables = tableList::getTableList($id_key, $id);

            try {
                if ($tables == null) {
                     if (checkAdmin()) {
                        $delete_query = SmHumanDepartment::destroy($id);
                    }else{
                        $delete_query = SmHumanDepartment::where('id',$id)->where('school_id',Auth::user()->school_id)->delete();
                    }
                    if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                        if ($delete_query) {
                            return ApiBaseMethod::sendResponse(null, 'Department has been deleted successfully');
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
                            // return redirect()->back()->with('message-danger-delete', 'Something went wrong, please try again');
                        }
                    }
                } else {
                    $msg = 'This data already used in  : ' . $tables . ' Please remove those data first';
                    Toastr::error('This item already used', 'Failed');
                    return redirect()->back();
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
        }
    }

    public function destroy(Request $request, $id)
    {

        try {
            $tables = \App\tableList::getTableList('department_id', $id);
            try {
                if ($tables == null) {
                    if (checkAdmin()) {
                        $department = SmHumanDepartment::destroy($id);
                    }else{
                        $department = SmHumanDepartment::where('id',$id)->where('school_id',Auth::user()->school_id)->delete();
                    }
                    if ($department) {
                        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                            if ($department) {
                                return ApiBaseMethod::sendResponse(null, 'Deleted successfully');
                            } else {
                                return ApiBaseMethod::sendError('Something went wrong, please try again');
                            }
                        } else {
                            if ($department) {
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
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}