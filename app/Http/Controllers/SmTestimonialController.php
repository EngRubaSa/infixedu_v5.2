<?php
namespace App\Http\Controllers;
use App\SmTestimonial;
use App\SmGeneralSettings;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;

class SmTestimonialController extends Controller
{

    public function __construct()
	{
        $this->middleware('PM');
        // User::checkAuth();
    }

    public function index()
    {
        try{
            $testimonial = SmTestimonial::where('school_id',Auth::user()->school_id)->get();
            return view('backEnd.testimonial.testimonial_page', compact('testimonial'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }

    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'designation' => 'required',
            'institution_name' => 'required',
            'image' => "required|mimes:jpg,jpeg,png",
            'description' => 'required',
        ]);

        try{
            $maxFileSize = SmGeneralSettings::first('file_size')->file_size;
            $file = $request->file('image');
            $fileSize =  filesize($file);
            $fileSizeKb = ($fileSize / 1000000);
            if($fileSizeKb >= $maxFileSize){
                Toastr::error( 'Max upload file size '. $maxFileSize .' Mb is set in system', 'Failed');
                return redirect()->back();
            }
            $testimonial = new SmTestimonial();
            $image = "";
            if ($request->file('image') != "") {
                $file = $request->file('image');
                $image = 'stu-' . md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                $file->move('public/uploads/testimonial/', $image);
                $image =  'public/uploads/testimonial/' . $image;
            }
            $testimonial->name = $request->name;
            $testimonial->designation = $request->designation;
            $testimonial->institution_name = $request->institution_name;
            $testimonial->image = $image;
            $testimonial->description = $request->description;
            $testimonial->school_id = Auth::user()->school_id;
            $result = $testimonial->save();
            if ($result) {
                Toastr::success('Operation successful', 'Success');
                return redirect()->back();
            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }
    public function edit($id)
    {

        try{
            $testimonial = SmTestimonial::where('school_id',Auth::user()->school_id)->get();
            $add_testimonial = SmTestimonial::find($id);
            return view('backEnd.testimonial.testimonial_page', compact('add_testimonial', 'testimonial'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'image' => "sometimes|nullable|mimes:jpg,jpeg,png",
        ]);


        try{
            $maxFileSize = SmGeneralSettings::first('file_size')->file_size;
            $file = $request->file('image');
            $fileSize =  filesize($file);
            $fileSizeKb = ($fileSize / 1000000);
            if($fileSizeKb >= $maxFileSize){
                Toastr::error( 'Max upload file size '. $maxFileSize .' Mb is set in system', 'Failed');
                return redirect()->back();
            }
            $image = "";
            if ($request->file('image') != "") {
                $testimonial = SmTestimonial::find($request->id);
                if ($testimonial->image != "") {
                    unlink($testimonial->image);
                }

                $file = $request->file('image');
                $image = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                $file->move('public/uploads/testimonial/', $image);
                $image =  'public/uploads/testimonial/' . $image;
            }

            $testimonial = SmTestimonial::find($request->id);
            $testimonial->name = $request->name;
            $testimonial->designation = $request->designation;
            $testimonial->institution_name = $request->institution_name;
            if ($image != "") {
                $testimonial->image = $image;
            }
            $testimonial->description = $request->description;
            $result = $testimonial->save();
            if ($result) {
                Toastr::success('Operation successful', 'Success');
                return redirect('testimonial');
            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function testimonialDetails($id)
    {

        try{
            $testimonial = SmTestimonial::find($id);
            return view('backEnd.testimonial.testimonial_details', compact('testimonial'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }
    public function forDeleteTestimonial($id)
    {

        try{
            return view('backEnd.testimonial.delete_modal', compact('id'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }
    public function delete($id)
    {

        try{
            $testimonial = SmTestimonial::find($id);
            $result = $testimonial->delete();
            if ($result) {
                Toastr::success('Operation successful', 'Success');
                return redirect()->back();
            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }
}