@extends('backEnd.master')
@section('mainContent')

<section class="sms-breadcrumb mb-40 white-box up_breadcrumb">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('lang.bank')  @lang('lang.payment')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('lang.dashboard')</a>
                <a href="#">@lang('lang.fees_collection')</a>
                <a href="#">@lang('lang.bank')  @lang('lang.payment')</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
        
         <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="main-title mt_0_sm mt_0_md">
                        <h3 class="mb-30">@lang('lang.select_criteria') </h3>
                    </div>
                </div>
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
                    <div class="white-box">
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'bank-payment-slip', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_studentA']) }}
                            <div class="row">
                                <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                                <div class="col-lg-4 col-md-4 sm_mb_20 sm2_mb_20">
                                    <select class="niceSelect w-100 bb form-control{{ $errors->has('class') ? ' is-invalid' : '' }}" id="select_class" name="class">
                                        <option data-display="@lang('lang.select_class') *" value="">@lang('lang.select_class') *</option>
                                        @foreach($classes as $class)
                                        <option value="{{$class->id}}" {{isset($class_id)? ($class_id == $class->id? 'selected': ''):'' }}>{{$class->class_name}}</option>
                                        @endforeach
                                    </select>
                                     @if ($errors->has('class'))
                                    <span class="invalid-feedback invalid-select" role="alert">
                                        <strong>{{ $errors->first('class') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-lg-4 col-md-4" id="select_section_div">
                                    <select class="niceSelect w-100 bb form-control{{ $errors->has('section') ? ' is-invalid' : '' }}" id="select_section" name="section">
                                        <option data-display="@lang('lang.select_section')" value="">@lang('lang.select_section')</option>
                                        @if (isset($section_id))
                                            @foreach($sections as $section)
                                                <option value="{{$section->id}}" {{isset($section_id)? ($section_id == $section->id? 'selected': ''):'' }}>{{$section->section_name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @if ($errors->has('section'))
                                    <span class="invalid-feedback invalid-select" role="alert">
                                        <strong>{{ $errors->first('section') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-lg-4 col-md-4 mt-30-md">
                                    <div class="row no-gutters input-right-icon">
                                        <div class="col">
                                            <div class="input-effect">
                                                <input class="primary-input date form-control{{ $errors->has('payment_date') ? ' is-invalid' : '' }} {{isset($date)? 'read-only-input': ''}}" id="startDate" type="text"
                                                    name="payment_date" autocomplete="off" value="{{isset($date)? $date: ''}}">
                                                <label for="startDate">@lang('lang.payment') @lang('lang.date')</label>
                                                <span class="focus-border"></span>
                                                
                                                @if ($errors->has('payment_date'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('payment_date') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <button class="" type="button">
                                                <i class="ti-calendar" id="start-date-icon"></i>
                                            </button>
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
            @if(isset($bank_slips))
            <div class="row mt-40">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-4 no-gutters">
                            <div class="main-title">
                                <h3 class="mb-0">  @lang('lang.bank')  @lang('lang.payment') @lang('lang.list')</h3>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">

                            <table id="table_id" class="display school-table" cellspacing="0" width="100%">

                                <thead>
                                    @if(session()->has('message-success-delete') != "" ||
                                    session()->get('message-danger-delete') != "")
                                    <tr>
                                        <td colspan="6">
                                            @if(session()->has('message-success-delete'))
                                            <div class="alert alert-success">
                                                {{ session()->get('message-success-delete') }}
                                            </div>
                                            @elseif(session()->has('message-danger-delete'))
                                            <div class="alert alert-danger">
                                                {{ session()->get('message-danger-delete') }}
                                            </div>
                                            @endif
                                        </td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <th>@lang('lang.student') @lang('lang.name')</th>
                                        <th>@lang('lang.fees_type')</th>
                                        <th>@lang('lang.date')</th>
                                        <th>@lang('lang.amount')</th>
                                        <th>@lang('lang.note')</th>
                                        <th>@lang('lang.slip')</th>
                                        <th>@lang('lang.status')</th>
                                        <th>@lang('lang.actions')</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($bank_slips as $bank_slip)
                                    <tr>
                                        <td>{{@$bank_slip->studentInfo->full_name}}</td>
                                        <td>{{@$bank_slip->feesType->name}}</td>
                                        <td  data-sort="{{strtotime(@$bank_slip->date)}}" >{{ !empty($bank_slip->date)? dateConvert(@$bank_slip->date):''}}</td>
                                        <td>{{@$bank_slip->amount}}</td>
                                        <td>{{@$bank_slip->note}}</td>
                                        
                                        <td><a class="text-color" data-toggle="modal" data-target="#showCertificateModal{{ @$bank_slip->id}}"  href="#">@lang('lang.view')</a></td>
                                        <td>{{@$bank_slip->approve_status == 0? 'Pending':'Approved'}}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                                                    @lang('lang.select')
                                                </button>
                                                @if($bank_slip->approve_status == 0)
                                                <div class="dropdown-menu dropdown-menu-right">


                                                    
                                                    <a onclick="enableId({{$bank_slip->id}});" class="dropdown-item" href="#" data-toggle="modal" data-target="#enableStudentModal" data-id="{{$bank_slip->id}}"  >@lang('lang.approve')</a>


                                                    
                                                </div>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>

                                    <div class="modal fade admin-query" id="showCertificateModal{{ @$bank_slip->id}}">
                                        <div class="modal-dialog modal-dialog-centered large-modal">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">@lang('lang.view') @lang('lang.slip')</h4>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>
                                                <div class="modal-body p-0">
                                                    <div class="container student-certificate">
                                                        <div class="row justify-content-center">
                                                            <div class="col-lg-12 text-center">
                                                                <div class="mb-5">
                                                                    <img class="img-fluid" src="{{asset($bank_slip->slip)}}">
                                                                </div>
                                                            </did> 
                                                            </div>
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
            @endif

        {{-- </div> --}}
    </div>
</section>

<div class="modal fade admin-query" id="enableStudentModal" >
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">@lang('lang.approve') @lang('lang.payment')</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <div class="text-center">
                    <h4>@lang('lang.are_you_sure_to_approve')</h4>
                </div>

                <div class="mt-40 d-flex justify-content-between">
                    <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('lang.cancel')</button>
                     {{ Form::open(['route' => 'approve-fees-payment', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                     <input type="hidden" name="id" value="" id="student_enable_i">  {{-- using js in main.js --}}
                    <button class="primary-btn fix-gr-bg" type="submit">@lang('lang.approve')</button>
                     {{ Form::close() }}
                </div>
            </div>

        </div>
    </div>
</div>


@endsection
