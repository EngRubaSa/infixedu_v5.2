<!DOCTYPE html>
<html>
<head>
    <title>@lang('lang.fees_group') @lang('lang.details')</title>
    <style>
      
        .school-table-style {
            padding: 10px 0px!important;
        }
        .school-table-style tr th {
            font-size: 6px!important;
            text-align: left!important;
        }
        .school-table-style tr td {
            font-size: 7px!important;
            text-align: left!important;
            padding: 10px 0px!important;
        }
        .logo-image {
            width: 10%;
        }
    </style>
    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/vendors/css/bootstrap.css" />
    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/css/style.css" />
</head>
<body>

    <table style="width: 100%;">
        <tr>
             
            <td style="width: 30%"> 
                <img src="{{url($setting->logo)}}" alt="{{url($setting->logo)}}"> 
            </td> 
            <td  style="width: 70%">  
                <h3>{{$setting->school_name}}</h3>
                <h4>{{$setting->address}}</h4>
            </td> 
        </tr> 
    </table>
    <hr>
    
    <table class="school-table school-table-style" style="width: 100%; table-layout: fixed">
        <tr>
            <td>@lang('lang.student_name')</td>
            <td>{{$student->full_name}}</td>
            <td>@lang('lang.roll_number')</td>
            <td>{{$student->roll_no}}</td>
        </tr>
        <tr>
            <td> @lang('lang.father_name')</td>
            <td>{{$student->parents->fathers_name}}</td>
            <td>@lang('lang.class')</td>
            <td>{{$student->className->class_name}}</td>
        </tr>
        <tr>
            <td> @lang('lang.section')</td>
            <td>{{$student->section->section_name}}</td>
            <td>@lang('lang.admission_no')</td>
            <td>{{$student->admission_no}}</td>
        </tr>
    </table>


    <div class="text-center"> 
        <h4 class="text-center mt-1"><span>@lang('lang.fees_details')</span></h4>
    </div>
	<table class="display school-table school-table-style" style="width: 100%; table-layout: fixed">
        <thead>
            <tr align="center">
                <th>@lang('lang.fees_group')</th>
                <th>@lang('lang.fees_code')</th>
                <th>@lang('lang.due_date')</th>
                <th>@lang('lang.status')</th>
                <th>@lang('lang.amount') ({{generalSetting()->currency_symbol}})</th>
                <th>@lang('lang.payment_id')</th>
                <th>@lang('lang.mode')</th>
                <th>@lang('lang.date')</th>
                <th>@lang('lang.discount') ({{generalSetting()->currency_symbol}})</th>
                <th>@lang('lang.fine')({{generalSetting()->currency_symbol}})</th>
                <th>@lang('lang.paid') ({{generalSetting()->currency_symbol}})</th>
                <th>@lang('lang.balance')</th>
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
            @foreach($fees_assigneds as $fees_assigned)
                @php
                       $grand_total += $fees_assigned->feesGroupMaster->amount;
                   
                    
                @endphp

                @php
                    $discount_amount = App\SmFeesAssign::discountSum($fees_assigned->student_id, $fees_assigned->feesGroupMaster->feesTypes->id, 'discount_amount');
                    $total_discount += $discount_amount;
                    $student_id = $fees_assigned->student_id;
                @endphp
                @php
                    $paid = App\SmFeesAssign::discountSum($fees_assigned->student_id, $fees_assigned->feesGroupMaster->feesTypes->id, 'amount');
                    $total_grand_paid += $paid;
                @endphp
                @php
                    $fine = App\SmFeesAssign::discountSum($fees_assigned->student_id, $fees_assigned->feesGroupMaster->feesTypes->id, 'fine');
                    $total_fine += $fine;
                @endphp
                    
                @php
                    $total_paid = $discount_amount + $paid;
                @endphp
            <tr align="center">
                <td>{{$fees_assigned->feesGroupMaster!=""?$fees_assigned->feesGroupMaster->feesGroups->name:""}}</td>
                <td>{{$fees_assigned->feesGroupMaster!=""?$fees_assigned->feesGroupMaster->feesTypes->name:""}}</td>
                <td>
                    @if($fees_assigned->feesGroupMaster!="")
                       
              {{$fees_assigned->feesGroupMaster->date != ""? dateConvert($fees_assigned->feesGroupMaster->date):''}}

                    @endif
                </td>
                <td>
                        @if($fees_assigned->feesGroupMaster->amount == $total_paid)
                        <span class="text-success">@lang('lang.paid')</span>
                        @elseif($total_paid != 0)
                        <span class="text-warning">@lang('lang.partial')</span>
                        @elseif($total_paid == 0)
                        <span class="text-danger">@lang('lang.unpaid')</span>
                        @endif
                    
                </td>
                <td>
                    @php
                           echo $fees_assigned->feesGroupMaster->amount;
                       
                    @endphp
                </td>
                <td></td>
                <td></td>
                <td></td>
                <td> {{$discount_amount}} </td>
                <td>{{$fine}}</td>
                <td>{{$paid}}</td>
                <td>
                    @php 

                           $rest_amount = $fees_assigned->feesGroupMaster->amount - $total_paid;
                       

                        $total_balance +=  $rest_amount;
                        echo $rest_amount;
                    @endphp
                </td>
            </tr>
                @php 
                    $payments = App\SmFeesAssign::feesPayment($fees_assigned->feesGroupMaster->feesTypes->id, $fees_assigned->student_id);
                    $i = 0;
                @endphp

                @foreach($payments as $payment)
                <tr align="center">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="text-right"><img src="{{asset('public/backEnd/img/table-arrow.png')}}"></td>
                    <td>
                        @php
                            $created_by = App\User::find($payment->created_by);
                        @endphp
                        <span>{{$payment->fees_type_id.'/'.$payment->id}}</span>
                    </td>
                    <td>
                    @if($payment->payment_mode == "C")
                            {{'Cash'}}
                    @elseif($payment->payment_mode == "Cq")
                        {{'Cheque'}}
                    @else
                        {{'DD'}}
                    @endif 
                    </td>
                    <td> 
                        {{$payment->payment_date != ""? dateConvert($payment->payment_date):''}}

                    </td>
                    <td>{{$payment->discount_amount}}</td>
                    <td>{{$payment->fine}}</td>
                    <td>{{$payment->amount}}</td>
                    <td></td>
                </tr>
                @endforeach
            @endforeach
            
        </tbody>
        <tfoot>
            <tr align="center">
                <th></th>
                <th></th>
                <th>@lang('lang.grand_total ')({{generalSetting()->currency_symbol}})</th>
                <th></th>
                <th>{{$grand_total}}</th>
                <th></th>
                <th></th>
                <th></th>
                <th>{{$total_discount}}</th>
                <th>{{$total_fine}}</th>
                <th>{{$total_grand_paid}}</th>
                <th>{{$total_balance}}</th>
                <th></th>
            </tr>
        </tfoot>
    </table>
</body>
</html>
