<?php

namespace App\Http\Controllers;
use App\YearCheck;
use App\ApiBaseMethod;
use App\SmPostalDispatch;
use App\SmGeneralSettings;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class SmPostalDispatchController extends Controller
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
            $postal_dispatchs = SmPostalDispatch::where('school_id',Auth::user()->school_id)->where('academic_id', getAcademicId())->get();
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse($postal_dispatchs->toArray(), 'Postal dispatchs retrieved successfully.');
            }
            return view('backEnd.admin.postal_dispatch', compact('postal_dispatchs'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'from_title' => "required|max:250",
            'reference_no' => "required|max:150",
            'address' => "required|max:250",
            'to_title' => "required|max:250",
            'file' => "sometimes|nullable|mimes:pdf,doc,docx,jpg,jpeg,png,txt",
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
            $maxFileSize = SmGeneralSettings::first('file_size')->file_size;
            $file = $request->file('file');
            $fileSize =  filesize($file);
            $fileSizeKb = ($fileSize / 1000000);
            if($fileSizeKb >= $maxFileSize){
                Toastr::error( 'Max upload file size '. $maxFileSize .' Mb is set in system', 'Failed');
                return redirect()->back();
            }
            $fileName = "";
            if ($request->file('file') != "") {
                $file = $request->file('file');
                $fileName = 'dis-' . md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                $file->move('public/uploads/postal/', $fileName);
                $fileName =  'public/uploads/postal/' . $fileName;
            }
            $postal_dispatch = new SmPostalDispatch();
            $postal_dispatch->from_title = $request->from_title;
            $postal_dispatch->reference_no = $request->reference_no;
            $postal_dispatch->address = $request->address;
            $postal_dispatch->date = date('Y-m-d', strtotime($request->date));
            $postal_dispatch->note = $request->note;
            $postal_dispatch->to_title = $request->to_title;
            $postal_dispatch->file = $fileName;
            $postal_dispatch->school_id = Auth::user()->school_id;
            $postal_dispatch->academic_id = getAcademicId();
            $result = $postal_dispatch->save();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Postal dispatch has been created successfully.');
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
            $postal_dispatchs = SmPostalDispatch::where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
           
           

             if (checkAdmin()) {
                    $postal_dispatch = SmPostalDispatch::find($id);
                }else{
                    $postal_dispatch = SmPostalDispatch::where('id',$id)->where('school_id',Auth::user()->school_id)->first();
                }
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['postal_dispatchs'] = $postal_dispatchs->toArray();
                $data['postal_dispatch'] = $postal_dispatch->toArray();

                return ApiBaseMethod::sendResponse($data, 'Postal retrieved successfully.');
            }
            return view('backEnd.admin.postal_dispatch', compact('postal_dispatchs', 'postal_dispatch'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }
    public function update(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'from_title' => "required|max:250",
            'reference_no' => "required|max:150",
            'address' => "required|max:250",
            'to_title' => "required|max:250",
            'file' => "sometimes|nullable|mimes:pdf,doc,docx,jpg,jpeg,png,txt",
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
            $maxFileSize = SmGeneralSettings::first('file_size')->file_size;
            $file = $request->file('file');
            $fileSize =  filesize($file);
            $fileSizeKb = ($fileSize / 1000000);
            if($fileSizeKb >= $maxFileSize){
                Toastr::error( 'Max upload file size '. $maxFileSize .' Mb is set in system', 'Failed');
                return redirect()->back();
            }
            $fileName = "";
            if ($request->file('file') != "") {
                $postal_dispatch = SmPostalDispatch::find($request->id);
                if ($postal_dispatch->file != "") {
                    if (file_exists($postal_dispatch->file)) {
                        unlink($postal_dispatch->file);
                    }
                }

                $file = $request->file('file');
                $fileName = 'dis' . md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                $file->move('public/uploads/postal/', $fileName);
                $fileName =  'public/uploads/postal/' . $fileName;
            }

             if (checkAdmin()) {
                    $postal_dispatch = SmPostalDispatch::find($request->id);
                }else{
                    $postal_dispatch = SmPostalDispatch::where('id',$request->id)->where('school_id',Auth::user()->school_id)->first();
                }
            $postal_dispatch->from_title = $request->from_title;
            $postal_dispatch->reference_no = $request->reference_no;
            $postal_dispatch->address = $request->address;
            $postal_dispatch->date = date('Y-m-d', strtotime($request->date));
            $postal_dispatch->note = $request->note;
            $postal_dispatch->to_title = $request->to_title;
            if ($fileName != "") {
                $postal_dispatch->file = $fileName;
            }
            $result = $postal_dispatch->save();


            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Postal has been updated successfully.');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again.');
                }
            } else {
                if ($result) {
                    Toastr::success('Operation successful', 'Success');
                    return redirect('postal-dispatch');
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
            $postal_dispatch = SmPostalDispatch::find($id);
            if ($postal_dispatch->file != "") {
                if (file_exists($postal_dispatch->file)) {
                    unlink($postal_dispatch->file);
                }
            }
            $result = $postal_dispatch->delete();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Postal dispatch has been deleted successfully.');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again.');
                }
            } else {
                if ($result) {
                    Toastr::success('Operation successful', 'Success');
                    return redirect('postal-dispatch');
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