@extends('backEnd.master')
@section('mainContent')
    <style type="text/css">
        .panel-title {
            display: inline;
            font-weight: bold;
        }

        .display-table {
            display: table;
        }

        .display-tr {
            display: table-row;
        }

        .display-td {
            display: table-cell;
            vertical-align: middle;
            width: 61%;
        }
    </style>

    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('lang.collect_fees') @lang('lang.online')</h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">@lang('lang.dashboard')</a>
                    <a href="{{route('student-fees')}}">@lang('lang.all_fees')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="main-title">
                        <h3 class="mb-30">@lang('lang.collect_fees') @lang('lang.online') @lang('lang.vai_card')
                            ( @lang('lang.stripe') )</h3>
                    </div>
                </div>
            </div>

            {{ Form::open(['class' => 'form-horizontal require-validation', 'files' => true, 'method' => 'POST','data-cc-on-file' => 'false', 'data-stripe-publishable-key' => $stripe_publisher_key, 'route' => 'collect-fees-stripe-strore', 'id' => 'payment-form', 'name'=> 'payment-form', 'enctype' => 'multipart/form-data']) }}

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
                    <div class="white-box">
                        <div class="">
                            <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                            <input type="hidden" name="real_amount" id="real_amount" value="{{$amount}}">
                            <input type="hidden" name="student_id" value="{{$student_id}}">
                            <input type="hidden" name="fees_type_id" value="{{$fees_type_id}}">


                            <div class="row justify-content-center mb-30">
                                <div class="col-lg-4">
                                    <div class="input-effect required">
                                        <input class="primary-input form-control control-label"
                                               type="text" size='4'>
                                        <label>@lang('lang.name_on_card') <span>*</span> </label>
                                        <span class="focus-border"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-center mb-30">
                                <div class="col-lg-4">
                                    <div class="input-effect required">
                                        <input class="primary-input form-control card-number"
                                               type="text" size='20' autocomplete='off'>
                                        <label>@lang('lang.card_number')<span>*</span> </label>
                                        <span class="focus-border"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="row justify-content-center mb-30">
                                <div class="col-lg-4">
                                    <div class="input-effect cvc required">
                                        <input class="primary-input form-control card-cvc"
                                               type="text" size='4' autocomplete='off'>
                                        <label>@lang('lang.cvc')<span>*</span> </label>
                                        <span class="focus-border"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="row justify-content-center mb-30">
                                <div class="col-lg-4">
                                    <div class="input-effect expiration required">
                                        <input class="primary-input form-control card-expiry-month"
                                               type="text" size='4' autocomplete='off'>
                                        <label>@lang('lang.expiration') @lang('lang.month')<span>*</span> </label>
                                        <span class="focus-border"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="row justify-content-center mb-30">
                                <div class="col-lg-4">
                                    <div class="input-effect expiration required">
                                        <input class="primary-input form-control card-expiry-year"
                                               type="text" size='4' autocomplete='off'>
                                        <label>@lang('lang.expiration') @lang('lang.year')<span>*</span> </label>
                                        <span class="focus-border"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class='form-row row justify-content-center'>
                            <div class='col-md-4 error form-group hide'>
                                <div class='alert-warning alert alert-danger-stripe'>
                                    *** @lang('lang.please_give_the_all_information_properly') ***
                                </div>
                            </div>
                        </div>
                        <div class="row mt-40">
                            <div class="col-lg-12 text-center">
                                <button class="primary-btn fix-gr-bg">
                                    <span class="ti-check"></span>
                                    @lang('lang.pay_with') @lang('lang.stripe')
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{ Form::close() }}
    </section>
@endsection


