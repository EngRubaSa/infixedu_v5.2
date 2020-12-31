<!DOCTYPE html>
<html lang="en">
<head>
  <title>@lang('lang.progress_card_report') </title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="{{asset('public/backEnd/')}}/vendors/css/print/bootstrap.min.css"/>
  <script type="text/javascript" src="{{asset('public/backEnd/')}}/vendors/js/print/jquery.min.js"></script>
  <script type="text/javascript" src="{{asset('public/backEnd/')}}/vendors/js/print/bootstrap.min.js"></script>
</head> 
<style>
    th{
        border: 1px solid #ddd;
        text-align: center;
        padding: 5px !important;
        font-size: 11px;
    }
    td{
        text-align: center;
        padding: 5px !important;
        font-size: 11px;
    }
    td.subject-name{
        text-align: left;
        padding-left: 10px !important;
    }
  

    .studentInfoTable{
        width: 100%;
        padding: 0px !important;
    }

    .studentInfoTable td{
        padding: 0px !important;
        text-align: left;
        padding-left: 15px !important;
    }
    h4{
        text-align: left !important;
    }
</style>
 <script>
        var is_chrome = function () { return Boolean(window.chrome); }
        if(is_chrome) 
        {
           window.print();
           setTimeout(function(){window.close();}, 10000); 
        }
        else
        {
           window.print();
           window.close();
        }
        </script>
    <body onLoad="loadHandler();">

 
@php 
    $generalSetting= App\SmGeneralSettings::find(1);
    if(!empty($generalSetting)){
        $school_name =$generalSetting->school_name;
        $site_title =$generalSetting->site_title;
        $school_code =$generalSetting->school_code;
        $address =$generalSetting->address;
        $phone =$generalSetting->phone; 
    }
    if (is_chrome) {
        window.print();
        setTimeout(function () {
            window.close();
        }, 10000);
    } else {
        window.print();
        window.close();
    }
</script>
<body onLoad="loadHandler();">

<div class="container-fluid">
    <table style="width: 100%; border: 0px;">
        <tr>
            <td style="width: 30%">
                <img class="logo-img" src="{{ url('/')}}/{{generalSetting()->logo }}" alt="">
            </td>
            <td style="text-align: left; width: 70%">
                <h3 class="text-white"> {{isset(generalSetting()->school_name)?generalSetting()->school_name:'Infix School Management ERP'}} </h3>
                <p class="text-white mb-0"> {{isset(generalSetting()->address)?generalSetting()->address:'Infix School Address'}} </p>
            </td>
        </tr>
    </table>
</div>


    $section = App\SmSection::find($section_id);
    $class = App\SmClass::find($class_id);


@endphp

<div class="container-fluid"> 
    <table  style="width: 100%; border: 0px;"> 
            <tr > 
                <td style="width: 30%">
                    <img class="logo-img" src="{{ url('/')}}/{{$generalSetting->logo }}" alt=""> 
                </td>
                <td style="text-align: left; width: 70%"> 
                    <h3 class="text-white"> {{isset($school_name)?$school_name:'Infix School Management ERP'}} </h3> 
                    <p class="text-white mb-0"> {{isset($address)?$address:'Infix School Address'}} </p>
                </td> 
            </tr> 
    </table>



 
    <div class="container-fluid p-0"> 
        <div class="row">
            <div class="col-lg-12"> 
                <div class="row justify-content-center">
                    <div class="col-lg-12">
                        <div class="single-report-admit">
                            <div class="card"> 
                               
                                <div class="card-body">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="offset-2 col-md-8">

                                                <table class="table">
                                                    <tr>
                                                        <td>
                                                            <p class="text-center">@lang('lang.student_information')</p> 
                                                            <hr>
                                                            <table class="studentInfoTable">
                                                                <tr>
                                                                    <td class="font-weight-bold">
                                                                        @lang('lang.student_name') :
                                                                    </td>
                                                                    <td>
                                                                        {{$student_detail->full_name}}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="font-weight-bold">
                                                                        @lang('lang.roll_number'):
                                                                    </td>
                                                                    <td>
                                                                        {{$student_detail->roll_no}}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="font-weight-bold">
                                                                        @lang('lang.admission_no') :
                                                                    </td>
                                                                    <td>
                                                                        {{$student_detail->admission_no}}
                                                                    </td>
                                                                </tr>


                                                            </table>
                                                        </td>
                                                        <td style="padding-left: 30px">
                                                            <p class="text-center">@lang('lang.exam') @lang('lang.info')</p>
                                                            <hr>
                                                            <table class="studentInfoTable">
                                                                
                                                                <tr>
                                                                    <td class="font-weight-bold">
                                                                        @lang('lang.academic') @lang('lang.class') :
                                                                    </td>
                                                                    <td>
                                                                        {{@$class->class_name}}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="font-weight-bold">
                                                                        @lang('lang.academic') @lang('lang.section') :
                                                                    </td>
                                                                    <td>
                                                                        {{@$section->section_name}}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="font-weight-bold">
                                                                        @lang('lang.date_of_birth') :
                                                                    </td>
                                                                    <td>
                                                                        {{$student_detail->date_of_birth}}
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                        <td>
                                                            @php
                                                                $marks_grade=DB::table('sm_marks_grades')->get();
                                                            @endphp
                                                                @if(@$marks_grade)
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
                                            
                                                                    @foreach($marks_grade as $d)
                                                                        <tr>
                                                                            <td>{{$d->percent_from}}</td>
                                                                            <td>{{$d->percent_upto}}</td>
                                                                            <td>{{$d->gpa}}</td>
                                                                            <td>{{$d->grade_name}}</td>
                                                                            <td class="text-left">{{$d->description}}</td>
                                                                        </tr>
                                                                    @endforeach
                                                                    </tbody>
                                                                </table>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                </table>
                                        </div>
                                    </div>
                                    <h4 style="text-align: center;">@lang('lang.progress_card_report')</h4>
                                    <hr>

                                    <table class="w-100 mt-40 mb-20 table   table-bordered marksheet">
                                        <thead>
                                                    <tr style="text-align: center;">
                                                        <th rowspan="2">@lang('lang.subjects')</th>
                                                        @foreach($assinged_exam_types as $assinged_exam_type)
                                                        @php
                                                            $exam_type = App\SmExamType::examType($assinged_exam_type);
                                                        @endphp
                                                            <th colspan="2" style="text-align: center;">{{$exam_type->title}}</th>
                                                        @endforeach
                                                        <th rowspan="2">@lang('lang.total')</th>
                                                        {{-- <th rowspan="2">@lang('lang.grade')</th> --}}
                                                        <th rowspan="2">@lang('lang.gpa')</th>

                                                    </tr>
                                                <tr  style="text-align: center;">
                                                    @foreach($assinged_exam_types as $assinged_exam_type)
                                                       
                                                        <th>@lang('lang.marks')</th>
                                                        <th>@lang('lang.grade')</th>

                                                    @endforeach
                                                </tr>
                                                </thead>
                                                <tbody>

                                                    @php
                                                        $total_fail = 0;
                                                        $total_marks = 0;
                                                        $gpa_with_optional_count=0;
                                                        $gpa_without_optional_count=0;
                                                    @endphp
                                                    {{-- {{ dd($student_optional_subject) }} --}}
                                                    @foreach($subjects as $data)
                                                    <tr style="text-align: center">
                                                        @if ($optional_subject_setup!='' && $student_optional_subject!='')
                                                                @if ($student_optional_subject->subject_id==$data->subject->id)
                                                                <td>
                                                                    {{$data->subject !=""?$data->subject->subject_name:""}} (Optional) 
                                                                </td>
                                                            @else
                                                                <td>{{$data->subject !=""?$data->subject->subject_name:""}} </td>
                                                            @endif
                                                        @else
                                                        <td>{{$data->subject !=""?$data->subject->subject_name:""}} </td>
                                                        @endif
                                                        <?php
                                                            $totalSumSub= 0;
                                                            $totalSubjectFail= 0;
                                                            $TotalSum= 0;
                                                        foreach($assinged_exam_types as $assinged_exam_type){

                                                            $mark_parts     =   App\SmAssignSubject::getNumberOfPart($data->subject_id, $class_id, $section_id, $assinged_exam_type);

                                                            $result         =   App\SmResultStore::GetResultBySubjectId($class_id, $section_id, $data->subject_id,$assinged_exam_type ,$student_id);
                                                            if(!empty($result)){
                                                                $final_results = App\SmResultStore::GetFinalResultBySubjectId($class_id, $section_id, $data->subject_id,$assinged_exam_type ,$student_id);

                                                            }

                                                            if($result->count()>0){
                                                                ?>
                                                                    <td>
                                                                    @php

                                                                        if($final_results != ""){
                                                                            echo $final_results->total_marks;
                                                                            $totalSumSub = $totalSumSub + $final_results->total_marks;
                                                                            $total_marks = $total_marks + $final_results->total_marks;

                                                                        }else{
                                                                            echo 0;
                                                                        }

                                                                    @endphp
                                                                </td>
                                                                    <td>
                                                                        @php

                                                                            if($final_results != ""){
                                                                                if($final_results->total_gpa_grade == "F"){
                                                                                    $totalSubjectFail++;
                                                                                    $total_fail++;
                                                                                }
                                                                                echo $final_results->total_gpa_grade;
                                                                            }else{
                                                                                echo '-';
                                                                            }
                                                                            if ($student_optional_subject!='') {
                                                                                    if ($student_optional_subject->subject_id==$data->subject->id) {
                                                                                        $optional_subject_mark=$final_results->total_marks;
                                                                                        // echo $final_results->total_marks;
                                                                                    }
                                                                            }

                                                                        @endphp
                                                                    </td>
                                                        <?php
                                                                }else{ ?>
                                                                    <td>0</td>
                                                                    <td>0</td>
                                                                <?php

                                                                }
                                                                    }
                                                                ?>

                                                                <td>{{$totalSumSub}}</td>
                                                                {{-- <td> --}}
                                                                    @php
                                                                        if($totalSubjectFail > 0){
                                                                          
                                                                        }else{
                                                                            $totalSumSub = $totalSumSub / count($assinged_exam_types);

                                                                            $mark_grade = App\SmMarksGrade::where([['percent_from', '<=', floor($totalSumSub)], ['percent_upto', '>=', floor($totalSumSub)]])->first();


                                                                            
                                                                        }
                                                                    @endphp
                                                                {{-- </td> --}}
                                                                {{-- <td>
                                                                    @php
                                                                        if($totalSubjectFail > 0){
                                                                            echo 'F';
                                                                        }else{
                                                                            $totalSumSub = $totalSumSub / count($assinged_exam_types);

                                                                            $mark_grade = App\SmMarksGrade::where([['percent_from', '<=', floor($totalSumSub)], ['percent_upto', '>=', floor($totalSumSub)]])->first();


                                                                            echo @$mark_grade->grade_name;
                                                                        }
                                                                    @endphp
                                                                </td> --}}

                                                                <td>
                                                                    @php
                                                                        if($totalSubjectFail > 0){
                                                                            echo 'F';
                                                                        }else{
                                                                            if ($student_optional_subject!='') {
                                                                                if (@$student_optional_subject->subject_id==$data->subject->id) {
                                                                                // dd($data);
                                                                                $mark_grade = App\SmMarksGrade::where([['percent_from', '<=', floor($totalSumSub)], ['percent_upto', '>=', floor($totalSumSub)]])->where('academic_id', getAcademicId())->first();
                                                                                if ($mark_grade->gpa > $optional_subject_setup->gpa_above) {
                                                                                   echo "GPA Above ".$optional_subject_setup->gpa_above;
                                                                                   echo "<hr>";
                                                                                   echo $mark_grade->gpa - $optional_subject_setup->gpa_above;
                                                                                   $optional_show_gpa=$mark_grade->gpa - $optional_subject_setup->gpa_above;
                                                                                   $gpa_with_optional_count+=$optional_show_gpa;
                                                                                   $gpa_without_optional_count+=$mark_grade->gpa;

                                                                                } else {
                                                                                   echo "0";
                                                                                }
                                                                            } else {
                                                                                $mark_grade = App\SmMarksGrade::where([['percent_from', '<=', floor($totalSumSub)], ['percent_upto', '>=', floor($totalSumSub)]])->where('academic_id', getAcademicId())->first();
                                                                                echo @$mark_grade->gpa;
                                                                                @$gpa_with_optional_count+=$mark_grade->gpa;
                                                                                @$gpa_without_optional_count+=$mark_grade->gpa;
                                                                            }
                                                                            }else{
                                                                                $mark_grade = App\SmMarksGrade::where([['percent_from', '<=', floor($totalSumSub)], ['percent_upto', '>=', floor($totalSumSub)]])->where('academic_id', getAcademicId())->first();
                                                                                echo @$mark_grade->gpa;
                                                                               @$gpa_with_optional_count+=$mark_grade->gpa;
                                                                                @$gpa_without_optional_count+=$mark_grade->gpa;
                                                                            }
                                                                        }
                                                                    @endphp
                                                                </td>
                                                                
                                                    </tr>
                                                    @endforeach
                                                    @php
                                                        $colspan = 4 + count($assinged_exam_types) * 2;
                                                        if ($optional_subject_setup!='') {
                                                           $col_for_result=3;
                                                        } else {
                                                            $col_for_result=2;
                                                        }
                                                    @endphp
                                                    <tr>
                                                        <td colspan="{{$colspan / 2 - 1}}" class="text-center">@lang('lang.total') @lang('lang.marks')</td>
                                                        <td colspan="{{$colspan / 2 + 1}}" class="text-center">{{$total_marks}}</td>
                                                    </tr>
                                                    @php
                                                    if (isset($optional_subject_mark)) {
                                                        $total_marks_without_optional=$total_marks-$optional_subject_mark;
                                                        $op_subject_count=count($subjects)-1;
                                                    }else{
                                                        $total_marks_without_optional=$total_marks;
                                                        $op_subject_count=count($subjects);
                                                    }
                                                    @endphp
                                                     <tr>
                                                        <td colspan="{{$colspan / $col_for_result - 1}}" class="text-center">@lang('lang.total') @lang('lang.grade')</td>
                                                        @if ($optional_subject_setup!='')
                                                            {{-- @if ($student_optional_subject!='') --}}
                                                             <td colspan="{{$colspan / $col_for_result + 1}}" class="text-center">
                                                                @php
                                                                    if($total_fail != 0){

                                                                        echo 'F';
                                                                    }else{
                                                                        $average_mark = $gpa_with_optional_count / $op_subject_count;
                                                                        $average_grade = App\SmMarksGrade::where([['from', '<=', floor($average_mark)], ['up', '>=', floor($average_mark)]])->where('academic_id', getAcademicId())->first();
                                                                        echo @$average_grade->grade_name;
                                                                    }
                                                                @endphp

                                                            </td>
                                                            {{-- @endif --}}
                                                            
                                                        @endif
                                                        <td colspan="{{$colspan / $col_for_result + 1}}" class="text-center">
                                                            @php
                                                                if($total_fail != 0){

                                                                    echo 'F';
                                                                }else{
                                                                    $total_exam_subject = count($subjects) ;
                                                                    $average_mark = $gpa_without_optional_count / $total_exam_subject;
                                                                    $average_grade = App\SmMarksGrade::where([['from', '<=', floor($average_mark)], ['up', '>=', floor($average_mark)]])->where('academic_id', getAcademicId())->first();
                                                                    echo @$average_grade->grade_name;
                                                                }
                                                            @endphp

                                                        </td>

                                                    </tr>
                                                    <tr>
                                                        <td colspan="{{$colspan / $col_for_result - 1}}" class="text-center">@lang('lang.total') @lang('lang.gpa')</td>
                                                        
                                                        @if ($optional_subject_setup!='')
                                                                <td colspan="{{$colspan / $col_for_result + 1}}" class="text-center">
                                                                    @php
                                                                        if($total_fail != 0){
                                                                            echo '0.00';
                                                                        }else{
                                                                            $average_mark = $gpa_with_optional_count / $op_subject_count;
                                                                            echo round($average_mark,2);
                                                                            $average_grade = App\SmMarksGrade::where([['from', '<=', floor($average_mark)], ['up', '>=', floor($average_mark)]])->where('academic_id', getAcademicId())->first();
                                                                        }
                                                                    @endphp

                                                                </td>
                                                        @endif
                                                        <td colspan="{{$colspan / $col_for_result + 1}}" class="text-center">
                                                            @php
                                                                if($total_fail != 0){
                                                                    echo '0.00';
                                                                }else{
                                                                    $total_exam_subject = count($subjects);
                                                                    $average_mark = $gpa_without_optional_count / $total_exam_subject;
                                                                    echo round($average_mark,2);
                                                                    $average_grade = App\SmMarksGrade::where([['from', '<=', floor($average_mark)], ['up', '>=', floor($average_mark)]])->where('academic_id', getAcademicId())->first();
                                                                }
                                                            @endphp

                                                        </td>
                                                    </tr>
                                                </tbody>
                                    </table>


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
</body>
</html>
