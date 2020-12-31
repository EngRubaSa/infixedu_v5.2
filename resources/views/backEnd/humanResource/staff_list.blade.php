@extends('backEnd.master')
@section('mainContent')
<style type="text/css">
     .switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background: linear-gradient(90deg, #7c32ff 0%, #c738d8 51%, #7c32ff 100%);
}

input:focus + .slider {
  box-shadow: 0 0 1px linear-gradient(90deg, #7c32ff 0%, #c738d8 51%, #7c32ff 100%);
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
/* th,td{
    font-size: 9px !important;
    padding: 5px !important

} */
</style>
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('lang.staff_list')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('lang.dashboard')</a>
                <a href="#">@lang('lang.human_resource')</a>
                <a href="#">@lang('lang.staff_list')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-8 col-md-6 col-6">
                <div class="main-title xs_mt_0 mt_0_sm">
                    <h3 class="mb-30">@lang('lang.select_criteria') </h3>
                </div>
            </div>
            
@if(userPermission(162))

            <div class="col-lg-4 text-md-right text-left col-md-6 mb-30-lg col-6 text_sm_right">
                <a href="{{route('addStaff')}}" class="primary-btn small fix-gr-bg">
                    <span class="ti-plus pr-2"></span>
                    @lang('lang.add_staff')
                </a>
            </div>
@endif
        </div>
        <div class="row">
            <div class="col-lg-12"> 
              @if(session()->has('message-success'))
                  <div class="alert alert-success">
                  {{ session()->get('message-success') }}
                  </div>
                  @elseif(session()->has('message-danger'))
                  <div class="alert alert-danger">
                      {{ session()->get('message-danger') }}
                  </div>
              @endif
              </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="white-box">
                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'searchStaff', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                        <div class="row">
                            <div class="col-lg-4">
                              <select class="niceSelect w-100 bb form-control" name="role_id" id="role_id">
                                    <option data-display="Role" value=""> @lang('lang.select') </option>
                                    @foreach($roles as $key=>$value)
                                    <option value="{{$value->id}}">{{$value->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-4 mt-30-md">
                               <div class="col-lg-12">
                                <div class="input-effect">
                                    <input class="primary-input" type="text" placeholder=" @lang('lang.search_by_staff_id')" name="staff_no">
                                    <span class="focus-border"></span>
                                </div>
                            </div>
                           </div>
                            <div class="col-lg-4 mt-30-md">
                               <div class="col-lg-12">
                                <div class="input-effect">
                                    <input class="primary-input" type="text" placeholder="@lang('lang.search_by_name')" name="staff_name">
                                    <span class="focus-border"></span>
                                </div>
                            </div>
                           </div>
                        <div class="col-lg-12 mt-20 text-right">
                            <button type="submit" class="primary-btn small fix-gr-bg">
                                <span class="ti-search pr-2"></span>
                                @lang('lang.search')
                            </button>
                        </div>
                    </div>
            {{ Form::close() }}
            </div>
        </div>
    </div>
 <div class="row mt-40 full_wide_table">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-4 no-gutters">
                    <div class="main-title">
                        <h3 class="mb-0">@lang('lang.staff_list')</h3>
                    </div>
                </div>
            </div>

         <div class="row">
                <div class="col-lg-12">
                    <table id="table_id" class="display school-table" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>@lang('lang.staff') @lang('lang.no')</th>
                                <th>@lang('lang.name')</th>
                                <th>@lang('lang.role')</th>
                                <th>@lang('lang.department')</th>
                                <th>@lang('lang.description')</th>
                                <th>@lang('lang.mobile')</th>
                                <th>@lang('lang.email')</th>
                                <th>@lang('lang.status')</th>
                                <th>@lang('lang.action')</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($staffs as $value)
                            <tr id="{{@$value->id}}">
                                <td>{{$value->staff_no}}</td>
                                <td>{{$value->first_name}}&nbsp;{{$value->last_name}}</td>
                                <td>{{!empty($value->roles->name)?$value->roles->name:''}}</td>
                                <td>{{$value->departments !=""?$value->departments->name:""}}</td>
                                <td>{{$value->designations !=""?$value->designations->title:""}}</td>
                                <td>{{$value->mobile}}</td>
                                <td>{{$value->email}}</td>
                                <td>
                                    <label class="switch">
                                      <input type="checkbox" class="switch-input-staff" {{@$value->active_status == 0? '':'checked'}} {{@$value->role_id == 1? 'disabled':''}}>
                                      
                                      <span class="slider round"></span>
                                    </label>
                                </td>

                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                                            @lang('lang.select')
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" href="{{route('viewStaff', $value->id)}}">@lang('lang.view')</a>
                                           @if(userPermission(163))

                                            <a class="dropdown-item" href="{{route('editStaff', $value->id)}}">@lang('lang.edit')</a>
                                           @endif
                                           @if(userPermission(164))

                                            @if ($value->role_id != Auth::user()->role_id )
                                           
                                            {{-- <a class="dropdown-item modalLink" title="Delete Staff" data-modal-size="modal-md" href="{{route('deleteStaffView', $value->id)}}">@lang('lang.delete')</a> --}}
                                            <a  class="dropdown-item" href="#" data-toggle="modal" data-target="#deleteStaffModal{{$value->id}}" data-id="{{$value->id}}"  >@lang('lang.delete')</a>
                                               
                                            @endif
                                            @endif
                                       
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <div class="modal fade admin-query" id="deleteStaffModal{{$value->id}}" >
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Confirmation Required</h4>
                                            {{-- <h4 class="modal-title">@lang('lang.delete') @lang('lang.value')</h4> --}}
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                            
                                        <div class="modal-body">
                                            <div class="text-center">
                                                {{-- <h4>@lang('lang.are_you_sure_to_delete')</h4> --}}
                                                <h4 class="text-danger">You are going to remove {{@$value->first_name.' '.@$value->last_name}}. Removed data CANNOT be restored! Are you ABSOLUTELY Sure!</h4>
                                                {{-- <div class="alert alert-warning">@lang('lang.student_delete_note')</div> --}}
                                            </div>
                            
                                            <div class="mt-40 d-flex justify-content-between">
                                                <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('lang.cancel')</button>
                                                <a href="{{route('deleteStaff',$value->id)}}" class="text-light">
                                                    <button class="primary-btn fix-gr-bg" type="submit">@lang('lang.delete')</button>
                                                
                                                     </a>
                                            </div>
                                        </div>
                            
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
@endsection
