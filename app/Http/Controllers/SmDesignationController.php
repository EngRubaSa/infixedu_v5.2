<?php

namespace App\Http\Controllers;

use App\YearCheck;
use App\ApiBaseMethod;
use App\SmDesignation;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SmDesignationController extends Controller
{

    public function __construct()
	{
        $this->middleware('PM');
	}
    public function index(Request $request)
    {
        try {
            $designations = SmDesignation::where('active_status', 1)->where('school_id', Auth::user()->school_id)->get();
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse($designations, null);
            }
            return view('backEnd.humanResource.designation', compact('designations'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function store(Request $request)
    {


        $input = $request->all();
        $validator = Validator::make($input, [
            'title' => "required|max:200"
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
        $is_duplicate = SmDesignation::where('school_id', Auth::user()->school_id)->where('title', $request->title)->first();
        if ($is_duplicate) {
            Toastr::error('Duplicate name found!', 'Failed');
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            $designation = new SmDesignation();
            $designation->title = $request->title;
            $designation->school_id = Auth::user()->school_id;
            $result = $designation->save();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Designation has been created successfully');
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
            // dd($e);
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
            // $designation = SmDesignation::find($id);
             if (checkAdmin()) {
                $designation = SmDesignation::find($id);
            }else{
                $designation = SmDesignation::where('id',$id)->where('school_id',Auth::user()->school_id)->first();
            }
            $designations = SmDesignation::where('school_id', Auth::user()->school_id)->get();
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['designation'] = $designation->toArray();
                $data['designations'] = $designations->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }
            return view('backEnd.humanResource.designation', compact('designation', 'designations'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function update(Request $request, $id)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'title' => "required|max:200"
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
        $is_duplicate = SmDesignation::where('school_id', Auth::user()->school_id)->where('title', $request->title)->where('id', '!=', $request->id)->first();
        if ($is_duplicate) {
            Toastr::error('Duplicate name found!', 'Failed');
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            // $designation = SmDesignation::find($request->id);
            if (checkAdmin()) {
                $designation = SmDesignation::find($request->id);
            }else{
                $designation = SmDesignation::where('id',$request->id)->where('school_id',Auth::user()->school_id)->first();
            }
            $designation->title = $request->title;
            $result = $designation->save();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Designation has been updated successfully');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again.');
                }
            } else {
                if ($result) {
                    Toastr::success('Operation successful', 'Success');
                    return redirect('designation');
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


    public function destroy1(Request $request, $id)
    {
        try {
            $id_key = 'designation_id';
            $tables = \App\tableList::getTableList($id_key, $id);
            try {
                if ($tables == null) {
                    // $delete_query = SmDesignation::destroy($id);
                    if (checkAdmin()) {
                        $delete_query = SmDesignation::destroy($id);
                    }else{
                        $delete_query = SmDesignation::where('id',$id)->where('school_id',Auth::user()->school_id)->delete();
                    }
                    if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                        if ($delete_query) {
                            return ApiBaseMethod::sendResponse(null, 'Designation has been deleted successfully');
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
                } else {
                    $msg = 'This data already used in  : ' . $tables . ' Please remove those data first';
                    Toastr::error($msg, 'Failed');
                    return redirect()->back();
                }
            } catch (\Illuminate\Database\QueryException $e) {
                Toastr::error('This item already used', 'Failed');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function destroy(Request $request, $id)
    {

        try {
            $tables = \App\tableList::getTableList('designation_id', $id);
            // return $tables;
            try {
                if ($tables == null) {
                    // $designation = SmDesignation::destroy($id);
                     if (checkAdmin()) {
                        $designation = SmDesignation::destroy($id);
                    }else{
                        $designation = SmDesignation::where('id',$id)->where('school_id',Auth::user()->school_id)->delete();
                    }
                    if ($designation) {
                        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                            if ($designation) {
                                return ApiBaseMethod::sendResponse(null, 'Deleted successfully');
                            } else {
                                return ApiBaseMethod::sendError('Something went wrong, please try again');
                            }
                        } else {
                            if ($designation) {
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