<?php
namespace App\Http\Controllers;
use App\YearCheck;
use App\ApiBaseMethod;
use App\SmPostalReceive;
use App\SmGeneralSettings;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SmPostalReceiveController extends Controller
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
            $postal_receives = SmPostalReceive::where('school_id',Auth::user()->school_id)->where('academic_id', getAcademicId())->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse($postal_receives->toArray(), 'Postal retrieved successfully.');
            }
            return view('backEnd.admin.postal_receive', compact('postal_receives'));
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
                $fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                $file->move('public/uploads/postal/', $fileName);
                $fileName =  'public/uploads/postal/' . $fileName;
            }


            $postal_receive = new SmPostalReceive();
            $postal_receive->from_title = $request->from_title;
            $postal_receive->reference_no = $request->reference_no;
            $postal_receive->address = $request->address;
            $postal_receive->date = date('Y-m-d', strtotime($request->date));
            $postal_receive->note = $request->note;
            $postal_receive->to_title = $request->to_title;
            $postal_receive->file = $fileName;
            $postal_receive->school_id = Auth::user()->school_id;
            $postal_receive->academic_id = getAcademicId();
            $result = $postal_receive->save();


            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Postal has been created successfully.');
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
            $postal_receives = SmPostalReceive::where('school_id',Auth::user()->school_id)->get();

            // $postal_receive = SmPostalReceive::find($id);
             if (checkAdmin()) {
                $postal_receive = SmPostalReceive::find($id);
            }else{
                $postal_receive = SmPostalReceive::where('id',$id)->where('school_id',Auth::user()->school_id)->first();
            }

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['postal_receives'] = $postal_receives->toArray();
                $data['postal_receive'] = $postal_receive->toArray();

                return ApiBaseMethod::sendResponse($data, 'Postal retrieved successfully.');
            }
            return view('backEnd.admin.postal_receive', compact('postal_receives', 'postal_receive'));
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
                $postal_receive = SmPostalReceive::find($request->id);
                if ($postal_receive->file != "") {
                    if (file_exists($postal_receive->file)) {
                        unlink($postal_receive->file);
                    }
                }
                $file = $request->file('file');
                $fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                $file->move('public/uploads/postal/', $fileName);
                $fileName =  'public/uploads/postal/' . $fileName;
            }

             if (checkAdmin()) {
                    $postal_receive = SmPostalReceive::find($request->id);
                }else{
                    $postal_receive = SmPostalReceive::where('id',$request->id)->where('school_id',Auth::user()->school_id)->first();
                }
            $postal_receive->from_title = $request->from_title;
            $postal_receive->reference_no = $request->reference_no;
            $postal_receive->address = $request->address;
            $postal_receive->date = date('Y-m-d', strtotime($request->date));
            $postal_receive->note = $request->note;
            $postal_receive->to_title = $request->to_title;
            if ($fileName != "") {
                $postal_receive->file = $fileName;
            }
            $result = $postal_receive->save();
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Postal has been updated successfully.');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again.');
                }
            } else {
                if ($result) {
                    Toastr::success('Operation successful', 'Success');

                    return redirect('postal-receive');
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
             if (checkAdmin()) {
                $postal_receive = SmPostalReceive::find($id);
            }else{
                $postal_receive = SmPostalReceive::where('id',$id)->where('school_id',Auth::user()->school_id)->first();
            }
            if ($postal_receive->file != "") {
                if (file_exists($postal_receive->file)) {
                    unlink($postal_receive->file);
                }
            }
            $result = $postal_receive->delete();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Postal has been deleted successfully.');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again.');
                }
            } else {
                if ($result) {
                    Toastr::success('Operation successful', 'Success');

                    return redirect('postal-receive');
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