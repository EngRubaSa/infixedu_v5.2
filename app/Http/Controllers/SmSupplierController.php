<?php

namespace App\Http\Controllers;
use App\YearCheck;
use App\SmSupplier;
use App\ApiBaseMethod;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SmSupplierController extends Controller
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
            $suppliers = SmSupplier::where('school_id',Auth::user()->school_id)->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse($suppliers, null);
            }
            return view('backEnd.inventory.supplierList', compact('suppliers'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function store(Request $request)
    {
        // return $request;
        $input = $request->all();
        $validator = Validator::make($input, [
            'company_name' => "required|max:200",
            'company_address' => "required",
            'contact_person_name' => "required|max:200",
            'contact_person_mobile' => "required|numeric",
            'contact_person_email' => "email"
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
            $suppliers = new SmSupplier();
            $suppliers->company_name = $request->company_name;
            $suppliers->company_address = $request->company_address;
            $suppliers->contact_person_name = $request->contact_person_name;
            $suppliers->contact_person_mobile = $request->contact_person_mobile;
            $suppliers->contact_person_email = $request->contact_person_email;
            $suppliers->description = $request->description;
            $suppliers->school_id = Auth::user()->school_id;
            $suppliers->academic_id = getAcademicId();
            $results = $suppliers->save();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($results) {
                    return ApiBaseMethod::sendResponse(null, 'New Supplier has been added successfully');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again');
                }
            } else {
                if ($results) {
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
            $editData = SmSupplier::find($id);
            $suppliers = SmSupplier::where('school_id',Auth::user()->school_id)->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['editData'] = $editData->toArray();
                $data['suppliers'] = $suppliers->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }
            return view('backEnd.inventory.supplierList', compact('editData', 'suppliers'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'company_name' => "required|max:200",
            'company_address' => "required",
            'contact_person_name' => "required|max:200",
            'contact_person_mobile' => "required|numeric",
            'contact_person_email' => "email"
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
            $suppliers = SmSupplier::find($id);
            $suppliers->company_name = $request->company_name;
            $suppliers->company_address = $request->company_address;
            $suppliers->contact_person_name = $request->contact_person_name;
            $suppliers->contact_person_mobile = $request->contact_person_mobile;
            $suppliers->contact_person_email = $request->contact_person_email;
            $suppliers->description = $request->description;
            $suppliers->updated_by = Auth()->user()->id;
            $results = $suppliers->update();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($results) {
                    return ApiBaseMethod::sendResponse(null,  'Supplier has been updated successfully');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again');
                }
            } else {
                if ($results) {
                    Toastr::success('Operation successful', 'Success');
                    return redirect('suppliers');
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

    public function deleteSupplierView(Request $request, $id)
    {

        try{
            $title = "Are you sure to detete this Supplier?";
            $url = route('delete-supplier',$id);
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse($id, null);
            }
            return view('backEnd.modal.delete', compact('id', 'title', 'url'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function deleteSupplier(Request $request, $id)
    {
        try{
        $tables = \App\tableList::getTableList('supplier_id', $id);
        try {
            if ($tables==null) {
                $result = SmSupplier::destroy($id);
            if ($result) {

                if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                    if ($result) {
                        return ApiBaseMethod::sendResponse(null, 'Supplier Category has been deleted successfully');
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
           }
        }catch (\Exception $e) {
            //dd($e->getMessage(), $e->errorInfo);
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}