<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class SmNotification extends Model
{
    public static function notifications(){
		
			$user = Auth()->user();
			// dd(Auth::user()->id);
			if ($user) {
				if($user->role_id == 2){
					return SmNotification::where('user_id', Auth::user()->id)->where('role_id', 2)->where('is_read', 0)->orderBy('id','DESC')->get();
					// return SmNotification::where('role_id', 2)->where('is_read', 0)->get();
				}
				if($user->role_id == 3){
					return SmNotification::where('user_id', Auth::user()->id)->where('role_id', 3)->where('is_read', 0)->orderBy('id','DESC')->get();
					// return SmNotification::where('role_id', 2)->where('is_read', 0)->get();
				}
				if($user->role_id == 1){
					return SmNotification::where('user_id', Auth::user()->id)->where('role_id', 1)->where('is_read', 0)->orderBy('id','DESC')->get();
					// return SmNotification::where('role_id', 2)->where('is_read', 0)->get();
				}
				if($user->role_id == 10){
					return SmNotification::where('is_read', 0)->orderBy('id','DESC')->get();
				}else{
					return SmNotification::where('user_id', $user->id)->where('role_id', '!=', 2)->where('is_read', 0)->orderBy('id','DESC')->get();
				}
			}

    }
}