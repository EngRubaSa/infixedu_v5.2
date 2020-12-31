@extends('backEnd.master')
@section('mainContent')

<section class="sms-breadcrumb mb-40 white-box up_breadcrumb">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('lang.email') @lang('lang.template')</h1>
            <div class="bc-pages">
                <a href="{{url('dashboard')}}">@lang('lang.dashboard')</a>
                <a href="#">@lang('lang.template') @lang('lang.settings')</a>
                <a href="#">@lang('lang.email') @lang('lang.template')</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
        @if(isset($certificate))
        @if(userPermission(481))
        <div class="row">
            <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                <a href="{{url('student-certificate')}}" class="primary-btn small fix-gr-bg">
                    <span class="ti-plus pr-2"></span>
                    @lang('lang.add')
                </a>
            </div>
        </div>
        @endif
        @endif
        <div class="row">
           
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="main-title">
                            <h3 class="mb-30">@if(isset($certificate))
                                    @lang('lang.edit')
                                @else
                                    @lang('lang.add')
                                @endif
                                @lang('lang.email') @lang('lang.template')
                            </h3>
                        </div>

                        @if(userPermission(481))
                            {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'url' => 'templatesettings/email-template', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                        @endif
                            <div class="white-box">
                            <div class="add-visitor">
                                <div class="row mt-25">
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

                               
                                <div class="row mt-25">
                                    <div class="col-lg-12 mb-20">

                                        <span class="text-primary">[name] [email] [admission_number] [school_name]</span>

                                    </div>
                                    <div class="col-lg-12">
                                        <div class="input-effect">
                                            <textarea class="primary-input form-control{{ $errors->has('password_reset_message') ? ' is-invalid' : '' }}" cols="0" rows="4" name="password_reset_message" maxlength="500">{{isset($template)? $template->password_reset_message: old('password_reset_message')}}</textarea>
                                            <label>@lang('lang.password_reset_message') <span></span></label>
                                            <span class="focus-border textarea"></span>

                                            @if($errors->has('password_reset_message'))
                                                <span class="error text-danger"><strong>{{ $errors->first('password_reset_message') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-25">
                                    <div class="col-lg-12 mb-20">

                                        <span class="text-primary">[student_name] [email] [admission_number] [password] [father_name] [school_name]</span>

                                    </div>
                                    <div class="col-lg-12">
                                        <div class="input-effect">
                                            <textarea class="primary-input form-control{{ $errors->has('student_login_credential_message') ? ' is-invalid' : '' }}" cols="0" rows="4" name="student_login_credential_message" maxlength="500">{{isset($template)? $template->student_login_credential_message: old('student_login_credential_message')}}</textarea>
                                            <label>@lang('lang.student_login_credential_message') <span></span></label>
                                            <span class="focus-border textarea"></span>

                                            @if($errors->has('student_login_credential_message'))
                                                <span class="error text-danger"><strong>{{ $errors->first('student_login_credential_message') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-25">
                                    <div class="col-lg-12 mb-20">
                                        <span class="text-primary">[name]  [father_name] [email] [admission_number] [password] [student_name] [school_name]</span>

                                    </div>
                                    <div class="col-lg-12">
                                        <div class="input-effect">
                                            <textarea class="primary-input form-control{{ $errors->has('guardian_login_credential_message') ? ' is-invalid' : '' }}" cols="0" rows="4" name="guardian_login_credential_message" maxlength="500">{{isset($template)? $template->guardian_login_credential_message: old('guardian_login_credential_message')}}</textarea>
                                            <label>@lang('lang.guardian_login_credential_message') <span></span></label>
                                            <span class="focus-border textarea"></span>

                                            @if($errors->has('guardian_login_credential_message'))
                                                <span class="error text-danger"><strong>{{ $errors->first('guardian_login_credential_message') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                </div> 

                                <div class="row mt-25">
                                    <div class="col-lg-12 mb-20">

                                        <span class="text-primary">[name] [admission_number] [guardian_name] [class] [section] [school_name]</span>

                                    </div>
                                    <div class="col-lg-12">
                                        <div class="input-effect">
                                            <textarea class="primary-input form-control{{ $errors->has('student_registration_message') ? ' is-invalid' : '' }}" cols="0" rows="4" name="student_registration_message" maxlength="500">{{isset($template)? $template->student_registration_message: old('student_registration_message')}}</textarea>
                                            <label>@lang('lang.student_registration_message') <span></span></label>
                                            <span class="focus-border textarea"></span>

                                            @if($errors->has('student_registration_message'))
                                                <span class="error text-danger"><strong>{{ $errors->first('student_registration_message') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                </div> 

                                <div class="row mt-25">
                                    <div class="col-lg-12 mb-20">

                                        <span class="text-primary">[name] [student_name] [school_name]</span>

                                    </div>
                                    <div class="col-lg-12">
                                        <div class="input-effect">
                                            <textarea class="primary-input form-control{{ $errors->has('guardian_registration_message') ? ' is-invalid' : '' }}" cols="0" rows="4" name="guardian_registration_message" maxlength="500">{{isset($template)? $template->guardian_registration_message: old('guardian_registration_message')}}</textarea>
                                            <label>@lang('lang.guardian_registration_message') <span></span></label>
                                            <span class="focus-border textarea"></span>

                                            @if($errors->has('guardian_registration_message'))
                                                <span class="error text-danger"><strong>{{ $errors->first('guardian_registration_message') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                </div> 

                                <div class="row mt-25">
                                    <div class="col-lg-12 mb-20">

                                        <span class="text-primary">[name] [username] [password] [school_name]</span>

                                    </div>
                                    <div class="col-lg-12">
                                        <div class="input-effect">
                                            <textarea class="primary-input form-control{{ $errors->has('staff_login_credential_message') ? ' is-invalid' : '' }}" cols="0" rows="4" name="staff_login_credential_message" maxlength="500">{{isset($template)? $template->staff_login_credential_message: old('staff_login_credential_message')}}</textarea>
                                            <label>@lang('lang.staff_login_credential_message') <span></span></label>
                                            <span class="focus-border textarea"></span>

                                            @if($errors->has('staff_login_credential_message'))
                                                <span class="error text-danger"><strong>{{ $errors->first('staff_login_credential_message') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                </div>   

                               <!--  <div class="row mt-25">
                                    <div class="col-lg-12 mb-20">

                                        <span class="text-primary">[name] [student_name] [father_name] [mother_name] [school_name]</span>

                                    </div>
                                    <div class="col-lg-12">
                                        <div class="input-effect">
                                            <textarea class="primary-input form-control{{ $errors->has('send_email_message') ? ' is-invalid' : '' }}" cols="0" rows="4" name="send_email_message" maxlength="500">{{isset($template)? $template->send_email_message: old('send_email_message')}}</textarea>
                                            <label>@lang('lang.send_email_message') <span></span></label>
                                            <span class="focus-border textarea"></span>

                                            @if($errors->has('send_email_message'))
                                                <span class="error text-danger"><strong>{{ $errors->first('send_email_message') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                </div> -->

                                <div class="row mt-25">
                                    <div class="col-lg-12 mb-20">

                                        <span class="text-primary">[student_name] [parent_name] [due_amount] [fees_name] [due_date] [school_name]</span>

                                    </div>
                                    <div class="col-lg-12">
                                        <div class="input-effect">
                                            <textarea class="primary-input form-control{{ $errors->has('dues_payment_message') ? ' is-invalid' : '' }}" cols="0" rows="4" name="dues_payment_message" maxlength="500">{{isset($template)? $template->dues_payment_message: old('dues_payment_message')}}</textarea>
                                            <label>@lang('lang.dues_payment_message') <span></span></label>
                                            <span class="focus-border textarea"></span>

                                            @if($errors->has('dues_payment_message'))
                                                <span class="error text-danger"><strong>{{ $errors->first('dues_payment_message') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-25">
                                    <div class="col-lg-12">
                                        <div class="input-effect">
                                            <textarea class="primary-input form-control{{ $errors->has('email_footer_text') ? ' is-invalid' : '' }}" cols="0" rows="4" name="email_footer_text" maxlength="500">{{isset($template)? $template->email_footer_text: old('email_footer_text')}}</textarea>
                                            <label>@lang('lang.email_footer_text') <span></span></label>
                                            <span class="focus-border textarea"></span>

                                            @if($errors->has('email_footer_text'))
                                                <span class="error text-danger"><strong>{{ $errors->first('email_footer_text') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                               
	                           @php 
                                  $tooltip = "";
                                  if(userPermission(481)){
                                        $tooltip = "";
                                    }else{
                                        $tooltip = "You have no permission to add";
                                    }
                                @endphp
                                
                                <div class="row mt-40">
                                    <div class="col-lg-12 text-center">
                                        <button class="primary-btn fix-gr-bg" data-toggle="tooltip" title="{{$tooltip}}">
                                            <span class="ti-check"></span>
                                            @if(isset($certificate))
                                                @lang('lang.update')
                                            @else
                                                @lang('lang.save')
                                            @endif
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
           
        </div>
    </div>
</section>
@endsection
