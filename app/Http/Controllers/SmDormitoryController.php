<?php

namespace App\Http\Controllers;

use App\SmClass;
use App\SmStudent;
use App\YearCheck;
use App\ApiBaseMethod;
use App\SmDormitoryList;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;

class SmDormitoryController extends Controller
{
    public function __construct()
	{
        $this->middleware('PM');
        // User::checkAuth();
	}


    public function studentDormitoryReport(Request $request)
    {
        try{
            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())
            ->where('school_id',Auth::user()->school_id)->get();
            $dormitories = SmDormitoryList::where('active_status', 1)->where('school_id',Auth::user()->school_id)->get();
            $students = SmStudent::where('active_status', 1)->where('academic_id', getAcademicId())
                ->where('dormitory_id', '!=', "")->limit(100)->where('school_id',Auth::user()->school_id)->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['classes'] = $classes->toArray();
                $data['dormitories'] = $dormitories->toArray();
                $data['students'] = $students->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }
            return view('backEnd.dormitory.student_dormitory_report', compact('classes', 'students', 'dormitories'));
        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }

    }


    public function studentDormitoryReportSearch(Request $request)
    {
        try{
            $students = SmStudent::query();
            $students->where('active_status', 1);
            if ($request->class != "") {
                $students->where('class_id', $request->class);
            }
            if ($request->section != "") {
                $students->where('section_id', $request->section);
            }
            if ($request->dormitory != "") {
                $students->where('dormitory_id', $request->dormitory);
            } else {
                $students->where('dormitory_id', '!=', '');
            }
            $students = $students->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            $dormitories = SmDormitoryList::where('active_status', 1)->where('school_id',Auth::user()->school_id)->get();
            $class_id = $request->class;
            $dormitory_id = $request->dormitory;

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['classes'] = $classes->toArray();
                $data['dormitories'] = $dormitories->toArray();
                $data['students'] = $students->toArray();
                $data['class_id'] = $class_id;
                $data['dormitory_id'] = $dormitory_id;
                return ApiBaseMethod::sendResponse($data, null);
            }

            return view('backEnd.dormitory.student_dormitory_report', compact('classes', 'dormitories', 'students', 'class_id', 'dormitory_id'));
        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}
