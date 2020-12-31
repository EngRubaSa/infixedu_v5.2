<?php

namespace App\Http\Controllers;

use App\SmCourse;
use App\SmGeneralSettings;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;

class SmCourseController extends Controller
{
    public function __construct()
	{
        $this->middleware('PM');
        // User::checkAuth();
	}
    public function index()
    {

        try{
        $course = SmCourse::where('school_id',Auth::user()->school_id)->get();
        return view('backEnd.course.course_page', compact('course'));

        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }

    }
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'image' => 'required|mimes:jpg,jpeg,png',
            'overview' => 'required',
            'outline' => 'required',
            'prerequisites' => 'required',
            'resources' => 'required',
            'stats' => 'required',
        ]);
        try {
            $course = new SmCourse();
            $image = "";
            if ($request->file('image') != "") {
                $maxFileSize = SmGeneralSettings::first('file_size')->file_size;
                $file = $request->file('image');
                $fileSize =  filesize($file);
                $fileSizeKb = ($fileSize / 1000000);
                if($fileSizeKb >= $maxFileSize){
                    Toastr::error( 'Max upload file size '. $maxFileSize .' Mb is set in system', 'Failed');
                    return redirect()->back();
                }
                $file = $request->file('image');
                $image = 'stu-' . md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                $file->move('public/uploads/course/', $image);
                $image = 'public/uploads/course/' . $image;
            }



            $course->title = $request->title;
            $course->image = $image;
            $course->overview = $request->overview;
            $course->outline = $request->outline;
            $course->prerequisites = $request->prerequisites;
            $course->resources = $request->resources;
            $course->stats = $request->stats;
            $course->school_id = Auth::user()->school_id;
            $result = $course->save();
            if ($result) {
                Toastr::success('Operation successful', 'Success');
                return redirect()->back();
            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function edit($id)
    {

        try {
            $course = SmCourse::where('school_id',Auth::user()->school_id)->get();
            $add_course = SmCourse::find($id);
            return view('backEnd.course.course_page', compact('course', 'add_course'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'overview' => 'required',
            'outline' => 'required',
            'prerequisites' => 'required',
            'resources' => 'required',
            'stats' => 'required',
            'image' => "sometimes|nullable|mimes:jpg,jpeg,png",
        ]);
        try {
            $course = SmCourse::find($request->id);
            $image = "";
            if ($request->file('image') != "") {
                $maxFileSize = SmGeneralSettings::first('file_size')->file_size;
                $file = $request->file('image');
                $fileSize =  filesize($file);
                $fileSizeKb = ($fileSize / 1000000);
                if($fileSizeKb >= $maxFileSize){
                    Toastr::error( 'Max upload file size '. $maxFileSize .' Mb is set in system', 'Failed');
                    return redirect()->back();
                }
                $course = SmCourse::find($request->id);
                if ($course->image != "") {
                    unlink($course->image);
                }

                $file = $request->file('image');
                $image = 'stu-' . md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                $file->move('public/uploads/course/', $image);
                $image = 'public/uploads/course/' . $image;
            }
            $course = SmCourse::find($request->id);
            $course->title = $request->title;
            if ($image != "") {
                $course->image = $image;
            }
            $course->overview = $request->overview;
            $course->outline = $request->outline;
            $course->prerequisites = $request->prerequisites;
            $course->resources = $request->resources;
            $course->stats = $request->stats;
            $result = $course->save();
            if ($result) {
                Toastr::success('Operation successful', 'Success');
                return redirect()->back();
            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function destroy($id)
    {
        try {
            $course = SmCourse::find($id);
            $result = $course->delete();
            if ($result) {
                Toastr::success('Operation successful', 'Success');
                return redirect()->back();
            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function forDeleteCourse($id)
    {
        try {
            return view('backEnd.course.delete_modal', compact('id'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function courseDetails($id)
    {

        try {
            $course = SmCourse::find($id);
            return view('backEnd.course.course_details', compact('course'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}
