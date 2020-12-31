@extends('backEnd.master')
@section('mainContent')
@php  $setting = App\SmGeneralSettings::find(1); if(!empty($setting->currency_symbol)){ $currency = $setting->currency_symbol; }else{ $currency = '$'; } @endphp


<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('lang.fees_collection')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('lang.dashboard')</a>
                <a href="#">@lang('lang.fees_collection')</a>
                <a href="{{route('collect_fees')}}">@lang('lang.collect_fees')</a>
                <a href="{{route('fees_collect_student_wise', [$student->id])}}">@lang('lang.student_wise')</a>
            </div>
        </div>
    </div>
</section>
<section class="student-details mb-40">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-4 no-gutters">
                <div class="main-title">
                    <h3 class="mb-30">@lang('lang.student') @lang('lang.fees')</h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="student-meta-box">

                    <div class="white-box">
                        <div class="row">
                            <div class="col-lg-5 col-md-6">
                                <div class="single-meta mt-20">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="value text-left">
                                                @lang('lang.name')
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="name">
                                                {{@$student->full_name}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="single-meta">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="value text-left">
                                                @lang('lang.father_name')
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="name">
                                                {{@$student->parents != ""? @$student->parents->fathers_name:""}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="single-meta">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="value text-left">
                                                @lang('lang.mobile')
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="name">
                                                {{@$student->mobile}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="single-meta">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="value text-left">
                                                @lang('lang.category')
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="name">
                                                {{@$student->category !=""?@$student->category->category_name:""}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="offset-lg-2 col-lg-5 col-md-6">
                                <div class="single-meta mt-20">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="value text-left">
                                                @lang('lang.class') @lang('lang.section')
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="name">
                                                @php
                                                if(@$student->className !="" && @$student->section !="")
                                                {
                                                 echo $student->className->class_name .'('.$student->section->section_name.')';
                                                }
                                                @endphp
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="single-meta">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="value text-left">
                                                @lang('lang.admission') @lang('lang.no')
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="name">
                                                {{@$student->admission_no}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="single-meta">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="value text-left">
                                                @lang('lang.roll') @lang('lang.no')
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="name">
                                                {{@$student->roll_no}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<input type="hidden" id="url" value="{{URL::to('/')}}">
<input type="hidden" id="student_id" value="{{@$student->id}}">
<section class="">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-4 no-gutters">
                <div class="d-flex justify-content-between">
                    <div class="main-title">
                        <h3 class="mb-30">@lang('lang.add') @lang('lang.fees')</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 table-responsive">
                <table id="default_table" class="display school-table" cellspacing="0" width="100%">
                    <thead>
                        @if(session()->has('message-success') != "" ||
                            session()->get('message-danger') != "")
                        <tr>
                            <td colspan="14">
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
                            <td class="text-right" colspan="14">
                                <a href="" id="fees_groups_invoice_print_button" class="primary-btn medium fix-gr-bg" target="">
                                    <i class="ti-printer pr-2"></i>
                                    @lang('lang.invoice_print')
                                </a>
                            </td>
                            {{-- <td class="text-right" colspan="14">
                                <a href="" id="fees-groups-print-button" class="primary-btn medium fix-gr-bg" target="">
                                    <i class="ti-printer pr-2"></i>
                                    @lang('lang.print')
                                </a>
                            </td> --}}

                        </tr>

                        <tr>
                            <th>#</th>
                            <th>@lang('lang.fees')</th>
                            <th>@lang('lang.due_date')</th>
                            <th>@lang('lang.Status')</th>
                            <th>@lang('lang.amount') ({{generalSetting()->currency_symbol}})</th>
                            <th>@lang('lang.payment_id')</th>
                            <th>@lang('lang.mode')</th>
                            <th>@lang('lang.date')</th>
                            <th>@lang('lang.discount') ({{generalSetting()->currency_symbol}})</th>
                            <th>@lang('lang.fine') ({{generalSetting()->currency_symbol}})</th>
                            <th>@lang('lang.paid') ({{generalSetting()->currency_symbol}})</th>
                            <th>@lang('lang.balance')</th>
                            <th>@lang('lang.action')</th>
                        </tr>
                    </thead>

                    <tbody>
                        @php
                            $grand_total = 0;
                            $total_fine = 0;
                            $total_discount = 0;
                            $total_paid = 0;
                            $total_grand_paid = 0;
                            $total_balance = 0;
                        @endphp
                    @if(@$fees_assigneds)
                        @foreach($fees_assigneds as $fees_assigned)
                        {{-- {{dd($fees_assigned)}} --}}
                            @php
                                $grand_total += @$fees_assigned->feesGroupMaster->amount;

                            @endphp
                            @php
                                $discount_amount = $fees_assigned->applied_discount;
                                $total_discount += $discount_amount;
                                $student_id = $fees_assigned->student_id;
                            @endphp

                            @php
                            //Sum of total paid amount of single fees type
                                $paid = \App\SmFeesAssign::feesPayment($fees_assigned->feesGroupMaster->feesTypes->id,$fees_assigned->student_id)->sum('amount');
                                $total_grand_paid += $paid;
                            @endphp

                            @php
                                //Sum of total fine for single fees type
                                $fine = \App\SmFeesAssign::feesPayment($fees_assigned->feesGroupMaster->feesTypes->id,$fees_assigned->student_id)->sum('fine');
               
                                $total_fine += $fine;
                            @endphp

                            @php
                                $total_paid = $discount_amount + $paid;
                            @endphp
                                <tr>
                                    <td>
                                        <input type="checkbox" id="fees_group.{{$fees_assigned->id}}" class="common-checkbox fees-groups-print" name="fees_group[]" value="{{$fees_assigned->id}}">
                                        <label for="fees_group.{{$fees_assigned->id}}"></label>
                                        <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                                    </td>
                                    <td>{{@$fees_assigned->feesGroupMaster->feesGroups->name}} / {{@$fees_assigned->feesGroupMaster->feesTypes->name}}</td>
                                    <td  data-sort="{{strtotime($fees_assigned->feesGroupMaster->date)}}" >

                                        {{$fees_assigned->feesGroupMaster->date != ""? dateConvert($fees_assigned->feesGroupMaster->date):''}}

                                    </td>
                                    {{-- {{dd(number_format($fees_assigned->feesGroupMaster->amount+$total_fine, 2, '.', ''))}} --}}
                                    @php

                                            $rest_amount = $fees_assigned->feesGroupMaster->amount - $total_paid;
                                            $total_balance +=  $rest_amount;
                                            $balance_amount=number_format($rest_amount+$fine, 2, '.', '');
                                        @endphp
                                    <td>
                                        {{-- {{dd(number_format($paid, 2, '.', ''))}} --}}
                                        @if($balance_amount ==0)
                                            <button class="primary-btn small bg-success text-white border-0">@lang('lang.paid')</button>
                                        @elseif($paid != 0)
                                            <button class="primary-btn small bg-warning text-white border-0">@lang('lang.partial')</button>
                                        @elseif($paid == 0)
                                            <button class="primary-btn small bg-danger text-white border-0">@lang('lang.unpaid')</button>
                                        @endif
                                        
                                    </td>
                                    <td>
                                        @php
                                        echo number_format($fees_assigned->feesGroupMaster->amount+$fine, 2, '.', '');
                                        @endphp
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td> {{ number_format($discount_amount, 2, '.', '') }} </td>
                                    <td>{{ number_format($fine, 2, '.', '') }}</td>
                                    <td>{{ number_format($paid, 2, '.', '') }}</td>
                                    <td>
                                        @php
                                            echo  number_format($rest_amount+$fine, 2, '.', '');
                                        @endphp
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                                                @lang('lang.select')
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">

                                                @if(userPermission(111))

                                                @if($balance_amount != 0)
                                                <a class="dropdown-item modalLink" data-modal-size="modal-lg" title="{{@$fees_assigned->feesGroupMaster->feesGroups->name.': '. $fees_assigned->feesGroupMaster->feesTypes->name}}"  href="{{route('fees-generate-modal', [$rest_amount+$fine, $fees_assigned->student_id, $fees_assigned->feesGroupMaster->fees_type_id,$fees_assigned->fees_master_id])}}" >@lang('lang.add') @lang('lang.fees') </a>
                                                @else
                                                <a class="dropdown-item"  target="_blank">Payment Done</a>
                                                @endif
                                                @endif

                                                @if(userPermission(112))

                                                {{-- <a class="dropdown-item"  href="{{route('fees_group_print', [$fees_assigned->id])}}" target="_blank">Print</a> --}}
                                            @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @php
                                $payments = $fees_assigned->feesPayments->where('fees_type_id',$fees_assigned->feesGroupMaster->feesTypes->id)->where('student_id',$fees_assigned->student_id);

                                $i = 0;
                            @endphp

                            @foreach($payments as $payment)
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-right"><img src="{{asset('public/backEnd/img/table-arrow.png')}}"></td>
                                <td>
                                    @if(isset($payment->created_by))
                                        @php

                                            $created_by = $payment->created_by;
                                        @endphp

                                        @if(@$created_by != "")

                                        <a href="#" data-toggle="tooltip" data-placement="right" title="{{'Collected By: '.$created_by->full_name}}">{{$payment->id.'/'.$payment->fees_type_id}}</a></td>
                                        @endif
                                    @endif
                                <td>
                                        {{$payment->payment_mode}}
                                
                                </td>
                                <td>

                                {{$payment->payment_date != ""? dateConvert($payment->payment_date):''}}

                                </td>
                                <td>{{ number_format($payment->discount_amount, 2, '.', '')}}</td>
                                <td>{{ number_format($payment->fine, 2, '.', '')}}</td>
                                <td>{{ number_format($payment->amount, 2, '.', '')}}</td>
                                <td></td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                                            @lang('lang.select')
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            @if(!empty(@$fees_assigned->feesGroupMaster->feesGroups->name))
                                            {{-- <a class="dropdown-item" href="{{route('fees_payment_print', [$payment->id, $fees_assigned->feesGroupMaster->feesGroups->name])}}"  target="_blank">@lang('lang.print')</a> --}}
                                           @endif

                                           <a class="dropdown-item deleteFeesPayment" data-toggle="modal" href="#" data-id="{{$payment->id}}" data-target="#deleteFeesPayment">@lang('lang.delete')</a>

                                           @if($payment->slip != "")

                                            <a class="dropdown-item" href="{{route('bank-slip-view',getFilePath3(@$payment->slip))}}">
                                             @lang('lang.download') <span class="pl ti-download"></span>
                                     
                                        @endif

                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        @endforeach
                    @endif

{{-- 

                        @foreach($fees_discounts as $fees_discount)



                        @if($fees_discount->feesDiscount->type != "year")
                            <tr>
                                <td></td>
                                <td>@lang('lang.discount')</td>

                                <td>{{$fees_discount->feesDiscount->name}}</td>
                                <td></td>
                                <td>@if(in_array($fees_discount->id, $applied_discount))
                                    @php

                                        $createdBy = App\SmFeesAssign::createdBy($student_id, $fees_discount->id);


                                    @endphp

                                    @if(@$createdBy != "")

                                    @php
                                        $created_by = App\User::find($createdBy->created_by);

                                    @endphp

                                    @if(@$created_by != "")


                                    <a href="#" data-toggle="tooltip" data-placement="right" title="{{'Collected By: '.$created_by->full_name}}">@lang('lang.discount_of') ${{ number_format($fees_discount->feesDiscount->amount, 2, '.', '')}} @lang('lang.applied') : {{$createdBy->id.'/'.$createdBy->created_by}}</a>

                                    @endif
                                    @endif

                                    @else

                                        @lang('lang.discount_of') ${{ number_format($fees_discount->feesDiscount->amount, 2, '.', '')}} @lang('lang.assigned')


                                    @endif
                                </td>
                                <td>{{$fees_discount->name}}</td>

                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>

                        @else
                        @php
                             $academic_years = App\SmAcademicYear::find(\App\YearCheck::getAcademicId());
                            //  dd($academic_years);
                             $start_month = date("m",strtotime($academic_years->starting_date));
                             $end_month = date("m",strtotime($academic_years->ending_date));
                        @endphp
                            @for($i = $start_month; $i <= $end_month; $i++)
                            <tr>
                                <td></td>
                                <td>@lang('lang.discount')</td>

                                <td>{{$fees_discount->feesDiscount->name.' for '. date('F', mktime(0, 0, 0, $i, 10))}}</td>
                                <td></td>
                                <td>



                                    @if(in_array($fees_discount->id, $applied_discount))
                                        @php
                                        $discount_year = App\SmFeesPayment::discountMonth($fees_discount->id, $i);

                                        $createdBy = App\SmFeesAssign::createdBy($student_id, $fees_discount->id);

                                         @endphp

                                        @if($createdBy != "")


                                    @php
                                        $created_by = App\User::find($createdBy->created_by);
                                    @endphp



                                        @if(@$discount_year != "")
                                            @if(@$created_by != "")

                                                <a href="#" data-toggle="tooltip" data-placement="right" title="{{'Collected By: '.$created_by->full_name}}">@lang('lang.discount_of') {{$currency}}{{ number_format($fees_discount->feesDiscount->amount, 2, '.', '')}} @lang('lang.applied') : {{$createdBy->id.'/'.$createdBy->created_by}}</a>
                                            @endif
                                        @else
                                            @lang('lang.discount_of') {{$currency}}{{ number_format($fees_discount->feesDiscount->amount, 2, '.', '')}} @lang('lang.assigned')
                                        @endif


                                        @endif



                                    @else
                                        @lang('lang.discount_of') ${{  number_format($fees_discount->feesDiscount->amount, 2, '.', '') }} @lang('lang.assigned')
                                    @endif

                                </td>
                                <td>{{$fees_discount->name}}</td>

                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            @endfor
                        @endif
                        @endforeach --}}

                    </tbody>
                    <tfoot>
                        <tr>
                            {{-- <th></th> --}}
                            <th></th>
                            <th></th>
                            <th>@lang('lang.grand_total') ({{generalSetting()->currency_symbol}})</th>
                            <th></th>
                            <th>{{ number_format($grand_total+$total_fine, 2, '.', '') }}</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>{{ number_format($total_discount, 2, '.', '') }}</th>
                            <th>{{ number_format($total_fine, 2, '.', '') }}</th>
                            <th>{{ number_format($total_grand_paid, 2, '.', '') }}</th>
                            @php
                                $show_balance=$grand_total+$total_fine-$total_discount;
                            @endphp
                            <th>{{ number_format($show_balance-$total_grand_paid, 2, '.', '') }}</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</section>



<div class="modal fade admin-query" id="deleteFeesPayment" >
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">@lang('lang.delete') @lang('lang.collect_fees')</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <div class="text-center">
                    <h4>@lang('lang.are_you_sure_to_delete')</h4>
                </div>

                <div class="mt-40 d-flex justify-content-between">
                    <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('lang.cancel')</button>
                     {{ Form::open(['route' => 'fees-payment-delete', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                     <input type="hidden" name="id" id="feep_payment_id">
                    <button class="primary-btn fix-gr-bg" type="submit">@lang('lang.delete')</button>
                     {{ Form::close() }}
                </div>
            </div>

        </div>
    </div>
</div>






@endsection
