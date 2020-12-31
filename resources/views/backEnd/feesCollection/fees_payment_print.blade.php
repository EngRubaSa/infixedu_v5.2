<!DOCTYPE html>
<html>
<head>
    <title>@lang('lang.fees') @lang('lang.payment')</title>
    <style>
    
        .school-table-style {
            padding: 10px 0px!important;
        }
        .school-table-style tr th {
            font-size: 8px!important;
            text-align: left!important;
        }
        .school-table-style tr td {
            font-size: 9px!important;
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
    <table class="school-table school-table-style" cellspacing="0" width="100%">
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
        <h4 class="text-center mt-1"><span>@lang('lang.fees') @lang('lang.payment')</span></h4>
    </div>
	<table class="school-table school-table-style" cellspacing="0" width="100%">
        <thead>
            <tr align="center">
                <th>@lang('lang.date')</th>
                <th>@lang('lang.fees_group')</th>
                <th>@lang('lang.fees_code')</th>
                <th>@lang('lang.mode')</th>
                <th>@lang('lang.amount') ({{generalSetting()->currency_symbol}})</th>
                <th>@lang('lang.discount') ({{generalSetting()->currency_symbol}})</th>
                <th>@lang('lang.fine')({{generalSetting()->currency_symbol}})</th>
            </tr>
        </thead>

        <tbody>
            
            <tr align="center">
                <td>
                   
{{$payment->payment_date != ""? dateConvert($payment->payment_date):''}}

                </td>
                <td>{{$group}}</td>
                <td>{{$payment->feesType->code}}</td>
                <td>
                @if($payment->payment_mode == "C")
                        {{'Cash'}}
                @elseif($payment->payment_mode == "Cq")
                    {{'Cheque'}}
                @else
                    {{'DD'}}
                @endif 
                </td>
                <td>{{$payment->amount}}</td>
                <td>{{$payment->discount_amount}}</td>
                <td>{{$payment->fine}}</td>
                <td></td>
            </tr>
            
        </tbody>
    </table>
</body>
</html>
