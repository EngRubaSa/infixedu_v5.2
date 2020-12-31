
@extends('backEnd.master')
@section('mainContent')
<style type="text/css">
    .smtp_wrapper{
        display: none;
    }
    .smtp_wrapper_block{
        display: block;
    }
</style>

<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('lang.email_settings') </h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('lang.dashboard')</a>
                <a href="#">@lang('lang.system_settings')</a>
                <a href="#">@lang('lang.email_settings') </a>
            </div>
        </div>
    </div>
</section>

<section class="mb-40 student-details">
    <div class="container-fluid p-0">
        <div class="row">
            <!-- Start Sms Details -->
            <div class="col-lg-12">
                <ul class="nav nav-tabs tab_column" role="tablist">
                   
                    <li class="nav-item">
                        <a class="nav-link active" href="#smtp" role="tab" data-toggle="tab">Smtp @lang('lang.settings')</a>
                    </li> 
                    <li class="nav-item">
                        <a class="nav-link" href="#php" role="tab" data-toggle="tab">Php @lang('lang.settings')</a>
                    </li>

                    </li>  
                </ul>
            

               
                <div class="tab-content">
            

            <!-- Start Exam Tab -->
        <div role="tabpanel" class="tab-pane fade show active" id="smtp">
            @if(userPermission(446))
            {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'method' => 'POST', 'url' => 'update-email-settings-data', 'id' => 'email_settings1', 'enctype' => 'multipart/form-data']) }}
            @endif
                <div class="row">
                <div class="col-lg-12">
                    
                  <div class="white-box">
                      
                         <input type="hidden" name="email_settings_url" id="email_settings_url" value="update-email-settings-data">
                         <input type="hidden" name="url" id="url" value="{{URL::to('/')}}"> 
                         <input type="hidden" name="engine_type" id="engine_type" value="smtp">
                        
                        <div class="row justify-content-center mb-30 mt-40">
                            <div class="col-lg-6">
                                <div class="input-effect">
                                    <input class="primary-input form-control{{ $errors->has('from_name') ? ' is-invalid' : '' }}"
                                    type="text" name="from_name" id="from_name" autocomplete="off" value="{{isset($editData)? $editData->from_name : ''}}">
                                    <label>@lang('lang.from_name')<span>*</span> </label>
                                    <span class="focus-border"></span>
                                    @if ($errors->has('from_name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('from_name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                        
                            <div class="col-lg-6">
                                <div class="input-effect">
                                    <input class="primary-input form-control{{ $errors->has('from_email') ? ' is-invalid' : '' }}"
                                    type="text" name="from_email" id="from_email" autocomplete="off" value="{{isset($editData)? $editData->from_email : ''}}">
                                    <label>@lang('lang.from') @lang('lang.mail')<span>*</span> </label>
                                    <span class="focus-border"></span>
                                     @if ($errors->has('from_email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('from_email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                 
                        <div class="row justify-content-center">
                            <div class="col-lg-6 mb-30">
                                <div class="input-effect">
                                    <input class="primary-input form-control{{ $errors->has('mail_driver') ? ' is-invalid' : '' }}"
                                    type="text" name="mail_driver" id="mail_driver" autocomplete="off" value="{{isset($editData)? $editData->mail_driver : ''}}">
                                    <label>@lang('lang.mail') @lang('lang.driver') <span>*</span> </label>
                                    <span class="focus-border"></span>
                                    <span class="modal_input_validation red_alert"></span>
                                </div>
                            </div>
                            <div class="col-lg-6 mb-30">
                                <div class="input-effect">
                                    <input class="primary-input form-control{{ $errors->has('mail_host') ? ' is-invalid' : '' }}"
                                    type="text" name="mail_host" id="mail_host" autocomplete="off" value="{{isset($editData)? $editData->mail_host : ''}}">
                                    <label>@lang('lang.mail') @lang('lang.host') <span>*</span> </label>
                                    <span class="focus-border"></span>
                                    <span class="modal_input_validation red_alert"></span>
                                </div>
                            </div>
                          </div>
    
                           <div class="row justify-content-center">
                            <div class="col-lg-6 mb-30">
                                <div class="input-effect">
                                    <input class="primary-input form-control{{ $errors->has('mail_port') ? ' is-invalid' : '' }}"
                                    type="text" name="mail_port" id="mail_port" autocomplete="off" value="{{isset($editData)? $editData->mail_port : ''}}">
                                    <label>@lang('lang.mail') @lang('lang.port') <span>*</span> </label>
                                    <span class="focus-border"></span>
                                    <span class="modal_input_validation red_alert"></span>
                                </div>
                            </div>
                            <div class="col-lg-6 mb-30">
                                <div class="input-effect">
                                    <input class="primary-input form-control{{ $errors->has('mail_username') ? ' is-invalid' : '' }}"
                                    type="text" name="mail_username" id="mail_username" autocomplete="off" value="{{isset($editData)? $editData->mail_username : ''}}">
                                    <label>@lang('lang.mail') @lang('lang.username') <span>*</span> </label>
                                    <span class="focus-border"></span>
                                    <span class="modal_input_validation red_alert"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-lg-6 mb-30">
                                <div class="input-effect">
                                    <input class="primary-input form-control{{ $errors->has('mail_password') ? ' is-invalid' : '' }}"
                                    type="password" name="mail_password" id="mail_password" autocomplete="off" value="{{isset($editData)? $editData->mail_password : ''}}">
                                    <label>@lang('lang.mail') @lang('lang.password') <span>*</span> </label>
                                    <span class="focus-border"></span>
                                    <span class="modal_input_validation red_alert"></span>
                                </div>
                            </div>
                            <div class="col-lg-3 mb-30">
                                <div class="input-effect">
                                    <input class="primary-input form-control{{ $errors->has('mail_encryption') ? ' is-invalid' : '' }}"
                                    type="text" name="mail_encryption" id="mail_encryption" autocomplete="off" value="{{isset($editData)? $editData->mail_encryption : ''}}">
                                    <label>@lang('lang.mail') @lang('lang.encryption') <span>*</span> </label>
                                    <span class="focus-border"></span>
                                    <span class="modal_input_validation red_alert"></span>
                                </div>
                            </div>
                            <div class="col-lg-3 mb-30">
                                <div class="input-effect">
                                    <select class="w-100 bb niceSelect form-control {{ $errors->has('active_status') ? ' is-invalid' : '' }}" id="active_status" name="active_status">
                                        <option data-display="@lang('lang.select_status') *" value="">@lang('lang.select_status') *</option>
                                        <option @if($active_mail_driver == "smtp") selected @endif value="1">Enable</option>
                                        <option value="1">Disable</option>
                                    </select>
                                </div>
                            </div>
                          </div>
                        <div class="row mt-30">
                            <div class="col-lg-12 text-center">
                                <button class="primary-btn fix-gr-bg">
                                    <span class="ti-check"></span>
                                    @lang('lang.update')
                                </button>
                            </div>
                        </div>
                    </div>
                        
                </div>
                    
            </div>
     
            {{ Form::close() }}
            </div>
            
       
        <div role="tabpanel" class="tab-pane fade" id="php">
            @if(userPermission(446))
            {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'method' => 'POST', 'url' => 'update-email-settings-data', 'id' => 'email_settings1', 'enctype' => 'multipart/form-data']) }}
            @endif
                <div class="row">
                <div class="col-lg-12">
                    
                  <div class="white-box">
                      
                         <input type="hidden" name="email_settings_url" id="email_settings_url" value="update-email-settings-data">
                         <input type="hidden" name="url" id="url" value="{{URL::to('/')}}"> 
                         <input type="hidden" name="engine_type" id="engine_type" value="php">
                        
                        <div class="row justify-content-center mb-30 mt-40">
                            <div class="col-lg-4">
                                <div class="input-effect">
                                    <input class="primary-input form-control{{ $errors->has('from_name') ? ' is-invalid' : '' }}"
                                    type="text" name="from_name" id="from_name" autocomplete="off" value="{{isset($editData)? $editData->from_name : ''}}">
                                    <label>@lang('lang.from_name')<span>*</span> </label>
                                    <span class="focus-border"></span>
                                    @if ($errors->has('from_name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('from_name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                        
                            <div class="col-lg-4">
                                <div class="input-effect">
                                    <input class="primary-input form-control{{ $errors->has('from_email') ? ' is-invalid' : '' }}"
                                    type="text" name="from_email" id="from_email" autocomplete="off" value="{{isset($editData)? $editData->from_email : ''}}">
                                    <label>@lang('lang.from') @lang('lang.mail')<span>*</span> </label>
                                    <span class="focus-border"></span>
                                     @if ($errors->has('from_email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('from_email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-4 mb-30">
                                <div class="input-effect">
                                    <select class="w-100 bb niceSelect form-control {{ $errors->has('active_status') ? ' is-invalid' : '' }}" id="active_status" name="active_status">
                                        <option data-display="@lang('lang.select_status') *" value="">@lang('lang.select_status') *</option>
                                        <option @if($active_mail_driver == "php") selected @endif value="1">Enable</option>
                                        <option value="1">Disable</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                 
                        <div class="row mt-30">
                            <div class="col-lg-12 text-center">
                                <button class="primary-btn fix-gr-bg">
                                    <span class="ti-check"></span>
                                    @lang('lang.update')
                                </button>
                            </div>
                        </div>
                    </div>
                        
                </div>
                    
            </div>
     
            {{ Form::close() }}
            </div>
            </div>
      
    

        </section>

@endsection

