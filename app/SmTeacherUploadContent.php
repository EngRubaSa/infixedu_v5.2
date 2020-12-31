<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmTeacherUploadContent extends Model
{
 
	public function contentTypes()
	{
		return $this->belongsTo('App\SmContentType', 'content_type', 'id');
	}

	public function roles()
	{
		return $this->belongsTo('Modules\RolePermission\Entities\InfixRole', 'available_for', 'id');
	}

	public function classes()
	{
		return $this->belongsTo('App\SmClass', 'class', 'id');
	}
	public function sections()
	{
		return $this->belongsTo('App\SmSection', 'section', 'id');
	}
	public function users(){
		return $this->belongsTo('App\User', 'created_by', 'id');
	}
}