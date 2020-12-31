<!DOCTYPE html>
<html lang="en">
<head>
  <title>@lang('lang.student') @lang('lang.attendance')  </title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<style>
 table,th,tr,td{
     font-size: 11px !important;
     padding: 0px !important;
     text-align: center !important; 
 }
 
</style>
<style>
    #attendance.th,#attendance.tr,#attendance.td{
        font-size: 10px !important;
        padding: 0px !important;
        text-align: center !important;
        border:1px solid #ddd;
        vertical-align: middle !important;
    }
    #attendance th{
        background: #ddd;
        text-align: center;
    }
    #attendance{
        border: 1px solid black;
           border-collapse: collapse;
    }
    #attendance tr{
        border: 1px solid black;
           border-collapse: collapse;
    }
    #attendance th{
        border: 1px solid black;
           border-collapse: collapse;
           text-align: center !important;
           font-size: 11px;
    }
    #attendance td{
        border: 1px solid black;
           border-collapse: collapse;
           text-align: center;
           font-size: 10px;
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
    $class=DB::table('sm_classes')->find($class_id);
    $section=DB::table('sm_sections')->find($section_id);
@endphp
<div class="container-fluid"> 
                    
                    <table  cellspacing="0" width="100%" >
                        <tr>
                            <td> 
                                <img class="logo-img" src="{{ url('/')}}/{{generalSetting()->logo }}" alt=""> 
                            </td>
                            <td> 
                                <h3 style="font-size:22px !important" class="text-white"> {{isset(generalSetting()->school_name)?generalSetting()->school_name:'Infix School Management ERP'}} </h3> 
                                <p style="font-size:18px !important" class="text-white mb-0"> {{isset(generalSetting()->address)?generalSetting()->address:'Infix School Address'}} </p> 
                                <p style="font-size:15px !important" class="text-white mb-0">@lang('lang.student') @lang('lang.attendance') </p> 
                          </td>
                            <td style="text-aligh:center"> 
                                <p style="font-size:14px !important; border-bottom:1px solid gray" align="left" class="text-white">Class: {{ $class->class_name}} </p> 
                                <p style="font-size:14px !important; border-bottom:1px solid gray" align="left" class="text-white">Section: {{ $section->section_name}} </p> 
                                <p style="font-size:14px !important; border-bottom:1px solid gray" align="left" class="text-white">Month: {{ date("F", strtotime('00-'.$month.'-01')) }} </p> 
                                <p style="font-size:14px !important; border-bottom:1px solid gray" align="left" class="text-white">Year: {{ $year }} </p>
                               
                          </td>
                        </tr>
                    </table>

                    <hr>
           
                    <table  style="width: 100%; table-layout: fixed" id="attendance">
                        <thead>
                                <tr>
                                    <th width="5%">SL</th>
                                    <th width="6%">@lang('lang.name')</th>
                                    <th width="6%">@lang('lang.admission') @lang('lang.no')</th>
                                    <th width="3%">P</th>
                                    <th width="3%">L</th>
                                    <th width="3%">A</th>
                                    
                                    <th width="3%">F</th>
                                    <th width="3%">H</th>
                                    <th width="5%">%</th>
                                    @for($i = 1;  $i<=$days; $i++)
                                    <th width="3%" class="{{($i<=18)? 'all':'none'}}">
                                        {{$i}} <br>
                                        @php
                                            $date = $year.'-'.$month.'-'.$i;
                                            $day = date("D", strtotime($date));
                                            echo $day;
                                        @endphp
                                    </th>
                                    @endfor
                                </tr>
                            </thead>
                            
                            <tbody>
                                @php 
                                $total_grand_present = 0; 
                                $total_late = 0; 
                                $total_absent = 0; 
                                $total_holiday = 0; 
                                $total_halfday = 0; 
                                $countstudent=1;
                                @endphp
                                @foreach($attendances as $values)
                                @php $total_attendance = 0; @endphp
                                @php $count_absent = 0; @endphp
                                <tr>
                                <td>{{$countstudent++}}</td>
                                    <td>
                                        @php $student = 0; @endphp
                                        @foreach($values as $value)
                                            @php $student++; @endphp
                                            @if($student == 1)
                                                {{@$value->full_name}}
                                            @endif
                                        @endforeach
                                       
                                    </td>
                                    <td>
                                        @php $student = 0; @endphp
                                        @foreach($values as $value)
                                            @php $student++; @endphp
                                            @if($student == 1)
                                                {{@$value->admission_no}}
                                            @endif
                                        @endforeach
                                        
                                    </td>
                                    <td>
                                        @php $p = 0; @endphp
                                        @foreach($values as $value)
                                            @if($value->attendance_type == 'P')
                                                @php $p++; $total_attendance++; $total_grand_present++; @endphp
                                            @endif
                                        @endforeach
                                        {{$p}}
                                    </td>
                                    <td>
                                        @php $l = 0; @endphp
                                        @foreach($values as $value)
                                            @if($value->attendance_type == 'L')
                                                @php $l++; $total_attendance++; $total_late++; @endphp
                                            @endif
                                        @endforeach
                                        {{$l}}
                                    </td>
                                    <td>
                                        @php $a = 0; @endphp
                                        @foreach($values as $value)
                                            @if($value->attendance_type == 'A')
                                                @php $a++; $count_absent++; $total_attendance++; $total_absent++; @endphp
                                            @endif
                                        @endforeach
                                        {{$a}}
                                    </td>
                                    
                                    <td>
                                        @php $f = 0; @endphp
                                        @foreach($values as $value)
                                            @if($value->attendance_type == 'F')
                                                @php $f++; $total_attendance++; $total_halfday++; @endphp
                                            @endif
                                        @endforeach
                                        {{$f}}
                                    </td>
                                    <td>
                                        @php $h = 0; @endphp
                                        @foreach($values as $value)
                                            @if($value->attendance_type == 'H')
                                                @php $h++; $total_attendance++; $total_holiday++; @endphp
                                            @endif
                                        @endforeach
                                        {{$h}}
                                    </td>
                                    <td>  
                                       @php
                                         $total_present = $total_attendance - $count_absent;
                                         if($count_absent == 0){
                                             echo '100%';
                                         }else{
                                             $percentage = $total_present / $total_attendance * 100;
                                             echo number_format((float)$percentage, 2, '.', '').'%';
                                         }
                                       @endphp
        
                                    </td>
                                    @for($i = 1;  $i<=$days; $i++)
                                    @php
                                        $date = $year.'-'.$month.'-'.$i;
                                        $y = 0;
                                    @endphp
                                    <td width="3%" class="{{($i<=18)? 'all':'none'}}">
                                        @foreach($values as $value)
                                            @if(strtotime($value->attendance_date) == strtotime($date))
                                                {{$value->attendance_type}}
                                            @endif
                                        @endforeach
                                    </td>
                                    @endfor
                                </tr>
                                @endforeach
                            </tbody>
                </table>
        </div>  
 

</body>
</html>
    

