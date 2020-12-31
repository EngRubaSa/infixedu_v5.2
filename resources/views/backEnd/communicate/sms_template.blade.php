@extends('backEnd.master')
@section('mainContent')

<section class="sms-breadcrumb mb-40 white-box up_breadcrumb">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('lang.sms') @lang('lang.template')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('lang.dashboard')</a>
                <a href="#">@lang('lang.communicate')</a>
                <a href="#">@lang('lang.sms') @lang('lang.template')</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
        @if(isset($certificate))
        <div class="row">
            <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                <a href="{{route('student-certificate')}}" class="primary-btn small fix-gr-bg">
                    <span class="ti-plus pr-2"></span>
                    @lang('lang.add')
                </a>
            </div>
        </div>
        @endif
        <div class="row">
           
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="main-title">
                            <h3 class="mb-30">
                                    @lang('lang.update')
                                @lang('lang.sms') @lang('lang.template')
                            </h3>
                        </div>
                          
                            {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'sms-template-new', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                        
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
                                        <span class="text-primary">[student_name] [student_email] [student_password] [guardian_name] [guardian_email] [guardian_password] [admission_number] [school_name]</span>
                                    </div>
                                    <div class="col-lg-8">

                                        <div class="input-effect">
                                            <label>@lang('lang.student_approve_message') (@lang('lang.sms'))</label>
                                            <textarea class="primary-input form-control{{ $errors->has('student_approve_message_sms') ? ' is-invalid' : '' }}" cols="0" rows="2" name="student_approve_message_sms" maxlength="500">{{isset($template)? $template->student_approve_message_sms: old('student_approve_message_sms')}}</textarea>
                                            <span class="focus-border textarea"></span>

                                            @if($errors->has('student_approve_message_sms'))
                                                <span class="error text-danger"><strong>{{ $errors->first('student_approve_message_sms') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="row">
                                            
                                                <div class="col-lg-12">
                                                    
                                                        <div class=" radio-btn-flex ml-20">
                                                            <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="">
                                                                    <input type="radio" name="student_approve_message_sms_status" id="student_approve_message_sms_statusF" value="1" class="common-radio relationButton"  {{@$template->student_approve_message_sms_status == 1? 'checked': ''}}>
                                                                    <label for="student_approve_message_sms_statusF">@lang('lang.enable')</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="">
                                                                    <input type="radio" name="student_approve_message_sms_status" id="student_approve_message_sms_statusM" value="2" class="common-radio relationButton"  {{@$template->student_approve_message_sms_status == 2? 'checked': ''}}>
                                                                    <label for="student_approve_message_sms_statusM">@lang('lang.disable')</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                </div>

                                <div class="row mt-25">
                                    <div class="col-lg-12 mb-20">

                                        <span class="text-primary">[student_name] [student_email] [student_password] [guardian_name] [guardian_email] [guardian_password] [admission_number] [school_name]</span>

                                    </div>
                                    <div class="col-lg-8">

                                        <div class="input-effect">
                                            <label>@lang('lang.student_registration_message') (@lang('lang.sms')) <span></span></label>
                                            <textarea class="primary-input form-control{{ $errors->has('student_registration_message_sms') ? ' is-invalid' : '' }}" cols="0" rows="2" name="student_registration_message_sms" maxlength="500">{{isset($template)? $template->student_registration_message_sms: old('student_registration_message_sms')}}</textarea>
                                            
                                            <span class="focus-border textarea"></span>

                                            @if($errors->has('student_registration_message_sms'))
                                                <span class="error text-danger"><strong>{{ $errors->first('student_registration_message_sms') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="row">
                                                
                                                <div class="col-lg-12">
                                                    
                                                        <div class=" radio-btn-flex ml-20">
                                                            <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="">
                                                                    <input type="radio" name="student_registration_message_sms_status" id="student_registration_message_sms_statusF" value="1" class="common-radio relationButton"  {{@$template->student_registration_message_sms_status == 1? 'checked': ''}}>
                                                                    <label for="student_registration_message_sms_statusF">@lang('lang.enable')</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="">
                                                                    <input type="radio" name="student_registration_message_sms_status" id="student_registration_message_sms_statusM" value="2" class="common-radio relationButton"  {{@$template->student_registration_message_sms_status == 2? 'checked': ''}}>
                                                                    <label for="student_registration_message_sms_statusM">@lang('lang.disable')</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                </div>

                                <div class="row mt-25">
                                    <div class="col-lg-12 mb-20">

                                        <span class="text-primary">[student_name] [student_username] [student_email] [student_password] [guardian_name] [guardian_email] [guardian_password] [admission_number] [school_name]</span>

                                    </div>
                                    <div class="col-lg-8">

                                        <div class="input-effect">
                                            <label>@lang('lang.student_admission_message') (@lang('lang.sms')) <span></span></label>
                                            <textarea class="primary-input form-control{{ $errors->has('student_admission_message_sms') ? ' is-invalid' : '' }}" cols="0" rows="2" name="student_admission_message_sms" maxlength="500">{{isset($template)? $template->student_admission_message_sms: old('student_admission_message_sms')}}</textarea>
                                            
                                            <span class="focus-border textarea"></span>

                                            @if($errors->has('student_admission_message_sms'))
                                                <span class="error text-danger"><strong>{{ $errors->first('student_admission_message_sms') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="row">
                                                
                                                <div class="col-lg-12">
                                                    
                                                        <div class=" radio-btn-flex ml-20">
                                                            <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="">
                                                                    <input type="radio" name="student_admission_message_sms_status" id="student_admission_message_sms_statusF" value="1" class="common-radio relationButton"  {{@$template->student_admission_message_sms_status == 1? 'checked': ''}}>
                                                                    <label for="student_admission_message_sms_statusF">@lang('lang.enable')</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="">
                                                                    <input type="radio" name="student_admission_message_sms_status" id="student_admission_message_sms_statusM" value="2" class="common-radio relationButton"  {{@$template->student_admission_message_sms_status == 2? 'checked': ''}}>
                                                                    <label for="student_admission_message_sms_statusM">@lang('lang.disable')</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                </div>

                                <div class="row mt-25">
                                    <div class="col-lg-12 mb-20">

                                        <span class="text-primary">[student_name] [parent_name] [admission_number] [exam_term] [subject] [date] [period] [school_name]</span>

                                    </div>
                                    <div class="col-lg-8">

                                        <div class="input-effect">
                                            <label>@lang('lang.exam_schedule_message') (@lang('lang.sms')) <span></span></label>
                                            <textarea class="primary-input form-control{{ $errors->has('exam_schedule_message_sms') ? ' is-invalid' : '' }}" cols="0" rows="2" name="exam_schedule_message_sms" maxlength="500">{{isset($template)? $template->exam_schedule_message_sms: old('admission_pro')}}</textarea>
                                            
                                            <span class="focus-border textarea"></span>

                                            @if($errors->has('exam_schedule_message_sms'))
                                                <span class="error text-danger"><strong>{{ $errors->first('exam_schedule_message_sms') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="row">
                                                
                                                <div class="col-lg-12">
                                                    
                                                        <div class=" radio-btn-flex ml-20">
                                                            <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="">
                                                                    <input type="radio" name="exam_schedule_message_sms_status" id="exam_schedule_message_smsF" value="1" class="common-radio relationButton"  {{@$template->exam_schedule_message_sms_status == 1? 'checked': ''}}>
                                                                    <label for="exam_schedule_message_smsF">@lang('lang.enable')</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="">
                                                                    <input type="radio" name="exam_schedule_message_sms_status" id="exam_schedule_message_smsM" value="2" class="common-radio relationButton"  {{@$template->exam_schedule_message_sms_status == 2? 'checked': ''}}>
                                                                    <label for="exam_schedule_message_smsM">@lang('lang.disable')</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                </div>

                                <div class="row mt-25">
                                    <div class="col-lg-12 mb-20">

                                        <span class="text-primary">[student_name] [parent_name] [due_amount] [fees_name] [due_date] [school_name]</span>

                                    </div>
                                    <div class="col-lg-8">

                                        <div class="input-effect">
                                            <label>@lang('lang.dues_fees_message') (@lang('lang.sms')) <span></span></label>
                                            <textarea class="primary-input form-control{{ $errors->has('dues_fees_message') ? ' is-invalid' : '' }}" cols="0" rows="2" name="dues_fees_message_sms" maxlength="500">{{isset($template)? $template->dues_fees_message_sms: old('dues_fees_message_sms')}}</textarea>
                                           
                                            <span class="focus-border textarea"></span>

                                            @if($errors->has('dues_fees_message_sms'))
                                                <span class="error text-danger"><strong>{{ $errors->first('dues_fees_message_sms') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="row">
                                               
                                                <div class="col-lg-12">
                                                    
                                                        <div class=" radio-btn-flex ml-20">
                                                            <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="">
                                                                    <input type="radio" name="dues_fees_message_sms_status" id="dues_fees_message_smsF" value="1" class="common-radio relationButton"  {{@$template->dues_fees_message_sms_status == 1? 'checked': ''}}>
                                                                    <label for="dues_fees_message_smsF">@lang('lang.enable')</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="">
                                                                    <input type="radio" name="dues_fees_message_sms_status" id="dues_fees_message_smsM" value="2" class="common-radio relationButton"  {{@$template->dues_fees_message_sms_status == 2? 'checked': ''}}>
                                                                    <label for="dues_fees_message_smsM">@lang('lang.disable')</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                </div>


                                

                                {{-- <div class="row mt-25">
                                    <div class="col-lg-12">
                                        <div class="input-effect">
                                            <textarea class="primary-input form-control{{ $errors->has('sms_text') ? ' is-invalid' : '' }}" cols="0" rows="4" name="sms_text" maxlength="500">{{isset($template)? $template->sms_text: old('sms_text')}}</textarea>
                                            <label>@lang('lang.body') (@lang('lang.sms')) <span></span></label>
                                            <span class="focus-border textarea"></span>

                                            @if($errors->has('sms_text'))
                                                <span class="error text-danger"><strong>{{ $errors->first('sms_text') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                </div> --}}

                               
	                           @php 
                                  $tooltip = "";
                                  if(userPermission(50)){
                                        $tooltip = "";
                                    }else{
                                        $tooltip = "You have no permission to add";
                                    }
                                @endphp
                                
                                <div class="row mt-40">
                                    <div class="col-lg-12 text-center">
                                        <button class="primary-btn fix-gr-bg" data-toggle="tooltip" title="{{$tooltip}}">
                                            <span class="ti-check"></span>
                                            
                                                @lang('lang.update')
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
