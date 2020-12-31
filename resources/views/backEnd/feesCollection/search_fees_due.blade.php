@extends('backEnd.master')
@section('mainContent')
@php  $setting = App\SmGeneralSettings::find(1); if(!empty($setting->currency_symbol)){ $currency = $setting->currency_symbol; }else{ $currency = '$'; } @endphp
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('lang.search_fees_due')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('lang.dashboard')</a>
                <a href="#">@lang('lang.fees_collection')</a>
                <a href="#">@lang('lang.search_fees_due')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area">
    <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="main-title">
                        <h3 class="mb-30">@lang('lang.select_criteria') </h3>
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
                    <div class="white-box">
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'fees_due_search', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_student']) }}
                            <div class="row">
                                <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                                <div class="col-lg-4 mt-30-md">
                                    <select class="w-100 bb niceSelect form-control {{ $errors->has('fees_group') ? ' is-invalid' : '' }}" name="fees_group">
                                        <option data-display="@lang('lang.fees_group')*" value="">@lang('lang.fees_group') *</option>
                                        @foreach($fees_masters as $fees_master)
                                            <option value="" disabled>{{@$fees_master->feesGroups->name}}</option>
                                             @foreach($fees_master->feesTypeIds as $feesTypeId)
                                                <option value="{{$fees_master->fees_group_id.'-'.$feesTypeId->feesTypes->id}}" {{isset($fees_group_id)? ($fees_group_id == $feesTypeId->feesTypes->id? 'selected':''):''}}>{{$feesTypeId->feesTypes->name}}</option>
                                             @endforeach
                                        @endforeach
                                    </select>
                                    @if ($errors->has('fees_group'))
                                    <span class="invalid-feedback invalid-select" role="alert">
                                        <strong>{{ $errors->first('fees_group') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-lg-4 mt-30-md">
                                    <select class="w-100 bb niceSelect form-control {{ $errors->has('class') ? ' is-invalid' : '' }}" id="select_class" name="class">
                                        <option data-display="@lang('lang.select_class') *" value="">@lang('lang.select_class') *</option>
                                        @foreach($classes as $class)
                                        <option value="{{$class->id}}" {{isset($class_id)? ($class_id == $class->id? 'selected':''):''}}>{{$class->class_name}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('class'))
                                    <span class="invalid-feedback invalid-select" role="alert">
                                        <strong>{{ $errors->first('class') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-lg-4 mt-30-md" id="select_section_div">
                                    <select class="w-100 bb niceSelect form-control{{ $errors->has('section') ? ' is-invalid' : '' }}" id="select_section" name="section">
                                        <option data-display="@lang('lang.select_section')" value="">@lang('lang.select_section')</option>
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
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
            
        @if(isset($fees_dues))



             {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'send-dues-fees-email', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_student']) }}

            <div class="row mt-40">
                <div class="col-lg-12">
                    <div class="row">
                    <div class="col-12">
                        <div class="main-title d-flex align-items-center flex-wrap mb_sm_75">
                            <h3 class="mb-0 mb-2 mt-2 mr-2"> @lang('lang.fees_due_list')</h3>
                            <div class="fes_lag_btn d-flex align-items-center">
                                <button name="send_email" type="submit" class="primary-btn small fix-gr-bg mr-2">
                                    <span class="ti-envelope pr-2"></span>
                                    @lang('lang.send') @lang('lang.email')
                                </button>
                                <button name="send_sms" type="submit" class="primary-btn small fix-gr-bg ">
                                    <span class="ti-envelope pr-2"></span>
                                    @lang('lang.send') @lang('lang.sms')
                                </button>
                            </div>
                        </div>
                    </div>
                        <!-- <div class="col-lg-6 no-gutters">
                            <div class="row">
                                <div class="col-md-3">
                                    
                                </div>
                                <div class="col-md-3">
                                    
                                </div>
                                <div class="col-md-3">
                                    
                                </div>
                            </div>
                        </div> -->
                    </div>
                    <div class="row">
                        <div class="col-lg-12 search_hide_md">

                            <table id="table_id" class="display school-table " cellspacing="0" width="100%">

                                <thead>
                                    <tr>
                                        <th> @lang('lang.admission') @lang('lang.no')</th>
                                        <th> @lang('lang.roll')  @lang('lang.no')</th>
                                        <th> @lang('lang.name')</th>
                                        {{-- <th> @lang('lang.date_of_birth')</th> --}}
                                        <th>@lang('lang.due_date')</th>
                                        <th>@lang('lang.amount') ({{generalSetting()->currency_symbol}})</th>
                                        <th>@lang('lang.deposit') ({{generalSetting()->currency_symbol}})</th>
                                        <th>@lang('lang.discount') ({{generalSetting()->currency_symbol}})</th>
                                        <th>@lang('lang.fine') ({{generalSetting()->currency_symbol}})</th>
                                        <th>@lang('lang.balance') ({{generalSetting()->currency_symbol}})</th>
                                        <th>@lang('lang.action')</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @foreach($fees_dues as $fees_due)
                                    <input type="hidden" name="student_list[]" value="{{$fees_due->studentInfo->id}}">
                                    <input type="hidden" name="fees_master" value="{{$fees_due->feesGroupMaster->id}}">


                                    
{{-- @dd($fees_due->applied_discount) --}}
                                    <tr>
                                        <td>{{$fees_due->studentInfo !=""?$fees_due->studentInfo->admission_no:""}}</td>
                                        <td>{{$fees_due->studentInfo !=""?$fees_due->studentInfo->roll_no:""}}</td>
                                        <td>{{$fees_due->studentInfo !=""?$fees_due->studentInfo->full_name:""}}</td>
                                        {{-- <td>
                                            @if($fees_due->studentInfo !="")
                                           {{$fees_due->studentInfo->date_of_birth != ""? dateConvert($fees_due->studentInfo->date_of_birth):''}}
                                            @endif
                                        </td> --}}
                                        <td>
                                            @if($fees_due->feesGroupMaster !="")
                                            
                                                {{$fees_due->feesGroupMaster->date != ""? dateConvert($fees_due->feesGroupMaster->date):''}}

                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                    echo $fees_due->feesGroupMaster->amount;
                                                
                                                
                                            @endphp
                                        </td>
                                        <td>
                                            @php
                                                $amount = App\SmFeesAssign::discountSum($fees_due->student_id, $fees_due->feesGroupMaster->feesTypes->id, 'amount');
                                                echo $amount;
                                            @endphp
                                        </td>
                                        <td>
                                        @php
                                            $discount_amount = $fees_due->applied_discount;
                                            if ($discount_amount>0) {
                                                echo $discount_amount;
                                            } else {
                                               echo 0.00;
                                            }
                                            
                                        @endphp
                                        </td>
                                        <td>
                                        @php
                                            $fine = App\SmFeesAssign::discountSum($fees_due->student_id, $fees_due->feesGroupMaster->feesTypes->id, 'fine');
                                            echo $fine;
                                        @endphp
                                        </td>
                                        <td>
                                            @php
                                                   echo $fees_due->feesGroupMaster->amount - $discount_amount - $amount+$fine;
                                                    $dues_amount = $fees_due->feesGroupMaster->amount - $discount_amount - $amount;
                                               
                                                
                                            @endphp
                                            <input type="hidden" name="dues_amount[{{$fees_due->studentInfo->id}}]" value="{{$dues_amount}}">
                                        </td>
                                        <td><div class="dropdown">
                                                <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                                                    @lang('lang.select')
                                                </button>


                                                @if(userPermission(117))

                                            

                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="{{route('fees_collect_student_wise', [$fees_due->student_id])}}">@lang('lang.view')</a>
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
            {{ Form::close() }}

@endif

    </div>
</section>


@endsection
