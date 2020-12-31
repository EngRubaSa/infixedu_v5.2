<!DOCTYPE html>
<html lang="en">
<head>
    <title>Tabulation Sheet </title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/vendors/css/print/bootstrap.min.css"/>
    <script type="text/javascript" src="{{asset('public/backEnd/')}}/vendors/js/print/jquery.min.js"></script>
    <script type="text/javascript" src="{{asset('public/backEnd/')}}/vendors/js/print/bootstrap.min.js"></script>
</head>
<style>
    table.tabluationsheet {
        width: 100%;
    }

    .tabluationsheet th, .tabluationsheet td {
        border: 1px solid #ddd;
        font-size: 11px;
        padding: 5px;
    }



    .tabluationsheet td {
        text-align: center;
    }

    body {
        padding: 0;
        font-family: "Poppins", sans-serif; 

        margin-top: 35px;
    }

    html {
        padding: 0px;
        margin: 0px;
        font-family: "Poppins", sans-serif; 


    }

    .container-fluid {
        padding-bottom: 50px;
    }

    h1, h2, h3, h4 {

        font-family: "Poppins", sans-serif;
        font-weight: 400;
        margin-bottom: 15px;
    }

    .gradeChart tbody td{
        padding: 0;
        border-collapse: 1px solid #ddd;
    }
    table.gradeChart{
        padding: 0px;
        margin: 0px;
        width: 60%;
        text-align: right; 
    }
    table.gradeChart thead th{
        border: 1px solid #000000;
        border-collapse: collapse;
        text-align: center !important;
        padding: 0px;
        margin: 0px;
        font-size: 9px;
    }
    table.gradeChart tbody td{
        border: 1px solid #000000;
        border-collapse: collapse;
        text-align: center !important;
        padding: 0px;
        margin: 0px;
        font-size: 9px;
    }
    hr{
        margin: 0px;
    }
    .tabulation th{
        vertical-align: middle;
        text-align: center;
        font-size: 9px;
    }
    .tabulation td{
        font-size: 9px;
        padding: 0px !important;
        text-align: center
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


@endphp

<div class="container-fluid">
    <table class="table" style="width: 100%; table-layout: fixed">
        <thead>
        <tr>

            <th class="" style="vertical-align: middle; ">
                <img class="logo-img" src="{{ url('/')}}/{{generalSetting()->logo }}" alt="">
            </th>
            <th class="text-left">

                <h3 class="exam_title text-left text-capitalize">{{isset(generalSetting()->school_name)?generalSetting()->school_name:'Infix School Management ERP'}} </h3>
                <h4 class="exam_title text-left text-capitalize">{{isset(generalSetting()->address)?generalSetting()->address:'Infix School Adress'}} </h4>
                <h4 class="exam_title text-left text-uppercase"> tabulation sheet
                    of {{$tabulation_details['exam_term']}} in {{date('Y')}}</h4>
            </th>

        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <table>
                    <tr>
                        <td>


                            @if(@$tabulation_details['student_name'])
                                @if(@$tabulation_details['student_name'])
                                    <p class="student_name">
                                        <b>@lang('lang.student') @lang('lang.name') </b> {{$tabulation_details['student_name']}}
                                    </p>
                                @endif
                                @if(@$tabulation_details['student_roll'])
                                    <p class="student_name">
                                        <b>@lang('lang.student') @lang('lang.roll') </b> {{$tabulation_details['student_roll']}}
                                    </p>
                                @endif
                                @if(@$tabulation_details['student_admission_no'])
                                    <p class="student_name">
                                        <b>@lang('lang.student') @lang('lang.admission') </b> {{$tabulation_details['student_admission_no']}}
                                    </p>
                                @endif
                            @else
                                @foreach($tabulation_details['subject_list'] as $d)
                                    <p class="subject-list">{{$d}}</p>
                                @endforeach

                            @endif
                        </td>
                        <td>

                            @if(@$tabulation_details['student_class'])
                                <p class="student_name">
                                    <b>@lang('lang.class')  </b> {{$tabulation_details['student_class']}}
                                </p>
                            @endif
                            @if(@$tabulation_details['student_section'])
                                <p class="student_name">
                                    <b>@lang('lang.section') </b> {{$tabulation_details['student_section']}}
                                </p>
                            @endif
                            @if(@$tabulation_details['student_admission_no'])
                                <p class="student_name">
                                    <b> @lang('lang.exam') </b> {{$tabulation_details['exam_term']}}
                                </p>
                            @endif
                        </td>
                    </tr>
                </table>
            </td>
            <td>
                @if(@$tabulation_details)
                    <table class="table gradeChart table-bordered" id="grade_table">
                        <thead>
                        <tr>
                            <th>Staring</th>
                            <th>Ending</th>
                            <th>GPA</th>
                            <th>Grade</th>
                            <th>Evalution</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($tabulation_details['grade_chart'] as $d)
                            <tr>
                                <td>{{$d['start']}}</td>
                                <td>{{$d['end']}}</td>
                                <td>{{$d['gpa']}}</td>
                                <td>{{$d['grade_name']}}</td>
                                <td class="text-left">{{$d['description']}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endif

            </td>
        </tr>
        </tbody>
    </table>


    <h3 style="width: 100%; text-align: center; border-bottom: 1px solid #ddd; padding: 10px;">Tabulation Sheet</h3>

    <table class="mt-30 mb-20 table table-striped table-bordered tabulation"  style="width: 100%; table-layout: fixed">
        <thead>
        <tr>
            <th rowspan="2">@lang('lang.sl')</th>
            <th rowspan="2">@lang('lang.student') @lang('lang.name')</th>
            <th rowspan="2">Ad.  @lang('lang.no')</th>
            @foreach($subjects as $subject)
                @php
                    $subject_ID     = $subject->subject_id;
                    $subject_Name   = $subject->subject->subject_name;
                    $mark_parts      = App\SmAssignSubject::getNumberOfPart($subject_ID, $class_id, $section_id, $exam_term_id);
                @endphp
                <th colspan="{{count($mark_parts)+2}}" class="subject-list"> {{$subject_Name}}</th>
            @endforeach
            <th rowspan="2">@lang('lang.total_mark')</th>
           
            @if ($optional_subject_setup!='')
            <th >@lang('lang.gpa')</th>
            <th rowspan="2" >@lang('lang.gpa')</th>
            <th rowspan="2">@lang('lang.result')</th>
            @else
            <th >@lang('lang.gpa')</th>
            <th rowspan="2">@lang('lang.result')</th>
            @endif
           
       
        </tr>
        <tr>

            @foreach($subjects as $subject)
                @php
                    $subject_ID     = $subject->subject_id;
                    $subject_Name   = $subject->subject->subject_name;
                    $mark_parts     = App\SmAssignSubject::getNumberOfPart($subject_ID, $class_id, $section_id, $exam_term_id);
                @endphp

                @foreach($mark_parts as $sigle_part)
                    <th>{{$sigle_part->exam_title}}</th>
                @endforeach
                <th>@lang('lang.total')</th>
                <th>@lang('lang.gpa')</th>
            @endforeach

            @if ($optional_subject_setup!='')

            <th> <small>Without Additional</small> </th>
           

            @endif
            
            
        </tr>
        </thead>
        <tbody>
        @php  $count=1;  @endphp
        @foreach($students as $student)
            @php $this_student_failed=0; $tota_grade_point= 0; $tota_grade_point_main= 0; $marks_by_students = 0; @endphp
                @php
                    $optional_subject=App\SmOptionalSubjectAssign::where('student_id','=',$student->id)->where('session_id','=',$student->session_id)->first();
                @endphp
            <tr>
                <td>{{$count++}}</td>
                <td width='10%'> {{$student->full_name}}</td>
                <td> {{$student->admission_no}}</td>

                @foreach($subjects as $subject)
                    @php
                            $subject_ID     = $subject->subject_id;
                            $subject_Name   = $subject->subject->subject_name;
                            $mark_parts     = App\SmAssignSubject::getMarksOfPart($student->id, $subject_ID, $class_id, $section_id, $exam_term_id);
                          
                            $optional_subject_marks=DB::table('sm_optional_subject_assigns')
                            ->join('sm_mark_stores','sm_mark_stores.subject_id','=','sm_optional_subject_assigns.subject_id')
                            ->where('sm_optional_subject_assigns.student_id','=',$student->id)
                            ->first();

                    @endphp
                    
                    @foreach($mark_parts as $sigle_part)
                        <td class="total">{{$sigle_part->total_marks}}</td>
                    @endforeach
                    <td class="total">
                        @php
                            $tola_mark_by_subject = App\SmAssignSubject::getSumMark($student->id, $subject_ID, $class_id, $section_id, $exam_term_id);
                            $marks_by_students  = $marks_by_students + $tola_mark_by_subject;
                        @endphp
                        {{$tola_mark_by_subject }}
                    </td>
                    <td>
                        @php
                            $mark_grade = App\SmMarksGrade::where([['percent_from', '<=', $tola_mark_by_subject], ['percent_upto', '>=', $tola_mark_by_subject]])->first();
                            
                            $mark_grade_gpa=0;
                            $optional_setup_gpa=0;
                            if (@$optional_subject->subject_id==$subject_ID) {
                                $optional_setup_gpa=$optional_subject_setup->gpa_above;
                                if ($mark_grade->gpa >$optional_setup_gpa) {
                                    $mark_grade_gpa = $mark_grade->gpa-$optional_setup_gpa;
                                    $tota_grade_point = $tota_grade_point + $mark_grade_gpa;

                                    $tota_grade_point_main = $tota_grade_point_main + $mark_grade->gpa;
                                   
                                } else {
                                    $tota_grade_point = $tota_grade_point + $mark_grade_gpa;
                                    $tota_grade_point_main = $tota_grade_point_main + $mark_grade->gpa;
                                }
                            } else {
                                $tota_grade_point = $tota_grade_point + $mark_grade->gpa ;
                                if($mark_grade->gpa<1){
                                    $this_student_failed =1;
                                }
                                $tota_grade_point_main = $tota_grade_point_main + $mark_grade->gpa;
                            }



                        @endphp



                            @if (@$optional_subject->subject_id==$subject_ID)
                                
                               
                                    {{-- {{@$mark_grade->gpa-$optional_setup_gpa }} --}}
                                    {{@$mark_grade_gpa }}
                                    <hr>
                                    (GPA above {{ $optional_setup_gpa }})
                                @else
                                    {{@$mark_grade->gpa }}
                                @endif
                    </td>

                @endforeach
                <td>{{$marks_by_students}}
                    @php $marks_by_students = 0; @endphp
                </td>
                
                @if ($optional_subject_setup!='')
                     {{-- with Optional --}}
                
                    {{-- <td>
                        @if(isset($this_student_failed) && $this_student_failed==1)
                            <span class="text-warning font-weight-bold">F</span>
                        @else
                            @php
                            $mark_grade = App\SmMarksGrade::where([['from', '<=', $number], ['up', '>=', $number]])->first();
                            @endphp
                            {{@$mark_grade->grade_name }}
                         @endif


                    </td> --}}

                     {{-- without Optional --}}



                    <td>
                        
                            @if(isset($this_student_failed) && $this_student_failed==1)
                                0.00
                            @else
                            
                            @php
                            $subject_count=0;
                            if (@$optional_subject!='') {
                                $subject_count=count($subjects);
                                    if(!empty($tota_grade_point_main)){
                                        $number = number_format($tota_grade_point_main/ $subject_count, 2, '.', '');
                                    }else{
                                        $number = 0;
                                    }
                            } else{
                                $subject_count=count($subjects);
                                    if(!empty($tota_grade_point_main)){
                                        $number = number_format($tota_grade_point_main/ $subject_count, 2, '.', '');
                                    }else{
                                        $number = 0;
                                    }
                            }
                            // ===========
                              
                            @endphp 
                            {{-- {{ $tota_grade_point_main }}   --}}
                                {{$number==0?'0.00':$number}} 
                                @php 
                                    $tota_grade_point_main= 0; 
                                @endphp
                            @endif
                           
                        </td>
                        <td>
                    
                            @if(isset($this_student_failed) && $this_student_failed==1)
                                0.00
                            @else
                            {{-- {{ dd($optional_subject_marks->total_marks) }} --}}
                            @php
                            $subject_count=0;
                            if (@$optional_subject!='') {
                                $subject_count=count($subjects)-1;
                                    if(!empty($tota_grade_point)){
                                        $number = number_format($tota_grade_point/ $subject_count, 2, '.', '');
                                    }else{
                                        $number = 0;
                                    }
                            } else{
                                $subject_count=count($subjects);
                                    if(!empty($tota_grade_point)){
                                        $number = number_format($tota_grade_point/ $subject_count, 2, '.', '');
                                    }else{
                                        $number = 0;
                                    }
                            }
                            // ===========
                               
                            @endphp    
                           

                                {{$number==0?'0.00':$number}} @php $tota_grade_point= 0; @endphp
                            @endif
                        </td>
                        <td>
                            @if(isset($this_student_failed) && $this_student_failed==1)
                                <span class="text-warning font-weight-bold">F</span>
                            @else
                                @php
                                $mark_grade = App\SmMarksGrade::where([['from', '<=', $number], ['up', '>=', $number]])->first();
                                @endphp
                                {{@$mark_grade->grade_name }}
                             @endif
                        </td>
                @else
                <td>
                    
                        @if(isset($this_student_failed) && $this_student_failed==1)
                            0.00
                        @else
                        {{-- {{ dd($optional_subject_marks->total_marks) }} --}}
                        @php
                        $subject_count=0;
                        if (@$optional_subject!='') {
                            $subject_count=count($subjects)-1;
                                if(!empty($tota_grade_point)){
                                    $number = number_format($tota_grade_point/ $subject_count, 2, '.', '');
                                }else{
                                    $number = 0;
                                }
                        } else{
                            $subject_count=count($subjects);
                                if(!empty($tota_grade_point)){
                                    $number = number_format($tota_grade_point/ $subject_count, 2, '.', '');
                                }else{
                                    $number = 0;
                                }
                        }
                        // ===========
                           
                        @endphp    
                       

                            {{$number==0?'0.00':$number}} @php $tota_grade_point= 0; @endphp
                        @endif
                    </td>
                    <td>
                        @if(isset($this_student_failed) && $this_student_failed==1)
                            <span class="text-warning font-weight-bold">F</span>
                        @else
                            @php
                            $mark_grade = App\SmMarksGrade::where([['from', '<=', $number], ['up', '>=', $number]])->first();
                            @endphp
                            {{@$mark_grade->grade_name }}
                         @endif


                    </td>

                @endif
               
            </tr>
        @endforeach
        </tbody>
    </table>

    <table style="width:100%">
            <tr> 
                <td> 
                    <p style="padding-top:10px; text-align:right; float:right; border-top:1px solid #ddd; display:inline-block; margin-top:50px;">( Exam Controller )</p> 
                </td>
            </tr>

        </table>
       
               
</div>
</body>
</html>