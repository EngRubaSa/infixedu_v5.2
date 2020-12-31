<?php

namespace App\Http\Controllers;
use App\YearCheck;
use App\SmMarksGrade;
use App\ApiBaseMethod;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SmMarksGradeController extends Controller
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
            $marks_grades = SmMarksGrade::orderBy('gpa', 'desc')->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            // return $marks_grades;
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse($marks_grades, null);
            }
            return view('backEnd.examination.marks_grade', compact('marks_grades'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'grade_name' => "required|max:50",
            'gpa' => "required|max:4",
            'percent_from' => "required|integer||min:0",
            'percent_upto' => "required|integer|min:".@$request->percent_from,
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
         $is_duplicate = SmMarksGrade::where('school_id', Auth::user()->school_id)->where('grade_name', $request->grade_name)->where('academic_id', getAcademicId())->first();
         if ($is_duplicate) {
             Toastr::error('Duplicate name found!', 'Failed');
             return redirect()->back()->withErrors($validator)->withInput();
         }
         $is_duplicate_gpa = SmMarksGrade::where('school_id', Auth::user()->school_id)->where('gpa', $request->gpa)->where('academic_id', getAcademicId())->first();
         if ($is_duplicate_gpa) {
             Toastr::error('Duplicate GPA found!', 'Failed');
             return redirect()->back()->withErrors($validator)->withInput();
         }
        try{
            $marks_grade = new SmMarksGrade();
            $marks_grade->grade_name = $request->grade_name;
            $marks_grade->gpa = $request->gpa;
            $marks_grade->percent_from = $request->percent_from;
            $marks_grade->percent_upto = $request->percent_upto;
            $marks_grade->description = $request->description;
            $marks_grade->created_at= YearCheck::getYear() .'-'.date('m-d h:i:s');
            $marks_grade->school_id = Auth::user()->school_id;
            $marks_grade->academic_id = getAcademicId();

            $result = $marks_grade->save();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Grade has been created successfully');
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
             if (checkAdmin()) {
                $marks_grade = SmMarksGrade::find($id);
            }else{
                $marks_grade = SmMarksGrade::where('id',$id)->where('school_id',Auth::user()->school_id)->first();
            }
            $marks_grades = SmMarksGrade::where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['marks_grade'] = $marks_grade->toArray();
                $data['marks_grades'] = $marks_grades->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }
            return view('backEnd.examination.marks_grade', compact('marks_grade', 'marks_grades'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function update(Request $request, $id)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'grade_name' => "required|max:50",
            'gpa' => "required|max:4",
            'percent_from' => "required|integer||min:0",
            'percent_upto' => "required|integer|min:".@$request->percent_from,
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
        $is_duplicate = SmMarksGrade::where('school_id', Auth::user()->school_id)->where('academic_id', getAcademicId())->where('grade_name', $request->grade_name)->where('id', '!=', $request->id)->first();
        if ($is_duplicate) {
            Toastr::error('Duplicate name found!', 'Failed');
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $is_duplicate_gpa = SmMarksGrade::where('school_id', Auth::user()->school_id)->where('academic_id', getAcademicId())->where('gpa', $request->gpa)->where('id', '!=', $request->id)->first();
        if ($is_duplicate_gpa) {
            Toastr::error('Duplicate GPA found!', 'Failed');
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try{
            // $marks_grade = SmMarksGrade::find($request->id);
            if (checkAdmin()) {
                $marks_grade = SmMarksGrade::find($request->id);
            }else{
                $marks_grade = SmMarksGrade::where('id',$request->id)->where('school_id',Auth::user()->school_id)->first();
            }
            $marks_grade->grade_name = $request->grade_name;
            $marks_grade->gpa = $request->gpa;
            $marks_grade->percent_from = $request->percent_from;
            $marks_grade->percent_upto = $request->percent_upto;
            $marks_grade->description = $request->description;
            $result = $marks_grade->save();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Grade has been updated successfully');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again.');
                }
            } else {
                if ($result) {
                    Toastr::success('Operation successful', 'Success');
                    return redirect('marks-grade');
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {

        try{
            // $marks_grade = SmMarksGrade::destroy($id);
            if (checkAdmin()) {
                $marks_grade = SmMarksGrade::destroy($id);
            }else{
                $marks_grade = SmMarksGrade::where('id',$id)->where('school_id',Auth::user()->school_id)->delete();
            }
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($marks_grade) {
                    return ApiBaseMethod::sendResponse(null, 'Grdae has been deleted successfully');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again.');
                }
            } else {
                if ($marks_grade) {
                    Toastr::success('Operation successful', 'Success');
                    return redirect('marks-grade');
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
}