<!DOCTYPE html>
<html lang="en">
<head>
  <title>@lang('lang.exam_schedule')</title>
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
    $exam=App\SmExamType::find(@$exam_id);
    $class=App\SmClass::find(@$class_id);
    $section=App\SmSection::find(@$section_id);
@endphp
<div class="container-fluid"> 
                    
                    <table  cellspacing="0" width="100%">
                        <tr>
                            <td> 
                                <img class="logo-img" src="{{ url('/')}}/{{@generalSetting()->logo }}" alt=""> 
                            </td>
                            <td> 
                                <h3 style="font-size:22px !important" class="text-white"> {{isset(generalSetting()->school_name)?generalSetting()->school_name:'Infix School Management ERP'}} </h3> 
                                <p style="font-size:18px !important" class="text-white mb-0"> {{isset(generalSetting()->address)?generalSetting()->address:'Infix School Address'}} </p> 
                                <p style="font-size:15px !important" class="text-white mb-0"> @lang('lang.exam_schedule') </p> 
                          </td>
                            <td style="text-aligh:center"> 
                                <p style="font-size:14px !important; border-bottom:1px solid gray;" align="left" class="text-white">@lang('lang.exam') :  {{ @$exam->title}} </p> 
                                <p style="font-size:14px !important; border-bottom:1px solid gray" align="left" class="text-white">@lang('lang.class'): {{ @$class->class_name}} </p> 
                                <p style="font-size:14px !important; border-bottom:1px solid gray" align="left" class="text-white">@lang('lang.section'): {{ @$section->section_name}} </p> 
                                <p style="font-size:14px !important; border-bottom:1px solid gray" align="left" class="text-white">@lang('lang.academic_year'): {{ @$academic_year->title}} ({{ @$academic_year->year}}) </p> 
                               
                          </td>
                        </tr>
                    </table>

                    <hr>
                <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                    
                         
                        <tr>
                            <th width="10%">@lang('lang.subject')</th>
                            @foreach($exam_periods as $exam_period)
                            <th style="text-align:center" nowrap>{{ @$exam_period->period}}<br>{{date('h:i A', strtotime(@$exam_period->start_time))}}<br>To<br>{{ date('h:i A', strtotime(@$exam_period->end_time))}}</th>
                            @endforeach
                        </tr>
                        @foreach($assign_subjects as $assign_subject)
                        <tr>
                            <td >{{ @$assign_subject->subject !=""?@$assign_subject->subject->subject_name:""}}</td>
                            @foreach($exam_periods as $exam_period)
                                @php
                                $assigned_routine = App\SmExamSchedule::assignedRoutine($class_id, $section_id, $exam_id, $assign_subject->subject_id, $exam_period->id);
                                    // $assigned_routine = getScheduleSubject($class_id, $section_id, $exam_id, $exam_period->id, $exam_date);
                                @endphp
                            <td nowrap>
                                @if(@$assigned_routine == "")
                                    
                                @else
                                    <div class="col-lg-6">
                                        <span class="">{{@$assigned_routine->classRoom->room_no}}</span>
                                        <br>
                                        <span class="">
                                            
                                            {{@$assigned_routine->date != ""? dateConvert(@$assigned_routine->date):''}}

                                        </span></br>
                                        
                                @endif
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                        
                </table>
        </div>  
 

</body>
</html>
    
