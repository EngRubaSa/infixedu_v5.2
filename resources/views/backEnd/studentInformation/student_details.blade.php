
@extends('backEnd.master')
@section('mainContent')
<section class="sms-breadcrumb mb-40 up_breadcrumb white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('lang.manage') @lang('lang.student')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('lang.dashboard')</a>
                <a href="#">@lang('lang.student') @lang('lang.information')</a>
                <a href="#">@lang('lang.student_list')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-8 col-md-6 col-sm-6">
                    <div class="main-title mt_0_sm mt_0_md">
                        <h3 class="mb-30  ">@lang('lang.select_criteria')</h3>
                    </div>
                </div>

                @if(userPermission(62))
                 <div class="col-lg-4 text-md-right text-left col-md-6 mb-30-lg col-sm-6 text_sm_right">
                    <a href="{{route('student_admission')}}" class="primary-btn small fix-gr-bg">
                        <span class="ti-plus pr-2"></span>
                        @lang('lang.add') @lang('lang.student')
                    </a>
                </div>
            @endif
            </div>
            {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'student-list-search', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'infix_form']) }}
            <div class="row">
                <div class="col-lg-12">
                <div class="white-box">
                    <div class="row">
                        <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                        <div class="col-lg-3">
                            <div class="input-effect sm2_mb_20 md_mb_20">
                                <select class="niceSelect w-100 bb form-control{{ $errors->has('academic_year') ? ' is-invalid' : '' }}" name="academic_year" id="academic_year">
                                    <option data-display="@lang('lang.academic_year') *" value="">@lang('lang.academic_year') *</option>
                                    @foreach($sessions as $session)
                                    <option value="{{$session->id}}" {{old('session') == $session->id? 'selected': ''}}>{{$session->year}}[{{$session->title}}]</option>
                                    @endforeach
                                </select>
                                <span class="focus-border"></span>
                                @if ($errors->has('academic_year'))
                                <span class="invalid-feedback invalid-select" role="alert">
                                    <strong>{{ $errors->first('academic_year') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-3 sm_mb_20 sm2_mb_20 md_mb_20" id="class-div">
                            <select class="niceSelect w-100 bb form-control {{ $errors->has('class') ? ' is-invalid' : '' }}" id="classSelectStudent" name="class">
                                <option data-display="@lang('lang.select') @lang('lang.class') *" value="">@lang('lang.select') @lang('lang.class')</option>
                               
                            </select>
                            @if ($errors->has('class'))
                            <span class="invalid-feedback invalid-select" role="alert">
                                <strong>{{ $errors->first('class') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="col-lg-2 col-md-3" id="sectionStudentDiv">
                                <select class="niceSelect w-100 bb form-control{{ $errors->has('section') ? ' is-invalid' : '' }}" id="sectionSelectStudent" name="section">
                                    <option data-display="@lang('lang.select_section')" value="">@lang('lang.select_section')</option>
                                </select>
                                @if ($errors->has('section'))
                                <span class="invalid-feedback invalid-select" role="alert">
                                    <strong>{{ $errors->first('section') }}</strong>
                                </span>
                                @endif
                            </div>
                        <div class="col-lg-2">
                            <div class="input-effect sm_mb_20 sm2_mb_20 md_mb_20">
                                <input class="primary-input" type="text" name="name" value="{{ isset($name)?$name:''}}">
                                <label>@lang('lang.search_by_name')</label>
                                <span class="focus-border"></span>
                            </div>
                        </div>
                       
                        <div class="col-lg-2">
                            <div class="input-effect sm_mb_20 sm2_mb_20 md_mb_20">
                                <input class="primary-input" type="text" name="roll_no" value="{{ isset($roll_no)?$roll_no:''}}">
                                <label>@lang('lang.search_by_roll_no')</label>
                                <span class="focus-border"></span>
                            </div>
                        </div>
                        
                        <div class="col-lg-12 mt-20 text-right">
                            <button type="submit" class="primary-btn small fix-gr-bg" id="btnsubmit">
                                <span class="ti-search pr-2"></span>
                                @lang('lang.search')
                            </button>
                        </div>
                    </div>
                    </div>
                </div>
            </div>

            {{ Form::close() }}
            @if (@$students)
            <div class="row mt-40 full_wide_table">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-4 no-gutters">
                            <div class="main-title">
                                <h3 class="mb-0">@lang('lang.student_list') ({{@$students ? @$students->count() : 0}})</h3>
                            </div>
                        </div>
                    </div>
                    <div class="row  ">
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
                                        <th>@lang('lang.admission')@lang('lang.no')</th>
                                        <th>@lang('lang.roll') @lang('lang.no')</th>
                                        <th>@lang('lang.name')</th>
                                        <th>@lang('lang.class')</th>
                                        <th>@lang('lang.section')</th>
                                        <th>@lang('lang.father_name')</th>
                                        <th>@lang('lang.date_of_birth')</th>
                                        <th>@lang('lang.gender')</th>
                                        <th>@lang('lang.type')</th>
                                        <th>@lang('lang.phone')</th>
                                        <th>@lang('lang.actions')</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach(@$students as $student)
                                    <tr>
                                        <td>{{$student->admission_no}}</td>
                                        <td>{{$student->roll_no}}</td>
                                        <td>{{$student->first_name.' '.$student->last_name}}</td> 
                                        <td>{{!empty($student->className)?$student->className->class_name:''}}</td>
                                        <td>{{!empty($student->section)?$student->section->section_name:''}}</td>

                                        <td>{{!empty($student->parents->fathers_name)?$student->parents->fathers_name:''}}</td>
                                        <td  data-sort="{{strtotime($student->date_of_birth)}}" >
                                           
                                        {{$student->date_of_birth != ""? dateConvert($student->date_of_birth):''}}

                                        </td>
                                        <td>{{$student->gender != ""? $student->gender->base_setup_name :''}}</td>
                                        <td>{{!empty($student->category)? $student->category->category_name:''}}</td>
                                        <td>{{$student->mobile}}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                                                    @lang('lang.select')
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right">


                                                    {{-- <a class="dropdown-item" href="{{url('previous-class-results-view', [$student->admission_no])}}">@lang('lang.result')</a> --}}
                                                    

                                                    <a class="dropdown-item" href="{{route('student_view', [$student->id])}}">@lang('lang.view')</a>

                                                {{-- @if(in_array(66, App\GlobalVariable::GlobarModuleLinks()) || Auth::user()->role_id == 1 )
                                                 <a class="dropdown-item" href="{{route('student_edit', [$student->id])}}">@lang('lang.edit')</a>
                                                @endif
                                                 @if(in_array(67, App\GlobalVariable::GlobarModuleLinks()) || Auth::user()->role_id == 1 )
                                                  <a class="dropdown-item deleteStudentModal" href="#" data-toggle="modal" data-target="#deleteStudentModal" data-id="{{$student->id}}" onclick="deleteId()">@lang('lang.delete')</a> --}}

                                                @if(userPermission(66))
                                                    <a class="dropdown-item" href="{{route('student_edit', [$student->id])}}">@lang('lang.edit')</a>
                                                @endif
                                                 @if(userPermission(67))

                                                 @if(Illuminate\Support\Facades\Config::get('app.app_sync'))
                                                 <span  data-toggle="tooltip" title="Disabled For Demo "> 
                                                    <a  class="dropdown-item" href="#" data-toggle="modal" data-target="#deleteStudentModal" data-id=""  >@lang('lang.disable')</a>
                                               
                                                     </span>
                                                     
                                                 @else
                                                 <a onclick="deleteId({{$student->id}});" class="dropdown-item" href="#" data-toggle="modal" data-target="#deleteStudentModal"
                                                    data-id="{{$student->id}}"  >@lang('lang.disable')</a>
                                               
                                                 @endif
                                                  
                                                    @endif
                                                </div>
                                            </div>
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
</section>

{{-- disable student  --}}
    <div class="modal fade admin-query" id="deleteStudentModal" >
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('lang.disable') @lang('lang.student')</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    
                    <div class="text-center">
                        <h4>@lang('lang.are_you_sure_to_disable')</h4>
                    </div>

                    <div class="mt-40 d-flex justify-content-between">
                        <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('lang.cancel')</button>
                        {{ Form::open(['route' => 'student-delete', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                    <input type="hidden" name="id" value="{{@$student->id}}" id="student_delete_i">  {{-- using js in main.js --}}
                            <button class="primary-btn fix-gr-bg" type="submit">@lang('lang.disable')</button>
                        {{ Form::close() }}
                    </div>

                </div>

            </div>
        </div>
    </div>
    {{-- disable student  --}}

@endsection
