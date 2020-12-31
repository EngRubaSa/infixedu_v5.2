@extends('backEnd.master')
@section('mainContent')
@php  $setting = App\SmGeneralSettings::find(1); if(!empty($setting->currency_symbol)){ $currency = $setting->currency_symbol; }else{ $currency = '$'; } @endphp

<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('lang.search_fees_payment')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('lang.dashboard')</a>
                <a href="#">@lang('lang.fees_collection')</a>
                <a href="#">@lang('lang.search_fees_payment')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-12">
                @if(session()->has('message-success') != "")
                    @if(session()->has('message-success'))
                    <div class="alert alert-success">
                        {{ session()->get('message-success') }}
                    </div>
                    @endif
                @endif
                <div class="white-box">
                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'fees_payment_search', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_student']) }}
                        <div class="row">
                            <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                            <div class="col-lg-3 mt-30-md">
                                <select class="w-100 bb niceSelect form-control {{ $errors->has('class') ? ' is-invalid' : '' }}" id="select_class" name="class">
                                    <option data-display="@lang('lang.select_class') *" value="">@lang('lang.select_class') *</option>
                                    @foreach(@$classes as $class)
                                    <option value="{{$class->id}}"  {{( old("class") == $class->id ? "selected":"")}}>{{$class->class_name}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('class'))
                                <span class="invalid-feedback invalid-select" role="alert">
                                    <strong>{{ $errors->first('class') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="col-lg-3 mt-30-md" id="select_section_div">
                                <select class="w-100 bb niceSelect form-control{{ $errors->has('current_section') ? ' is-invalid' : '' }}" id="select_section" name="section">
                                    <option data-display="@lang('lang.select_section') *" value="">@lang('lang.select_section')</option>
                                </select>
                                @if ($errors->has('section'))
                                <span class="invalid-feedback invalid-select d-block" role="alert">
                                    <strong>{{ $errors->first('section') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="col-lg-6 mt-30-md">
                                <div class="input-effect">
                                    <input class="primary-input form-control" type="text" name="keyword">
                                    <label>@lang('lang.search_by_name'), @lang('lang.admission') @lang('lang.no'), @lang('lang.roll') @lang('lang.no')</label>
                                    <span class="focus-border"></span>
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
        @if (@$fees_payments)            
        <div class="row mt-40">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-4 no-gutters">
                        <div class="main-title">
                            <h3 class="mb-0"> @lang('lang.payment_ID_Details')</h3>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">

                        <table id="table_id" class="display school-table" cellspacing="0" width="100%">

                            <thead>
                                <tr>
                                    <th>@lang('lang.payment') @lang('lang.id')</th>
                                    <th>@lang('lang.date')</th>
                                    <th>@lang('lang.name')</th>
                                    <th>@lang('lang.class')</th>
                                    <th>@lang('lang.fees_group')</th>
                                    <th>@lang('lang.fees_type')</th>
                                    <th>@lang('lang.mode')</th>
                                    <th>@lang('lang.amount') ({{generalSetting()->currency_symbol}}) </th>
                                    {{-- <th>@lang('lang.discount') ({{generalSetting()->currency_symbol}}) </th>
                                    <th>@lang('lang.fine') ({{generalSetting()->currency_symbol}}) </th> --}}
                                    <th>@lang('lang.action')</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($fees_payments as $fees_payment)
                                <tr>
                                    <td>{{$fees_payment->id.'/'.$fees_payment->fees_type_id}}</td>
                                    <td>
                                        {{ dateConvert($fees_payment->payment_date)}}
                                      
                                    <!-- {{ $fees_payment->payment_date != ""? dateConvert($fees_payment->payment_date):''}} -->

                                    </td>
                                    <td>{{$fees_payment->full_name!=""?$fees_payment->full_name:""}}</td>
                                    <td>
                                        {{$fees_payment->class_name}}
                                    </td>
                                    <td>{{$fees_payment->name !=""?$fees_payment->name: ""}}</td>
                                    <td>{{$fees_payment->fees_type_name!=""?$fees_payment->fees_type_name:""}}</td>
                                    <td>
                                        @if($fees_payment->payment_mode == "C")
                                            {{'Cash'}}
                                        @elseif($fees_payment->payment_mode == "Cq")
                                            {{'Cheque'}}
                                        @else
                                            {{'DD'}}
                                        @endif
                                        
                                    </td>
                                    <td>{{$fees_payment->amount}}</td>
                                    {{-- <td>{{$fees_payment->discount_amount}}</td>
                                    <td>{{$fees_payment->fine}}</td> --}}
                                    <td><div class="dropdown">
                                            <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                                                @lang('lang.select')
                                            </button>

                                            @if(userPermission(115))

                                           

                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="{{route('fees_collect_student_wise', [$fees_payment->student_id])}}">@lang('lang.view')</a>
                                            </div>
                                            @endif
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
@endsection
