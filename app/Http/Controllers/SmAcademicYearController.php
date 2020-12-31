<?php

namespace App\Http\Controllers;

use App\SmClass;
use App\SmSection;
use App\tableList;
use App\YearCheck;
use App\ApiBaseMethod;
use App\SmAcademicYear;
use App\SmClassSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\SmGeneralSettings;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SmAcademicYearController extends Controller
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
            $academic_years = SmAcademicYear::where('active_status', 1)->orderBy('year', 'ASC')->where('school_id', Auth::user()->school_id)->get();
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse($academic_years, null);
            }
            return view('backEnd.systemSettings.academic_year', compact('academic_years'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function store(Request $request)
    {

        $input = $request->all();
        $validator = Validator::make($input, [
            'year' => 'required|numeric|digits:4',
            'starting_date' => 'required',
            'ending_date' => 'required',
            'title' => "required|max:150",
        ]);
        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        // $check_academicyear = SmAcademicYear::where('year', $request->year)->where('school_id', Auth::user()->school_id)->first();
        // if ($check_academicyear) {
        //     Toastr::error('Academic year already exist', 'Failed');
        //     return redirect()->back();
        // }

        //try {

        $yr = SmAcademicYear::orderBy('id', 'desc')->where('school_id', Auth::user()->school_id)->first();
        // $last_academic_year_id=SmAcademicYear::max('id');
// return $yr;
        // return $last_academic_year_id;
        // $created_year = $request->starting_date . '-01-01 12:00:00';
        $created_year = $request->starting_date;
        if ($yr == null) {
            $ye_year = $request->year;
        } else {
            $ye_year = $yr->year;
        }
        DB::beginTransaction();

        $academic_year = new SmAcademicYear();
        $academic_year->year = $request->year;
        $academic_year->title = $request->title;
        $academic_year->starting_date = date('Y-m-d', strtotime($request->starting_date));
        $academic_year->ending_date = date('Y-m-d', strtotime($request->ending_date));
        $academic_year->created_at = $created_year;
        $academic_year->school_id = Auth::user()->school_id;
        $result = $academic_year->save();
        if($result){
            session()->forget('academic_years');
            $academic_years = SmAcademicYear::where('active_status', 1)->where('school_id', Auth::user()->school_id)->get();
            session()->put('academic_years',$academic_years);
            
        }

        $sm_Gs = SmGeneralSettings::where('school_id', Auth::user()->school_id)->first();
        $sm_Gs->session_id = $academic_year->id;
        $sm_Gs->session_year = $academic_year->year;
        $sm_Gs->save();
        
        session()->forget('sessionId'); 
        session()->put('sessionId', $sm_Gs->session_id); 
        
        session()->forget('generalSetting');
        $generalSetting = SmGeneralSettings::where('school_id',Auth::user()->school_id)->first();
        session()->put('generalSetting', $generalSetting);

        $tables = ['App\SmClass', 'App\SmSection', 'App\SmExamType'];
        // $tables = ['App\SmClass', 'App\SmSection', 'App\SmSubject', 'App\SmExamSetup', 'App\SmExamType', 'App\SmMarksGrade', 'App\SmVehicle', 'App\SmClassTime', 'App\SmClassSection'];
        if ($yr) {
            foreach ($tables as $table_name) {
                $data = $table_name::where('academic_id', $yr->id)->where('school_id', Auth::user()->school_id)->get();
                if (!empty($data)) {
                    foreach ($data as $key => $value) {
                        $newClient = $value->replicate();
                        $newClient->created_at = $created_year;
                        $newClient->updated_at = $created_year;
                        $newClient->academic_id = $academic_year->id;
                        $newClient->save();
                    }
                }
            }
        }


        // $newClient->academic_id = getAcademicId();
        // Weekend for all school
        DB::table('sm_weekends')->insert([
            [
                'name' => 'Saturday',
                'order' => 1,
                'is_weekend' => 0,
                'created_at' => $created_year,
                'academic_id' => $academic_year->id,
                'school_id' => Auth::user()->school_id
            ],
            [
                'name' => 'Sunday',
                'order' => 2,
                'is_weekend' => 0,
                'created_at' => $created_year,
                'academic_id' => $academic_year->id,
                'school_id' => Auth::user()->school_id
            ],
            [
                'name' => 'Monday',
                'order' => 3,
                'is_weekend' => 0,
                'created_at' => $created_year,
                'academic_id' => $academic_year->id,
                'school_id' => Auth::user()->school_id
            ],
            [
                'name' => 'Tuesday',
                'order' => 4,
                'is_weekend' => 0,
                'created_at' => $created_year,
                'academic_id' => $academic_year->id,
                'school_id' => Auth::user()->school_id
            ],
            [
                'name' => 'Wednesday',
                'order' => 5,
                'is_weekend' => 0,
                'created_at' => $created_year,
                'academic_id' => $academic_year->id,
                'school_id' => Auth::user()->school_id
            ],
            [
                'name' => 'Thursday',
                'order' => 6,
                'is_weekend' => 0,
                'created_at' => $created_year,
                'academic_id' => $academic_year->id,
                'school_id' => Auth::user()->school_id
            ],
            [
                'name' => 'Friday',
                'order' => 7,
                'is_weekend' => 1,
                'created_at' => $created_year,
                'academic_id' => $academic_year->id,
                'school_id' => Auth::user()->school_id
            ]
        ]);

        $classes = SmClass::where('academic_id', $academic_year->id)->where('school_id', Auth::user()->school_id)->get();
        $sections = SmSection::where('academic_id', $academic_year->id)->where('school_id', Auth::user()->school_id)->get();


        foreach ($classes as $class) {
            foreach ($sections as $section) {

                $class_section = new SmClassSection();
                $class_section->class_id = $class->id;
                $class_section->section_id = $section->id;
                $class_section->created_at = $created_year;
                $class_section->school_id = Auth::user()->school_id;
                $class_section->academic_id = $academic_year->id;
                $class_section->save();
            }
        }

      
        DB::commit();
        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
            if ($result) {
                return ApiBaseMethod::sendResponse(null, 'Year has been created successfully');
            } else {
                return ApiBaseMethod::sendError('Something went wrong, please try again');
            }
        } else {
            if ($result) {
                Toastr::success('Operation successful', 'Success');
                return redirect()->back();
            } else {
                DB::rollBack();
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        }
        // } catch (\Exception $e) {
            // DB::rollBack();
        //     // dd($e);
        //     Toastr::error('Operation Failed', 'Failed');
        //     return redirect()->back();
        // }
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
            // $academic_year = SmAcademicYear::find($id);
             if (checkAdmin()) {
                $academic_year = SmAcademicYear::find($id);
            }else{
                $academic_year = SmAcademicYear::where('id',$id)->where('school_id',Auth::user()->school_id)->first();
            }
            $academic_years = SmAcademicYear::where('school_id', Auth::user()->school_id)->get();
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['academic_year'] = $academic_year->toArray();
                $data['academic_years'] = $academic_years->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }
            return view('backEnd.systemSettings.academic_year', compact('academic_year', 'academic_years'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Toastr::error('Operation Failed', 'Failed');
        return redirect()->back();
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
        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
            $validator = Validator::make($input, [
                'year' => 'required|numeric|digits:4',
                'title' => "required|max:150",
                'starting_date' => 'required',
                'ending_date' => 'required',
                'id' => "required"
            ]);
        } else {
            $validator = Validator::make($input, [
                'year' => 'required|numeric|digits:4',
                'title' => "required|max:150",
            ]);
        }


        // $check_academicyear = SmAcademicYear::where('year', $request->year)->where('school_id', Auth::user()->school_id)->where('id', '!=', $request->id)->first();
        // if ($check_academicyear) {
        //     Toastr::error('Academic year already exist', 'Failed');
        //     return redirect()->back();
        // }


        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        try {
            $yr = SmAcademicYear::orderBy('year', 'desc')->where('school_id', Auth::user()->school_id)->first();
            // $created_year = $request->year . '-01-01 12:00:00';
            $created_year = $request->starting_date;
            if ($yr->year != $request->year) {
                // $tables = ['App\SmClass', 'App\SmSection', 'App\SmSubject', 'App\SmExamSetup', 'App\SmExamType', 'App\SmMarksGrade', 'App\SmVehicle', 'App\SmClassTime'];
                $tables = ['App\SmClass', 'App\SmSection'];
                foreach ($tables as $table_name) {

                    $data = $table_name::where('academic_id', getAcademicId())->get();
                    if (!empty($data)) {
                        foreach ($data as $key => $value) {
                            $newClient = $value;
                            $newClient->created_at = $created_year;
                            $newClient->updated_at = $created_year;
                            $newClient->save();
                        }
                    }
                }
            }
            // $academic_year = SmAcademicYear::find($request->id);
             if (checkAdmin()) {
                $academic_year = SmAcademicYear::find($request->id);
            }else{
                $academic_year = SmAcademicYear::where('id',$request->id)->where('school_id',Auth::user()->school_id)->first();
            }
            $academic_year->year = $request->year;
            $academic_year->title = $request->title;
            // $academic_year->starting_date = $request->year . '-01-01';
            // $academic_year->ending_date = $request->year . '-12-31';
            $academic_year->starting_date = date('Y-m-d', strtotime($request->starting_date));
            // $academic_year->starting_date = $request->year . '-01-01';
            $academic_year->ending_date = date('Y-m-d', strtotime($request->ending_date));
            $academic_year->created_at = $created_year;
            $result = $academic_year->save();

            if($result){
                session()->forget('academic_years');
                $academic_years = SmAcademicYear::where('active_status', 1)->where('school_id', Auth::user()->school_id)->get();
                session()->put('academic_years',$academic_years);
            }

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Year has been updated successfully');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again');
                }
            } else {
                if ($result) {
                    Toastr::success('Operation successful', 'Success');
                    return redirect('academic-year');
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
    public function destroy(Request $request, $id)
    {


        try {
            $session_id = 'session_id';
            $tables = tableList::getTableList($session_id, $id);
            try {
                // $delete_query = SmAcademicYear::destroy($id);
                 if (checkAdmin()) {
                    $delete_query = SmAcademicYear::destroy($id);
                }else{
                    $delete_query = SmAcademicYear::where('id',$id)->where('school_id',Auth::user()->school_id)->delete();
                }
                if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                    if ($delete_query) {
                        return ApiBaseMethod::sendResponse(null, 'Academic Year has been deleted successfully');
                    } else {
                        return ApiBaseMethod::sendError('Something went wrong, please try again.');
                    }
                } else {
                    if ($delete_query) {
                       
                            session()->forget('academic_years');
                            $academic_years = SmAcademicYear::where('active_status', 1)->where('school_id', Auth::user()->school_id)->get();
                            session()->put('academic_years',$academic_years);
                        
                        Toastr::success('Operation successful', 'Success');
                        return redirect()->back();
                    } else {
                        Toastr::error('Operation Failed', 'Failed');
                        return redirect()->back();
                    }
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
}