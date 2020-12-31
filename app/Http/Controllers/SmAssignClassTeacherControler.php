<?php

namespace App\Http\Controllers;

use App\SmClass;
use App\SmStaff;
use App\SmSection;
use App\YearCheck;
use App\ApiBaseMethod;
use App\SmClassTeacher;
use Illuminate\Http\Request;
use App\SmAssignClassTeacher;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class SmAssignClassTeacherControler extends Controller
{
    public function __construct()
    {
        $this->middleware('PM');
        // User::checkAuth();
    }

    public function index(Request $request)
    {

        try {
            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $teachers = SmStaff::where('active_status', 1)->where('role_id', 4)->where('school_id', Auth::user()->school_id)->get();
            $assign_class_teachers = SmAssignClassTeacher::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['classes'] = $classes->toArray();
                $data['teachers'] = $teachers->toArray();
                $data['assign_class_teachers'] = $assign_class_teachers->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }

            return view('backEnd.academics.assign_class_teacher', compact('classes', 'teachers', 'assign_class_teachers'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function store(Request $request)
    {

        $input = $request->all();
        $validator = Validator::make(
            $input,
            [
                'class' => "required",
                'section' => "required",
                'teacher' => 'required|array',
            ],
            [
                'teacher.required' => 'At least one checkbox required!'
            ]
        );

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();

        try {

            $assign_class_teacher = new SmAssignClassTeacher();
            $assign_class_teacher->class_id = $request->class;
            $assign_class_teacher->section_id = $request->section;
            $assign_class_teacher->school_id = Auth::user()->school_id;
            $assign_class_teacher->academic_id = getAcademicId();
            $assign_class_teacher->save();
            $assign_class_teacher->toArray();

            foreach ($request->teacher as $teacher) {
                $class_teacher = new SmClassTeacher();
                $class_teacher->assign_class_teacher_id = $assign_class_teacher->id;
                $class_teacher->teacher_id = $teacher;
                $class_teacher->school_id = Auth::user()->school_id;
                $class_teacher->academic_id = getAcademicId();

                $class_teacher->save();
            }


            DB::commit();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse(null, 'Class Teacher has been Assigned successfully');
            }
            Toastr::success('Operation successful', 'Success');
            return redirect()->back();


            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Something went wrong, please try again.');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function edit(Request $request, $id)
    {

        try {
            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $teachers = SmStaff::where('active_status', 1)->where('role_id', 4)->where('school_id', Auth::user()->school_id)->get();
            $assign_class_teachers = SmAssignClassTeacher::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $assign_class_teacher = SmAssignClassTeacher::find($id);
            $sections = SmSection::where('active_status', '=', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();

            $teacherId = array();
            foreach ($assign_class_teacher->classTeachers as $classTeacher) {
                $teacherId[] = $classTeacher->teacher_id;
            }

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['assign_class_teacher'] = $assign_class_teacher;
                $data['classes'] = $classes->toArray();
                $data['teachers'] = $teachers->toArray();
                $data['assign_class_teachers'] = $assign_class_teachers->toArray();
                $data['sections'] = $sections->toArray();
                $data['teacherId'] = $teacherId;
                return ApiBaseMethod::sendResponse($data, null);
            }
            return view('backEnd.academics.assign_class_teacher', compact('assign_class_teacher', 'classes', 'teachers', 'assign_class_teachers', 'sections', 'teacherId'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function update(Request $request, $id)
    {

        $input = $request->all();
        $validator = Validator::make(
            $input,
            [
                'class' => "required",
                'section' => "required",
                'teacher' => 'required|array',
            ],
            [
                'teacher.required' => 'At least one checkbox required!'
            ]
        );

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }



        DB::beginTransaction();

        try {
            SmClassTeacher::where('assign_class_teacher_id', $request->id)->delete();

            $assign_class_teacher = SmAssignClassTeacher::find($request->id);
            $assign_class_teacher->class_id = $request->class;
            $assign_class_teacher->academic_id = getAcademicId();
            $assign_class_teacher->section_id = $request->section;
            $assign_class_teacher->save();
            $assign_class_teacher->toArray();

            foreach ($request->teacher as $teacher) {
                $class_teacher = new SmClassTeacher();
                $class_teacher->assign_class_teacher_id = $assign_class_teacher->id;
                $class_teacher->teacher_id = $teacher;
                $class_teacher->school_id = Auth::user()->school_id;
                $class_teacher->academic_id = getAcademicId();
                $class_teacher->save();
            }


            DB::commit();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse(null, 'Class Teacher has been updated successfully');
            }
            Toastr::success('Operation successful', 'Success');
            return redirect('assign-class-teacher');
        } catch (\Exception $e) {
            DB::rollBack();
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }

        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
            return ApiBaseMethod::sendError('Something went wrong, please try again.');
        }
        Toastr::error('Operation Failed', 'Failed');
        return redirect()->back();
    }

    public function destroy(Request $request, $id)
    {
        // return $id;
        try {
            $id_key = 'assign_class_teacher_id';
            $tables = \App\tableList::getTableList($id_key, $id);

            try {
                DB::beginTransaction();

                $delete_query = SmClassTeacher::where('assign_class_teacher_id', $id)->delete();
                $delete_query = SmAssignClassTeacher::destroy($id);

                DB::commit();
                if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                    if ($delete_query) {
                        return ApiBaseMethod::sendResponse(null, 'Class Teacher has been deleted successfully');
                    } else {
                        return ApiBaseMethod::sendError('Something went wrong, please try again.');
                    }
                } else {
                    if ($delete_query) {
                        Toastr::success('Operation successful', 'Success');
                        return redirect()->back();
                    } else {
                        DB::rollback();
                        Toastr::error('Operation Failed', 'Failed');
                        return redirect()->back();
                    }
                }
            } catch (\Illuminate\Database\QueryException $e) {
                DB::rollback();
                $msg = 'This data already used in  : ' . $tables . ' Please remove those data first';
                Toastr::error($msg, 'Failed');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}