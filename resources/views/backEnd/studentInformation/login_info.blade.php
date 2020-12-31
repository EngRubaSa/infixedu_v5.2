@extends('backEnd.master')
@section('mainContent')
<input type="text" hidden value="{{ @$clas->class_name }}" id="cls">
<input type="text" hidden value="{{ @$clas->section_name->sectionName->section_name }}" id="sec">
<section class="sms-breadcrumb mb-40 up_breadcrumb white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('lang.student_login_info')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('lang.dashboard')</a>
                <a href="#">@lang('lang.reports')</a>
                <a href="#">@lang('lang.student_login_info')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-8 col-md-6">
                    <div class="main-title">
                        <h3 class="mb-30">@lang('lang.select_criteria') </h3>
                    </div>
                </div>
            </div>
            {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'student_login_search', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
            <div class="row">
                <div class="col-lg-12">
                <div class="white-box">
                    <div class="row">
                                <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                                <div class="col-lg-6 mt-30-md col-md-6">
                                    <select class="niceSelect w-100 bb form-control {{ $errors->has('class') ? ' is-invalid' : '' }}" id="select_class" name="class">
                                        <option data-display="@lang('lang.select_class') *" value="">@lang('lang.select_class') *</option>
                                        @foreach($classes as $class)
                                        <option value="{{$class->id}}"  {{isset($class_id)? ($class->id == $class_id? 'selected':''): ''}}>{{$class->class_name}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('class'))
                                    <span class="invalid-feedback invalid-select" role="alert">
                                        <strong>{{ $errors->first('class') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-lg-6 mt-30-md col-md-6" id="select_section_div">
                                    <select class="niceSelect w-100 bb form-control{{ $errors->has('section') ? ' is-invalid' : '' }}" id="select_section" name="section">
                                        <option data-display="@lang('lang.select_current_section')" value="">@lang('lang.select_current_section')</option>
                                    </select>
                                    @if ($errors->has('section'))
                                    <span class="invalid-feedback invalid-select" role="alert">
                                        <strong>{{ $errors->first('section') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-lg-12 mt-20 text-right">
                                    <button type="submit" class="primary-btn small fix-gr-bg">
                                        <span class="ti-search pr-2"></span>
                                        @lang('lang.search')
                                    </button>
                                </div>
                            </div>
                    </div>
                </div>
            </div>

            {{ Form::close() }}
            @if(isset($students))
            <div class="row mt-40"> 
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-4 no-gutters">
                            <div class="main-title">
                                <h3 class="mb-0">@lang('lang.manage') @lang('lang.login')</h3>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <table id="table_id_s" class="display school-table" cellspacing="0" width="100%">
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
                                        <th>@lang('lang.sl')</th>
                                        <th>@lang('lang.admission') @lang('lang.no')</th>
                                        <th>
                                                @lang('lang.student') <br>
                                            @lang('lang.name')</th>
                                        <th>@lang('lang.email')
                                         & @lang('lang.password')</</th>
                                       
                                        <th>@lang('lang.parent') @lang('lang.email') & <br>@lang('lang.password') </th>
                                        
                                    </tr>
                                </thead>

                                <tbody>
                                    @php
                                        $count=1;
                                    @endphp
                                    @foreach($students as $student)
                                    <tr>
                                        <td>{{$count++}}</td>
                                        <td>{{$student->admission_no}}</td>
                                        <td>{{$student->first_name.' '.$student->last_name}}</td>
                                        <td>{{$student->user !=""?$student->user->email:""}}
                                            {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'url' => 'reset-student-password', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                                            <input type="hidden" name="id" value="{{$student->user_id}}">
                                            <div class="row mt-10">
                                                <div class="col-lg-6">
                                                    <div class="input-effect md_mb_20">
                                                        <input class="primary-input read-only-input"  type="text" name="new_password" required="true" minlength="6">
                                                        <label>@lang('lang.reset') @lang('lang.password')</label>

                                                    </div>
                                                </div>
                                                <div class="col-lg-6">

                                                    @if(userPermission(380))

                                                   
                                                    <button type="submit" class="primary-btn small fix-gr-bg">
                                                       
                                                        @lang('lang.update')
                                                    </button>
                                               @endif
                                                </div>
                                            </div>
                                             {{ Form::close() }}
                                        </td>

                                        <td>{{$student->parents->parent_user->email}}
                                            {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'url' => 'reset-student-password', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                                            <input type="hidden" name="id" value="{{$student->user_id}}">
                                            <div class="row mt-10">
                                                <div class="col-lg-6">
                                                    <div class="input-effect md_mb_20">
                                                        <input class="primary-input read-only-input" type="text" name="new_password" required="true" minlength="6">
                                                        <label>@lang('lang.reset') @lang('lang.password')</label>

                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <button type="submit" class="primary-btn small fix-gr-bg">
                                                        
                                                        @lang('lang.update')
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
    </div>
</section>

@endsection
