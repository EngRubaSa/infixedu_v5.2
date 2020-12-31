<?php

namespace Modules\RolePermission\Http\Controllers;

use Validator;
use App\tableList;
use App\ApiBaseMethod;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Modules\RolePermission\Entities\InfixRole;
use Modules\RolePermission\Entities\InfixModuleInfo;
use Modules\RolePermission\Entities\InfixPermissionAssign;
use Modules\RolePermission\Entities\InfixModuleStudentParentInfo;

class RolePermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
       
        return view('rolepermission::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('rolepermission::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        
        return view('rolepermission::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('rolepermission::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    public function role(Request $request)
    {
        try {

            $roles = InfixRole::where('is_saas',0)->where('active_status', '=', 1)
                ->where(function ($q) {
                    $q->where('school_id', Auth::user()->school_id)->orWhere('type', 'System');
                })
                ->where('id', '!=', 1)
                ->orderBy('id', 'desc')
                ->get();
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse($roles, null);
            }
            return view('rolepermission::role', compact('roles'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }



    public function roleStore(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => "required"
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
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            $role = new InfixRole();
            $role->name = $request->name;
            $role->type = 'User Defined';
            $role->school_id = Auth::user()->school_id;
            $result = $role->save();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Role has been created successfully');
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
    public function roleEdit(Request $request, $id)
    {
        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            $role = InfixRole::find($id);
            $roles = InfixRole::where('is_saas',0)->where('active_status', '=', 1)
                ->where(function ($q) {
                    $q->where('school_id', Auth::user()->school_id)->orWhere('type', 'System');
                })
                ->where('id', '!=', 1)
                ->orderBy('id', 'desc')
                ->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['role'] = $role;
                $data['roles'] = $roles->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }
            return view('rolepermission::role', compact('role', 'roles'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function roleUpdate(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => "required"
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
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            $role = InfixRole::find($request->id);
            $role->name = $request->name;
            $result = $role->save();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Role has been updated successfully');
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
    public function roleDelete(Request $request)
    {

        try {
            $id = 'role_id';

            $tables = tableList::getTableList($id, $request->id);

            try {
                $delete_query = InfixRole::destroy($request->id);
                if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                    if ($delete_query) {
                        return ApiBaseMethod::sendResponse(null, 'Role has been deleted successfully');
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
        }
    }

    public function assignPermission($id)
    {
        
        try {
           
            
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            $role = InfixRole::where('is_saas',0)->where('id',$id)->first();
            $assign_modules = InfixPermissionAssign::where('school_id',Auth::user()->school_id)->where('role_id', $id)->get();
           

            $already_assigned = [];
            foreach ($assign_modules as $assign_module) {
                $already_assigned[] = $assign_module->module_id;
            }
            

            if ($id != 2 && $id != 3) {

                $all_modules = InfixModuleInfo::where('is_saas',0)->where('active_status', 1)->get();

                // return $all_modules;

                $all_modules = $all_modules->groupBy('module_id');

                return view('rolepermission::role_permission', compact('role', 'all_modules', 'already_assigned'));
            } else {

                if ($id == 2) {
                    $user_type = 1;
                } else {
                    $user_type = 2;
                }

                $all_modules = InfixModuleStudentParentInfo::where('active_status', 1)->where('user_type', $user_type)->where('parent_id', 0)->get();

                return view('rolepermission::role_permission_student', compact('role', 'all_modules', 'already_assigned', 'user_type'));
            }
        } catch (\Exception $e) {
            Toastr::error($e->getMessage(), 'Failed');
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function rolePermissionAssign(Request $request)
    {
        
        
        DB::beginTransaction();

        try {
            Schema::disableForeignKeyConstraints();
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            InfixPermissionAssign::where('school_id', Auth::user()->school_id)->where('role_id', $request->role_id)->delete();
            
            if ($request->module_id) {
                    
                foreach ($request->module_id as $module) {
                    
                    $assign = new InfixPermissionAssign();
                    $assign->module_id = $module;
                    $assign->role_id = $request->role_id;
                    $assign->school_id = Auth::user()->school_id;
                    $assign->save();
                }
            }
            DB::commit();
            Toastr::success('User must be relogin again for applied permission changes', 'Success');
            return redirect('rolepermission/role');
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error($e->getMessage(), 'Failed');
            return redirect()->back();
        }
    }
}