<?php

namespace App\Http\Controllers;

use App\SmStaff;
use App\SmVehicle;
use App\YearCheck;
use App\ApiBaseMethod;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SmVehicleController extends Controller
{
    public function __construct()
	{
        $this->middleware('PM');
        // User::checkAuth();
	}



    public function index(Request $request)
    {
        try {
            $drivers = SmStaff::where([['active_status', 1], ['role_id', 9]])->where('school_id', Auth::user()->school_id)->get();
            $assign_vehicles = SmVehicle::where('school_id', Auth::user()->school_id)->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['drivers'] = $drivers->toArray();
                $data['assign_vehicles'] = $assign_vehicles->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }
            return view('backEnd.transport.vehicle', compact('assign_vehicles', 'drivers'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }



    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'vehicle_number' => "required|max:200",
            'vehicle_model' => "required|max:200",
            'year_made' => "sometimes|nullable|max:10",
            'driver_id' => "required",
        ]);


        // school wise uquine validation
        $is_duplicate = SmVehicle::where('school_id', Auth::user()->school_id)->where('vehicle_no', $request->vehicle_number)->first();
        if ($is_duplicate) {
            Toastr::error('Duplicate vehicle number found!', 'Failed');
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
            $assign_vehicle = new SmVehicle();
            $assign_vehicle->vehicle_no = $request->vehicle_number;
            $assign_vehicle->vehicle_model = $request->vehicle_model;
            $assign_vehicle->school_id = Auth::user()->school_id;
            $assign_vehicle->academic_id = getAcademicId();
            if ($request->year_made) {
                $assign_vehicle->made_year = $request->year_made;
            }
            $assign_vehicle->driver_id = $request->driver_id;
            // $assign_vehicle->driver_license = $request->driver_license;
            // $assign_vehicle->driver_contact = $request->driver_contact;
            $assign_vehicle->note = $request->note;
            $result = $assign_vehicle->save();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Vehicle has been created successfully');
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



    public function show(Request $request, $id)
    {
        try {
            //$drivers = SmStaff::where('active_status', 1)->where('school_id',Auth::user()->school_id)->get();
            $drivers = SmStaff::where([['active_status', 1], ['role_id', 9]])->where('school_id', Auth::user()->school_id)->get();
            // $assign_vehicle = SmVehicle::find($id);
             if (checkAdmin()) {
                $assign_vehicle = SmVehicle::find($id);
            }else{
                $assign_vehicle = SmVehicle::where('id',$id)->where('school_id',Auth::user()->school_id)->first();
            }
            $assign_vehicles = SmVehicle::where('school_id', Auth::user()->school_id)->get();
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['route'] = $drivers->toArray();
                $data['routes'] = $assign_vehicle;
                $data['routes'] = $assign_vehicles->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }
            return view('backEnd.transport.vehicle', compact('assign_vehicle', 'assign_vehicles', 'drivers'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }



    public function update(Request $request, $id)
    {
        $input = $request->all();
        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
            $validator = Validator::make($input, [
                'vehicle_number' => 'required|max:200',
                'vehicle_model' => "required|max:200",
                'year_made' => "sometimes|nullable|max:10",
                'id' => 'required',
            ]);
        } else {
            $validator = Validator::make($input, [
                'vehicle_number' => 'required|max:200',
                'vehicle_model' => "required|max:200",
                'year_made' => "sometimes|nullable|max:10",
            ]);
        }
        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }


        // school wise uquine validation
        $is_duplicate = SmVehicle::where('school_id', Auth::user()->school_id)->where('vehicle_no', $request->vehicle_number)->where('id', '!=', $request->id)->first();
        if ($is_duplicate) {
            Toastr::error('Duplicate vehicle number found!', 'Failed');
            return redirect()->back()->withErrors($validator)->withInput();
        }



        try {
            // $assign_vehicle = SmVehicle::find($request->id);
            if (checkAdmin()) {
                $assign_vehicle = SmVehicle::find($request->id);
            }else{
                $assign_vehicle = SmVehicle::where('id',$request->id)->where('school_id',Auth::user()->school_id)->first();
            }
            $assign_vehicle->vehicle_no = $request->vehicle_number;
            $assign_vehicle->vehicle_model = $request->vehicle_model;
            $assign_vehicle->made_year = $request->year_made;
            $assign_vehicle->driver_id = $request->driver_id;
            // $assign_vehicle->driver_license = $request->driver_license;
            // $assign_vehicle->driver_contact = $request->driver_contact;
            $assign_vehicle->note = $request->note;
            $result = $assign_vehicle->save();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Vehicle has been updated successfully');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again');
                }
            } else {
                if ($result) {
                    Toastr::success('Operation successful', 'Success');
                    return redirect('vehicle');
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



    public function destroy(Request $request, $id)
    {

        $tables = \App\tableList::getTableList('vehicle_id', $id);
        try {
            if ($tables == null) {
                // $vehicle = SmVehicle::destroy($id);
                if (checkAdmin()) {
                    $vehicle = SmVehicle::destroy($id);
                }else{
                    $vehicle = SmVehicle::where('id',$id)->where('school_id',Auth::user()->school_id)->delete();
                }
                if ($vehicle) {

                    if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                        if ($vehicle) {
                            return ApiBaseMethod::sendResponse(null, 'Vehicle has been deleted successfully');
                        } else {
                            return ApiBaseMethod::sendError('Something went wrong, please try again.');
                        }
                    } else {
                        if ($vehicle) {
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
    }
}