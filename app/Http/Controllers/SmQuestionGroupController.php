<?php

namespace App\Http\Controllers;
use App\YearCheck;
use App\SmQuestionGroup;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;

class SmQuestionGroupController extends Controller
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
    public function index()
    {
        try{
            $groups = SmQuestionGroup::where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            return view('backEnd.examination.question_group', compact('groups'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => "required|unique:sm_question_groups"
        ]);
        try{
            $group = new SmQuestionGroup();
            $group->title = $request->title;
            $group->school_id = Auth::user()->school_id;
            $group->academic_id = getAcademicId();
            $result = $group->save();
            if ($result) {
                Toastr::success('Operation successful', 'Success');
                return redirect()->back();
            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
                // return redirect()->back()->with('message-danger', 'Something went wrong, please try again');
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
    public function show($id)
    {
        try{
            // $group = SmQuestionGroup::find($id);
             if (checkAdmin()) {
                $group = SmQuestionGroup::find($id);
            }else{
                $group = SmQuestionGroup::where('id',$id)->where('school_id',Auth::user()->school_id)->first();
            }
            $groups = SmQuestionGroup::where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            return view('backEnd.examination.question_group', compact('groups', 'group'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => "required|unique:sm_question_groups,title," . $request->id
        ]);
        try{
            // $group = SmQuestionGroup::find($request->id);
             if (checkAdmin()) {
                $group = SmQuestionGroup::find($request->id);
            }else{
                $group = SmQuestionGroup::where('id',$request->id)->where('school_id',Auth::user()->school_id)->first();
            }
            $group->title = $request->title;
            $result = $group->save();
            if ($result) {
                Toastr::success('Operation successful', 'Success');
                return redirect('question-group');
            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
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
    public function destroy($id)
    {
        $tables = \App\tableList::getTableList('question_group_id', $id);

        try{
            if ($tables==null) {
                // $group = SmQuestionGroup::destroy($id);
                 if (checkAdmin()) {
                        $group = SmQuestionGroup::destroy($id);
                    }else{
                        $group = SmQuestionGroup::where('id',$id)->where('school_id',Auth::user()->school_id)->delete();
                    }
                if ($group) {
                    Toastr::success('Operation successful', 'Success');
                    return redirect('question-group');
                } else {
                    Toastr::error('Operation Failed', 'Failed');
                    return redirect()->back();
                }
            } else {
                $msg = 'This data already used in  : ' . $tables . ' Please remove those data first';
                Toastr::error($msg, 'Failed');
                return redirect()->back();
            }


        }catch (\Exception $e) {
           $msg = 'This data already used in  : ' . $tables . ' Please remove those data first';
            Toastr::error($msg, 'Failed');
           return redirect()->back();
        }
    }
}