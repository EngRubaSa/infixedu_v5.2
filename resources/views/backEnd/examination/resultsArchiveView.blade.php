@extends('backEnd.master')
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('lang.result') @lang('lang.archive') </h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('lang.dashboard')</a>
                <a href="#">@lang('lang.report')</a>
                <a href="{{route('results-archive')}}">@lang('lang.result')  @lang('lang.archive')  </a> 
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="main-title">
                    <h3 class="mb-30">@lang('lang.select') @lang('criteria') </h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                @if(session()->has('message-success') != "")
                    @if(session()->has('message-success'))
                    <div class="alert alert-success">
                        {{ session()->get('message-success') }}
                    </div>
                    @endif
                @endif
                 @if(session()->has('message-danger') != "")
                    @if(session()->has('message-danger'))
                    <div class="alert alert-danger">
                        {{ session()->get('message-danger') }}
                    </div>
                    @endif
                @endif
                <div class="white-box">
                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'marks_register', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_student']) }}
                        <div class="row">
                            <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">

                            <div class="col-lg-3">
                                                    <select class="niceSelect w-100 bb promote_session form-control{{ $errors->has('promote_session') ? ' is-invalid' : '' }}" name="promote_session" id="promote_session">
                                                        <option data-display="@lang('lang.select') @lang('lang.academic_year') *" value="">@lang('lang.select') @lang('lang.academic_year') *</option>
                                                        @foreach(academicYears() as $session)
                                                        @if (@$current_session != $session->id)
                                                          <option value="{{$session->id}}" {{( old("promote_session") == $session->id ? "selected":"")}}>{{$session->year}}</option>
                                                        @endif
                                                        @endforeach
                                                    </select>
                                                    
                                                    <span class="text-danger d-none" role="alert" id="promote_session_error">
                                                        <strong>@lang('lang.the_session_is_required')</strong>
                                                    </span>
                                                </div>

                                              
                                                 <div class="col-lg-3 " id="select_class_div">
                                                    <select class="niceSelect w-100 bb" id="select_class" name="promote_class" id="select_class">
                                                        <option data-display="@lang('lang.select_class')" value="">@lang('lang.select_class')</option>
                                                    </select>
                                                </div>

                                                 <div class="col-lg-3 " id="select_section_div">
                                                    <select class="niceSelect w-100 bb" id="select_section" name="promote_section">
                                                        <option data-display="@lang('lang.select_section')" value="">@lang('lang.select_section')</option>
                                                    </select>
                                                </div>
                                                <div class="col-lg-3 mt-30-md" id="select_student_div">
                                                    <select class="w-100 bb niceSelect form-control{{ $errors->has('student') ? ' is-invalid' : '' }}"
                                                            id="select_student" name="student">
                                                        <option data-display="@lang('lang.select_student')"
                                                                value="">@lang('lang.select_student')</option>
                                                    </select>
                                                    @if ($errors->has('student'))
                                                        <span class="invalid-feedback invalid-select" role="alert">
                                                            <strong>{{ $errors->first('student') }}</strong>
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
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</section> 

@endsection('mainContent')
