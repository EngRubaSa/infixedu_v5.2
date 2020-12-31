@extends('backEnd.master')
@section('mainContent')
<link rel="stylesheet" href="{{asset('public/backEnd/css/login_access_control.css')}}"/>
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('lang.login_permission') </h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('lang.dashboard')</a>
                <a href="#">@lang('lang.system_settings')</a>
                <a href="#">@lang('lang.login_permission')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="main-title">
                        <h3 class="mb-30">@lang('lang.select_criteria')</h3>
                    </div>
                </div>
            </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-12">
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'login-access-control', 'enctype' => 'multipart/form-data', 'method' => 'POST']) }}
                        <div class="white-box">
                            <div class="add-visitor">
                                <div class="row">
                                    <div class="col-lg-6 mb-30">
                                    @if(session()->has('message-success'))
                                    <div class="alert alert-success">
                                        {{ session()->get('message-success') }}
                                    </div>
                                    @elseif(session()->has('message-danger'))
                                    <div class="alert alert-danger">
                                        {{ session()->get('message-danger') }}
                                    </div>
                                    @endif

                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            @foreach ($errors->all() as $error)
                                                {{ $error }} <br>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                                    <div class="col-lg-12 mb-30">
                                        <select class="niceSelect w-100 bb form-control{{ $errors->has('role') ? ' is-invalid' : '' }}" name="role" id="member_type">
                                            <option data-display=" @lang('lang.select_role') *" value="">@lang('lang.select_role') *</option>
                                            @foreach($roles as $value)
                                                <option value="{{@$value->id}}">{{@$value->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="forStudentWrapper col-lg-12">
                                        <div class="row">
                                            <div class="col-lg-6 mb-30">
                                                <select class="w-100 bb niceSelect form-control {{ $errors->has('class') ? ' is-invalid' : '' }}" id="select_class" name="class">
                                                    <option data-display="@lang('lang.select_class') *" value="">@lang('lang.select_class')*</option>
                                                    @foreach($classes as $class)
                                                    <option value="{{@$class->id}}"  {{( old("class") == @$class->id ? "selected":"")}}>{{@$class->class_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-lg-6 mb-30" id="select_section__member_div">
                                                <select class="w-100 bb niceSelect form-control{{ $errors->has('section') ? ' is-invalid' : '' }}" id="select_section_member" name="section">
                                                    <option data-display="@lang('lang.select_section')" value="">@lang('lang.select_section') *</option>
                                                </select>
                                            </div>

                                        </div>
                                    </div>



                                    <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">

                                </div>

                                <div class="col-lg-12 mt-20 text-right">
                                    <button type="submit" class="primary-btn small fix-gr-bg">
                                        <span class="ti-search pr-2"></span>
                                        @lang('lang.search')
                                    </button>
                                </div>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
{{-- {{ dd($students) }} --}}

        @if(isset($students))
            <div class="row mt-40">


                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-4 no-gutters">
                            <div class="main-title">
                                <h3 class="mb-0">@lang('lang.student_list') ({{@$students->count()}})</h3>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-lg-12 table-responsive">
                            <table id="" class="display school-table school-table-style" cellspacing="0" width="100%">

                                <thead>
                                    <tr>
                                        <th>@lang('lang.admission') </th>
                                        <th>@lang('lang.roll')</th>
                                        <th>@lang('lang.name')</th>
                                        <th>@lang('lang.class')</th>

                                        <th>@lang('lang.student')  @lang('lang.permission')</th>
                                        <th>@lang('lang.student')  @lang('lang.password')</th>

                                        <th>@lang('lang.parents')  @lang('lang.permission')</th>
                                        <th>@lang('lang.parents')  @lang('lang.password')</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($students as $student)
                                    <tr id="{{@$student->user_id}}">
                                        
                                        <td>
                                            <input type="hidden" id="id" value="{{@$student->user_id}}">
                                            <input type="hidden" id="role" value="{{@$role}}">
                                             {{@$student->admission_no}}
                                        </td>
                                        <td> {{@$student->roll_no}}</td>
                                        <td>{{@$student->first_name.' '.@$student->last_name}}  </td>
                                        <td>{{!empty(@$student->className)?@$student->className->class_name:''}} ({{!empty(@$student->className)?@$student->section->section_name:''}})</td>
                                        <td>
                                            <input type="hidden" name="id" value="{{$student->user_id}}">
                                            <label class="switch">
                                              <input type="checkbox" id="ch{{@$student->user_id}}" onclick="lol({{@$student->user_id}},{{@$role}})" class="switch-input11" {{@$student->user->access_status == 0? '':'checked'}}>
                                              <span class="slider round"></span>
                                            </label>
                                        </td>
                                        <td style="white-space: nowrap;">
                                            {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'reset-student-password', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                                                <input type="hidden" name="id" value="{{@$student->user_id}}">
                                                <div class="row mt-10">
                                                    <div class="col-lg-6">
                                                        <div class="input-effect md_mb_20">
                                                            <input class="primary-input read-only-input" type="text" name="new_password" required="true" minlength="6">
                                                            <label>@lang('lang.password')</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <button type="submit" class="primary-btn small tr-bg icon-only mt-10"  data-toggle="tooltip" title="Update Password" >
                                                            <span class="ti-save"></span>
                                                        </button>
                                                        <button data-toggle="tooltip" title="Reset Password as Default" type="button" class="primary-btn small tr-bg icon-only mt-10" onclick="changePassword({{@$student->user_id}},{{@$role}})" >
                                                            <span class="ti-reload"></span>
                                                        </button>
                                                    </div>
                                                </div>
                                            {{ Form::close() }}
                                        </td>
                                        <td>
                                            @php $parent = App\SmParent::where('id',$student->parent_id)->first();@endphp
                                            <input type="hidden" name="ParentID" value="{{$parent->user_id}}" id="ParentID">
                                            <label class="switch">
                                                <input type="checkbox" class="parent-login-disable " {{@$parent->parent_user->access_status == 0? '':'checked'}}>
                                                <span class="slider round"></span>
                                            </label>

                                        </td>
                                        <td style="white-space: nowrap;">
                                            {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'reset-student-password', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                                                <input type="hidden" name="id" value="{{$student->user_id}}">
                                                <div class="row mt-10">
                                                    <div class="col-lg-6">
                                                        <div class="input-effect md_mb_20">
                                                            <input class="primary-input read-only-input" type="text" name="new_password" required="true" minlength="6">
                                                            <label>@lang('lang.password')</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <button type="submit" class="primary-btn small tr-bg icon-only mt-10"  data-toggle="tooltip" title="Update Password" >
                                                            <span class="ti-save"></span>
                                                        </button>
                                                        <button data-toggle="tooltip" title="Reset Password as Default" type="button" class="primary-btn small tr-bg icon-only mt-10"
                                                        onclick="changePassword({{@$student->parents->user_id}},{{@$role}})" >
                                                            <span class="ti-reload"></span>
                                                        </button>
                                                    </div>
                                                </div>
                                            {{ Form::close() }}
                                        </td>
                                    </tr>

                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if(isset($staffs))
             <div class="row mt-40">
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
                                {{-- <th>@lang('lang.department')</th> --}}
                                {{-- <th>@lang('lang.description')</th> --}}
                                {{-- <th>@lang('lang.mobile')</th> --}}
                                <th>@lang('lang.email')</th>
                                <th>@lang('lang.login_permission')</th>
                                <th>@lang('lang.password')</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($staffs as $value)
                            <tr id="{{@$value->user_id}}">
                                <input type="hidden" id="id" value="{{@$value->user_id}}">
                                <input type="hidden" id="role" value="{{@$role}}">
                                <td>{{@$value->staff_no}}</td>
                                <td>{{@$value->first_name}}&nbsp;{{@$value->last_name}}</td>
                                <td>{{!empty(@$value->roles->name)?@$value->roles->name:''}}</td>
                                {{-- <td>{{@$value->departments !=""?@$value->departments->name:""}}</td> --}}
                                {{-- <td>{{@$value->designations !=""?@$value->designations->title:""}}</td> --}}
                                {{-- <td>{{@$value->mobile}}</td> --}}
                                <td>{{@$value->email}}</td>
                                <td>
                                    @php
                                        if(@$value->staff_user->access_status == 0){
                                                $permission_id=422;
                                        }else{
                                                $permission_id=423;
                                        }
                                    @endphp
                                    @if(userPermission($permission_id))
                                    <label class="switch">
                                        <input type="checkbox" class="switch-input" {{@$value->staff_user->access_status == 0? '':'checked'}}>
                                        <span class="slider round"></span>
                                    </label>
                                    @endif
                                </td>
                                <td style="white-space: nowrap;">
                                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'reset-student-password', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                                        <input type="hidden" name="id" value="{{$value->user_id}}">
                                        <div class="row mt-10">
                                            <div class="col-lg-6">
                                                <div class="input-effect md_mb_20">
                                                    <input class="primary-input read-only-input" type="text" name="new_password" required="true" minlength="6">
                                                    <label>@lang('lang.password')</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <button type="submit" class="primary-btn small tr-bg icon-only mt-10"  data-toggle="tooltip" title="Update Password" >
                                                    <span class="ti-save"></span>
                                                </button>
                                                <button data-toggle="tooltip" title="Reset Password as Default" type="button" class="primary-btn small tr-bg icon-only mt-10"
                                                onclick="changePassword({{@$value->user_id}},{{@$role}})" >
                                                    <span class="ti-reload"></span>
                                                </button>
                                            </div>
                                        </div>
                                    {{ Form::close() }}
                                </td>

                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
        @endif

        @if(isset($parents))
            <div class="row mt-40">


                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-4 no-gutters">
                            <div class="main-title">
                                <h3 class="mb-0">@lang('lang.parents') @lang('lang.list')</h3>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-lg-12">
                            <table id="table_id" class="display school-table" cellspacing="0" width="100%">
                                <thead>
                                    @if(session()->has('message-success') != "" ||
                                    session()->get('message-danger') != "")
                                    <tr>
                                        <td colspan="10">
                                            @if(session()->has('message-success'))
                                            <div class="alert alert-success">
                                                {{ session()->get('message-success') }}
                                            </div>
                                            @elseif(session()->has('message-danger'))
                                            <div class="alert alert-danger">
                                                {{ session()->get('message-danger') }}
                                            </div>
                                            @endif
                                        </td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <th>@lang('lang.guardian_phone') </th>
                                        <th>@lang('lang.father_name') </th>
                                        <th>@lang('lang.father_phone') </th>
                                        <th>@lang('lang.mother_name') </th>
                                        <th>@lang('lang.mother_phone') </th>
                                        <th>@lang('lang.login_permission')</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($parents as $parent)
                                    <tr id="{{@$parent->user_id}}">
                                        <input type="hidden" id="id" value="{{@$parent->user_id}}">
                                        <input type="hidden" id="role" value="{{@$role}}">
                                        <td>{{@$parent->guardians_mobile}}</td>
                                        <td>{{@$parent->fathers_name}}</td>
                                        <td>{{@$parent->fathers_mobile}}</td>
                                        <td>{{@$parent->mothers_name}}</td>
                                        <td>{{@$parent->mothers_mobile}}</td>
                                        <td>
                                            @php
                                                if(@$value->staff_user->access_status == 0){
                                                    $permission_id=422;
                                                }else{
                                                    $permission_id=423;
                                                }
                                            @endphp
                                            @if(userPermission($permission_id))
                                            <label class="switch">
                                              <input type="checkbox" class="switch-input" {{@$parent->parent_user->access_status == 0? '':'checked'}}>
                                              <span class="slider round"></span>
                                            </label>
                                            @endif
                                        </td>
                                    </tr>

                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif


    </div>
</div>
</div>
</div>
</section>



@endsection
