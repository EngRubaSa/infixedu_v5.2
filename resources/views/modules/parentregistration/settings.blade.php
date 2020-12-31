@extends('backEnd.master')
@section('mainContent')
 <style type="text/css">
        #selectStaffsDiv, .forStudentWrapper {
            display: none;
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 55px;
            height: 26px;
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
            height: 24px;
            width: 24px;
            left: 3px;
            bottom: 2px;
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
        .buttons_div_one{
        /* border: 4px solid #FFFFFF; */
        border-radius:12px;

        padding-top: 0px;
        padding-right: 5px;
        padding-bottom: 0px;
        margin-bottom: 4px;
        padding-left: 0px;
         }
        .buttons_div{
        border: 4px solid #19A0FB;
        border-radius:12px
        }
    </style>
<section class="sms-breadcrumb mb-40 up_breadcrumb white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('lang.registration') @lang('lang.settings')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('lang.dashboard')</a>
                <a href="#">@lang('lang.registration')</a>
                <a href="#">@lang('lang.settings')</a>
            </div>
        </div>
    </div>
</section> 
    <section class="admin-visitor-area up_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-12">
                    {{-- @if(Illuminate\Support\Facades\Config::get('app.app_sync'))
                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'url' => '', 'method' => 'GET', 'enctype' => 'multipart/form-data']) }}
                    @else  --}}
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'url' => 'parentregistration/settings', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                  {{-- @endif  --}}
                    <div class="white-box"> 
                            <div class="row p-0">
                                <div class="col-lg-12">
                                    <h3 class="text-center">@lang('lang.registration') @lang('lang.settings')</h3>
                                    <hr>


                                    <div class="row mb-40 mt-40">
                                        
                                        <div class="col-lg-6">
                                            <div class="row">
                                                <div class="col-lg-5 d-flex">
                                                    <p class="text-uppercase fw-500 mb-10">@lang('lang.registration') </p>
                                                </div>
                                                <div class="col-lg-7">
                                                    
                                                        <div class="radio-btn-flex ml-20">
                                                            <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="">
                                                                    <input type="radio" name="registration_permission" id="relationFather" value="1" class="common-radio relationButton" {{@$setting->registration_permission == 1? 'checked': ''}}>
                                                                    <label for="relationFather">@lang('lang.enable')</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="">
                                                                    <input type="radio" name="registration_permission" id="relationMother" value="2" class="common-radio relationButton" {{@$setting->registration_permission == 2? 'checked': ''}}>
                                                                    <label for="relationMother">@lang('lang.disable')</label>
                                                                </div> 
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="row">
                                                <div class="col-lg-5 d-flex">
                                                    <p class="text-uppercase fw-500 mb-10">@lang('lang.registration') @lang('lang.button')</p>
                                                </div>
                                                <div class="col-lg-7">
                                                    
                                                        <div class=" radio-btn-flex ml-20">
                                                            <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="">
                                                                    <input type="radio" name="position" id="positionF" value="1" class="common-radio relationButton"  {{@$setting->position == 1? 'checked': ''}}>
                                                                    <label for="positionF">@lang('lang.header')</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="">
                                                                    <input type="radio" name="position" id="positionM" value="2" class="common-radio relationButton"  {{@$setting->position == 2? 'checked': ''}}>
                                                                    <label for="positionM">@lang('lang.footer')</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>


                                    <div class="row mb-40 mt-40">
                                        <div class="col-lg-6">
                                            <div class="row">
                                                <div class="col-lg-5 d-flex">
                                                    <p class="text-uppercase fw-500 mb-10">@lang('lang.after') @lang('lang.registration') @lang('lang.mail')  @lang('lang.send') </p>
                                                </div>
                                                <div class="col-lg-7">
                                                    
                                                        <div class="radio-btn-flex ml-20">
                                                            <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="">
                                                                    <input type="radio" name="registration_after_mail" id="registration_after_mailF" value="1" class="common-radio relationButton"  {{@$setting->registration_after_mail == 1? 'checked': ''}}>
                                                                    <label for="registration_after_mailF">@lang('lang.yes')</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="">
                                                                    <input type="radio" name="registration_after_mail" id="registration_after_mailM" value="2" class="common-radio relationButton"  {{@$setting->registration_after_mail == 2? 'checked': ''}}>
                                                                    <label for="registration_after_mailM">@lang('lang.no')</label>
                                                                </div> 
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="row">
                                                <div class="col-lg-5 d-flex">
                                                    <p class="text-uppercase fw-500 mb-10">@lang('lang.after') @lang('lang.registration')  @lang('lang.approve') @lang('lang.mail')  @lang('lang.send') </p>
                                                </div>
                                               <div class="col-lg-7">
                                                   
                                                        <div class="radio-btn-flex ml-20">
                                                             <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="">
                                                                    <input type="radio" name="approve_after_mail" id="approve_after_mailF" value="1" class="common-radio relationButton"  {{@$setting->approve_after_mail == 1? 'checked': ''}}>
                                                                    <label for="approve_after_mailF">@lang('lang.yes')</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="">
                                                                    <input type="radio" name="approve_after_mail" id="approve_after_mailM" value="2" class="common-radio relationButton"  {{@$setting->approve_after_mail == 2? 'checked': ''}}>
                                                                    <label for="approve_after_mailM">@lang('lang.no')</label>
                                                                </div> 
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-40 mt-40">
                                        
                                        <div class="col-lg-6">
                                            <div class="row">
                                                <div class="col-lg-5 d-flex">
                                                    <p class="text-uppercase fw-500 mb-10">@lang('lang.recaptcha') </p>
                                                </div>
                                                <div class="col-lg-7">
                                                    
                                                        <div class="radio-btn-flex ml-20">
                                                            <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="">
                                                                    <input type="radio" name="recaptcha" id="recaptchaF" value="1" class="common-radio relationButton" {{@$setting->recaptcha == 1? 'checked': ''}}>
                                                                    <label for="recaptchaF">@lang('lang.enable')</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="">
                                                                    <input type="radio" name="recaptcha" id="recaptchaM" value="2" class="common-radio relationButton" {{@$setting->recaptcha == 2? 'checked': ''}}>
                                                                    <label for="recaptchaM">@lang('lang.disable')</label>
                                                                </div> 
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <a href="https://www.google.com/recaptcha/admin/create" target="_blank">@lang('lang.click_for_recaptcha_create')</a>
                                        </div>

                                    </div>

                                    <div class="row mb-40 mt-40">
                                        
                                        <div class="col-lg-6">
                                            <div class="input-effect sm2_mb_20 md_mb_20">
                                                <input class="primary-input form-control{{ $errors->has('nocaptcha_sitekey') ? ' is-invalid' : '' }}" type="text" name="nocaptcha_sitekey" value="{{@$setting->nocaptcha_sitekey}}">
                                                <label>@lang('lang.nocaptcha_sitekey') <span></span> </label>
                                                <span class="focus-border"></span>
                                                @if ($errors->has('nocaptcha_sitekey'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('nocaptcha_sitekey') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="input-effect sm2_mb_20 md_mb_20">
                                                <input class="primary-input form-control{{ $errors->has('nocaptcha_secret') ? ' is-invalid' : '' }}" type="text" name="nocaptcha_secret" value="{{@$setting->nocaptcha_secret}}">
                                                <label>@lang('lang.nocaptcha_secret')</label>
                                                <span class="focus-border"></span>
                                                @if ($errors->has('nocaptcha_secret'))
                                                <span class="invalid-feedback invalid-select" role="alert">
                                                    <strong>{{ $errors->first('nocaptcha_secret') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    @if(userPermission(548))

                                    <div class="row mt-40">
                                        <div class="col-lg-12 text-center">
                                        <button class="primary-btn fix-gr-bg" id="_submit_btn_admission">
                                                <span class="ti-check"></span>
                                                @lang('lang.save') 
                                            </button>
                                        </div>
                                    </div>

                                    @endif
                                    
                                </div>
                            </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </section>
@endsection
