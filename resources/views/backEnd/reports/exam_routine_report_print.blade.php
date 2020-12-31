<!DOCTYPE html>
<html lang="en">
<head>
  <title>@lang('lang.exam_routine') </title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="{{asset('public/backEnd/')}}/vendors/css/print/bootstrap.min.css"/>
  <script type="text/javascript" src="{{asset('public/backEnd/')}}/vendors/js/print/jquery.min.js"></script>
  <script type="text/javascript" src="{{asset('public/backEnd/')}}/vendors/js/print/bootstrap.min.js"></script>
</head>
<style>
 table,th,tr,td{
     font-size: 11px !important;
 }
 
</style>
<body>
 

@php 
    $generalSetting= App\SmGeneralSettings::find(1); 
    if(!empty($generalSetting)){
        $school_name =$generalSetting->school_name;
        $site_title =$generalSetting->site_title;
        $school_code =$generalSetting->school_code;
        $address =$generalSetting->address;
        $phone =$generalSetting->phone; 
    } 
    $exam=App\SmExamType::find($exam_term_id);
@endphp
<div class="container-fluid"> 
                    
                    <table  cellspacing="0" width="100%">
                        <tr>
                            <td> 
                                <img class="logo-img" src="{{ url('/')}}/{{generalSetting()->logo }}" alt=""> 
                            </td>
                            <td> 
                                <h3 style="font-size:22px !important" class="text-white"> {{isset(generalSetting()->school_name)?generalSetting()->school_name:'Infix School Management ERP'}} </h3> 
                                <p style="font-size:18px !important" class="text-white mb-0"> {{isset(generalSetting()->address)?generalSetting()->address:'Infix School Address'}} </p> 
                                <p style="font-size:15px !important" class="text-white mb-0"> @lang('lang.exam_routine') </p> 
                          </td>
                            <td style="text-aligh:center"> 
                                <p style="font-size:14px !important; border-bottom:1px solid gray;" align="left" class="text-white">Exam :  {{ $exam->title}} </p> 
                                <p style="font-size:14px !important; border-bottom:1px solid gray;" align="left" class="text-white">Academic Year :  {{ @$academic_year->year}}  - [ {{ @$academic_year->title}} ]</p> 
                          </td>
                        </tr>
                    </table>

                    <hr>
           
                <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                    
                         
                        <tr>
                            <th width="10%">@lang('lang.date')</th>
                            @foreach($exam_periods as $exam_period)
                            <th>{{$exam_period->period}}<br>{{date('h:i A', strtotime($exam_period->start_time)).'-'.date('h:i A', strtotime($exam_period->end_time))}}</th>
                            @endforeach
                        </tr>
               
                        @foreach($exam_routines as $date => $exam_routine)
                        <tr>
                            <td>{{$date != ""? dateConvert($date):''}}

                        </td>
                            @foreach($exam_periods as $exam_period)
                            @php

                            $assigned_date_wise_exams = App\SmExamSchedule::assigned_date_wise_exams($exam_period->id, $exam_term_id, $date);

                            @endphp
                            <td>

                                @foreach($assigned_date_wise_exams as $assigned_date_wise_exam)
                                <span>
                                    {{$assigned_date_wise_exam->class->class_name}}({{$assigned_date_wise_exam->section->section_name}}) -
                                    {{$assigned_date_wise_exam->subject->subject_name}}
                                    
                                    {{'#'.$assigned_date_wise_exam->classRoom->room_no}}
                                    <br>
                                </span>


                                @endforeach
                                
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                        
                </table>
        </div>  
 

</body>
</html>
    
