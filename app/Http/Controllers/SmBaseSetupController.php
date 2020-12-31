<?php

namespace App\Http\Controllers;


use App\tableList;
use App\SmBaseGroup;
use App\SmBaseSetup;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;


class SmBaseSetupController extends Controller
{
	public function __construct()
	{
        $this->middleware('PM');
        // User::checkAuth();
	}

	public function index()
	{

		try {
			$base_groups = SmBaseGroup::where('active_status', '=', 1)->get();
			return view('backEnd.systemSettings.baseSetup.base_setup', compact('base_groups'));
		} catch (\Exception $e) {
			Toastr::error('Operation Failed', 'Failed');
			return redirect()->back();
		}
	}
	public function store(Request $request)
	{
		$request->validate([
			'name' => "required|max:100",
			'base_group' => "required"
		]);

		try {
			$base_setup = new SmBaseSetup();
			$base_setup->base_setup_name = $request->name;
			$base_setup->base_group_id = $request->base_group;
			$base_setup->school_id = Auth::user()->school_id;
			$result = $base_setup->save();
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
			// $base_setup = SmBaseSetup::find($id);
			 if (checkAdmin()) {
				$base_setup = SmBaseSetup::find($id);
			}else{
				$base_setup = SmBaseSetup::where('id',$id)->where('school_id',Auth::user()->school_id)->first();
			}
			$base_groups = SmBaseGroup::where('active_status', '=', 1)->get();
			return view('backEnd.systemSettings.baseSetup.base_setup', compact('base_setup', 'base_groups'));
		} catch (\Exception $e) {
			Toastr::error('Operation Failed', 'Failed');
			return redirect()->back();
		}
	}

	public function update(Request $request)
	{
		$request->validate([
			'name' => "required|max:100",
			'base_group' => "required"
		]);

		try {
			// $base_group = SmBaseSetup::find($request->id);
			 if (checkAdmin()) {
				$base_setup = SmBaseSetup::find($request->id);
			}else{
				$base_setup = SmBaseSetup::where('id',$request->id)->where('school_id',Auth::user()->school_id)->first();
			}
			$base_group->base_setup_name = $request->name;
			$base_group->base_group_id = $request->base_group;
			$result = $base_group->save();
			if ($result) {
				Toastr::success('Operation successful', 'Success');
				return redirect('base-setup');
			} else {
				Toastr::error('Operation Failed', 'Failed');
				return redirect()->back();
			}
		} catch (\Exception $e) {
			Toastr::error('Operation Failed', 'Failed');
			return redirect()->back();
		}
	}

	public function delete(Request $request)
	{

		try {
			try {
				// $delete_query = SmBaseSetup::destroy($request->id);
				 if (checkAdmin()) {
					$delete_query = SmBaseSetup::destroy($request->id);
				}else{
					$delete_query = SmBaseSetup::where('id',$request->id)->where('school_id',Auth::user()->school_id)->delete();
				}
				if ($delete_query) {
					Toastr::success('Operation successful', 'Success');
					return redirect('base-setup');
				} else {
					Toastr::error('Operation Failed', 'Failed');
					return redirect()->back();
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
