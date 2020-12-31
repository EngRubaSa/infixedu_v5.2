<?php

namespace App\Http\Controllers;

use App\tableList;
use App\YearCheck;
use App\SmSetupAdmin;
use App\ApiBaseMethod;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SmSetupAdminController extends Controller
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

        try{
            $admin_setups = SmSetupAdmin::where('active_status', '=', 1)->where('school_id',Auth::user()->school_id)->get();
            $admin_setups = $admin_setups->groupBy('type');

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['admin_setups'] = $admin_setups->toArray();
                $data['admin_setups'] = $admin_setups->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }
            return view('backEnd.admin.setup_admin', compact('admin_setups'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'type' => 'required',
            'name' => 'required|max:50'
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
            $setup = new SmSetupAdmin();
            $setup->type = $request->type;
            $setup->name = $request->name;
            $setup->description = $request->description;
            $setup->school_id = Auth::user()->school_id;
            $setup->academic_id = getAcademicId();
            $result = $setup->save();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Admin  Setup has been created successfully');
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {

        try{
            // $admin_setup = SmSetupAdmin::find($id);
             if (checkAdmin()) {
                $admin_setup = SmSetupAdmin::find($id);
            }else{
                $admin_setup = SmSetupAdmin::where('id',$id)->where('school_id',Auth::user()->school_id)->first();
            }
            $admin_setups = SmSetupAdmin::where('active_status', '=', 1)->where('school_id',Auth::user()->school_id)->get();
            $admin_setups = $admin_setups->groupBy('type');

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['admin_setup'] = $admin_setup->toArray();
                $data['admin_setups'] = $admin_setups->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }
            return view('backEnd.admin.setup_admin', compact('admin_setups', 'admin_setup'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function update(Request $request, $id)
    {

        try{
            $input = $request->all();
            $validator = Validator::make($input, [
                'type' => 'required',
                'name' => 'required|max:100'
            ]);

            if ($validator->fails()) {
                if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                    return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
                }
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            if (checkAdmin()) {
                    $setup = SmSetupAdmin::find($id);
            }else{
                $setup = SmSetupAdmin::where('id',$id)->where('school_id',Auth::user()->school_id)->first();
            }
            $setup->type = $request->type;
            $setup->name = $request->name;
            $setup->description = $request->description;
            $result = $setup->save();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Admin Setup has been updated successfully');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again');
                }
            } else {
                if ($result) {
                    Toastr::success('Operation successful', 'Success');
                    return redirect('setup-admin');
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
    public function destroy1(Request $request, $id)
    {

        try{


        $result = SmSetupAdmin::destroy($id);

        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
            if ($request) {
                return ApiBaseMethod::sendResponse(null, 'Admin Setup can not delete');
            } else {
                return ApiBaseMethod::sendError('Something went wrong, please try again');
            }
        } else {
            if ($request) {
                Toastr::success('Operation successful', 'Success');
                return redirect('setup-admin');
            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        }
        } catch (\Illuminate\Database\QueryException $e) {
            $msg='This data already used in  : '.$tables.' Please remove those data first';
            Toastr::error('This data used in another information', 'Failed');
            return redirect()->back();
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function destroy(Request $request, $id)
    {

        try{

            // $setup_info=SmSetupAdmin::where('id',$id)->first();

            // if ($setup_info->type==1) {
            //    $column_name='purpose';
            // }
            // if ($setup_info->type==2) {
            //    $column_name='complaint_type';
            // }
            // if ($setup_info->type==3) {
            //    $column_name='source';
            // }
            // if ($setup_info->type==4) {
            //    $column_name='reference';
            // }
            // $tables = \App\tableList::getTableList($column_name, $id);


            $tables1 = tableList::getTableList('complaint_type', $id);
            $tables2 = tableList::getTableList('complaint_source', $id);
            $tables3 = tableList::getTableList('source', $id);
            $tables4 = tableList::getTableList('reference', $id);

            // return $tables1 .'-'. $tables2 .'-'. $tables3 .'-'. $tables4;


                try {
                    if ($tables1==null && $tables2==null && $tables3==null && $tables4==null) {
                    // $setup_admin = SmSetupAdmin::destroy($id);
                    if (checkAdmin()) {
                         $setup_admin = SmSetupAdmin::destroy($id);
                    }else{
                        $setup_admin = SmSetupAdmin::where('id',$id)->where('school_id',Auth::user()->school_id)->delete();
                    }
                    if ($setup_admin) {
                        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                            if ($setup_admin) {
                                return ApiBaseMethod::sendResponse(null, 'Deleted successfully');
                            } else {
                                return ApiBaseMethod::sendError('Something went wrong, please try again');
                            }
                        } else {
                            if ($setup_admin) {
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
                    }else{
                        $msg = 'This data already used in  : ' . $tables1 .' '. $tables2 .' '. $tables3 .' '. $tables4 . ' Please remove those data first';
                        Toastr::error($msg, 'Failed');
                        return redirect()->back();
                    }
                } catch (\Illuminate\Database\QueryException $e) {

                    // $msg = 'This data already used in  : ' . $tables . ' Please remove those data first';
                    Toastr::error('Operation Failed', 'Failed');
                    return redirect()->back();
                } catch (\Exception $e) {
                    // return $e;
                    // dd($e);
                    Toastr::error('Operation Failed', 'Failed');
                    return redirect()->back();
                }


        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }
}