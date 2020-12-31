@extends('backEnd.master')
@section('mainContent')
<style type="text/css">
    .single-report-admit table tr th {

    border: 1px solid #a2a8c5 !important;
    vertical-align: middle;
}
    .single-report-admit table tr td {
        
    border: 1px solid #a2a8c5 !important;
}

    #grade_table th{
        border: 1px solid black;
        text-align: center;
        background: #351681;
        color: white;
    }
    #grade_table td{
        color: black;
        text-align: center !important;
        border: 1px solid black;
    }

hr{
    margin:0;
}
</style>
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('lang.progress_card_report')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('lang.dashboard')</a>
                <a href="#">@lang('lang.reports')</a>
                <a href="#">@lang('lang.progress_card_report')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area mb-40">
    <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-8 col-md-6">
                    <div class="main-title">
                        <h3 class="mb-30">@lang('lang.select_criteria') </h3>
                    </div>
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
                 @if(session()->has('message-danger') != "")
                    @if(session()->has('message-danger'))
                    <div class="alert alert-danger">
                        {{ session()->get('message-danger') }}
                    </div>
                    @endif
                @endif
                <div class="white-box">
                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'progress_card_report', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_student']) }}
                        <div class="row">
                            <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                            <div class="col-lg-4 mt-30-md md_mb_20">
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
                            <div class="col-lg-4 mt-30-md md_mb_20" id="select_section_div">
                                <select class="w-100 bb niceSelect form-control{{ $errors->has('section') ? ' is-invalid' : '' }} select_section" id="select_section" name="section">
                                    <option data-display="@lang('lang.select_section') *" value="">@lang('lang.select_section') *</option>
                                </select>
                                @if ($errors->has('section'))
                                <span class="invalid-feedback invalid-select" role="alert">
                                    <strong>{{ $errors->first('section') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="col-lg-4 mt-30-md md_mb_20" id="select_student_div">
                                <select class="w-100 bb niceSelect form-control{{ $errors->has('student') ? ' is-invalid' : '' }}" id="select_student" name="student">
                                    <option data-display="@lang('lang.select_student') *" value="">@lang('lang.select_student') *</option>
                                </select>
                                @if ($errors->has('student'))
                                <span class="invalid-feedback invalid-select" role="alert">
                                    <strong>{{ $errors->first('student') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="col-lg-12 mt-20 text-right">
                                <button type="submit" class="primary-btn small fix-gr-bg">
                                    <span class="ti-search"></span>
                                    @lang('lang.search')
                                </button>
                            </div>
                        </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
</section>

@if(isset($is_result_available))
@php 
$generalSetting = generalSetting();
        $school_name =$generalSetting->school_name;
        $site_title =$generalSetting->site_title;
        $school_code =$generalSetting->school_code;
        $address =$generalSetting->address;
        $phone =$generalSetting->phone; 
        $email =$generalSetting->email; 
@endphp
    <section class="student-details">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-4 no-gutters">
                    <div class="main-title">
                        <h3 class="mb-30">@lang('lang.progress_card_report')</h3>
                    </div>
                </div>
                <div class="col-lg-8 pull-right mt-0">

                        <div class="print_button pull-right">
                            {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'progress-card/print', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_student', 'target' => '_blank']) }}

                            <input type="hidden" name="class_id" value="{{$class_id}}">
                            <input type="hidden" name="section_id" value="{{$section_id}}">
                            <input type="hidden" name="student_id" value="{{$student_id}}">
                            
                            
                            <button type="submit" class="primary-btn small fix-gr-bg"><i class="ti-printer"> </i> @lang('lang.print')
                            </button>
                           {{ Form::close() }}
                        </div>

                    </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="white-box">
                        <div class="row justify-content-center">
                            <div class="col-lg-12">
                                <div class="single-report-admit">
                                    <div class="card">
                                            <div class="card-header">
                                                    <div class="d-flex">
                                                           
                                                            <div class="col-lg-2">
                                                            <img class="logo-img" src="{{ generalSetting()->logo }}" alt="">
                                                            </div>
                                                            <div class="col-lg-6 ml-30">
                                                                <h3 class="text-white"> {{isset(generalSetting()->school_name)?generalSetting()->school_name:'Infix School Management ERP'}} </h3> 
                                                                <p class="text-white mb-0"> {{isset(generalSetting()->address)?generalSetting()->address:'Infix School Address'}} </p>
                                                                <p class="text-white mb-0">Email:  {{isset($email)?$email:'admin@demo.com'}} ,   Phone:  {{isset(generalSetting()->phone)?generalSetting()->phone:'admin@demo.com'}} </p> 
                                                            </div>
                                                            <div class="offset-2">
                
                                                            </div>
                                                        </div>
                                                <div>
                                                    <img class="report-admit-img"  src="{{ file_exists(@$studentDetails->student_photo) ? asset($studentDetails->student_photo) : asset('public/uploads/staff/demo/staff.jpg') }}" width="100" height="100" alt="{{asset($studentDetails->student_photo)}}">
                                                </div>
                                                
                                                
                                            </div>
                                        <div class="card-body">
                                                <div class="row">
                                                
                                                        <div class="col-lg-8 text-black"> 
                                                            <h3 style="border-bottm:1px solid #ddd; padding: 15px; text-align:center"> @lang('lang.student_information')</h3>

                                                            <h3>{{  $studentDetails->full_name }}</h3>
                                                            
                                                            <div class="row">

                                                                <div class="col-lg-3">
                                                                    <p class="mb-0">
                                                                        @lang('lang.academic_year') : <span class="primary-color fw-500">{{generalSetting()->session_year}}</span>
                                                                    </p>
                                                                    <p class="mb-0">
                                                                            @lang('lang.roll') : <span class="primary-color fw-500">{{$studentDetails->roll_no}}</span>
                                                                        </p>
                                                                    
                                                                </div>

                                                                <div class="col-lg-3">
                                                                    <p class="mb-0">
                                                                        @lang('lang.class') : <span class="primary-color fw-500">{{ $studentDetails->class_name }}</span>
                                                                    </p>
                                                                    <p class="mb-0">
                                                                            @lang('lang.admission') @lang('lang.no') : <span class="primary-color fw-500">{{$studentDetails->admission_no}}</span>
                                                                        </p>
                                                                    {{-- <p class="mb-0">
                                                                        @lang('lang.section') : <span class="primary-color fw-500">{{ $studentDetails->section_name }}</span>
                                                                    </p> --}}

                                                                    
                                                                </div>

                                                                <div class="col-lg-3">
                                                                        <p class="mb-0">
                                                                                @lang('lang.section') : <span class="primary-color fw-500">{{ $studentDetails->section_name }}</span>
                                                                            </p>
                                                            
                                                                    {{-- <p class="mb-0">
                                                                        @lang('lang.position_in_class') : <span class="primary-color fw-500">CSE04506185</span>
                                                                    </p> --}}
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 text-black"> 
                                                            @php $marks_grade=DB::table('sm_marks_grades')->where('academic_id', getAcademicId())->get(); @endphp
                                                                @if(@$marks_grade)
                                                                <table class="table  table-bordered table-striped text-black" id="grade_table">
                                                                    <thead>
                                                                    <tr>
                                                                        <th>@lang('lang.staring')</th>
                                                                    <th>@lang('lang.ending')</th>
                                                                    <th>@lang('lang.gpa')</th>
                                                                    <th>@lang('lang.grade')</th>
                                                                    <th>@lang('lang.evalution')</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                            
                                                                    @foreach($marks_grade as $grade_d)
                                                                        <tr>
                                                                            <td>{{$grade_d->percent_from}}</td>
                                                                            <td>{{$grade_d->percent_upto}}</td>
                                                                            <td>{{$grade_d->gpa}}</td>
                                                                            <td>{{$grade_d->grade_name}}</td>
                                                                            <td class="text-left">{{$grade_d->description}}</td>
                                                                        </tr>
                                                                    @endforeach
                                                                    </tbody>
                                                                </table>
                                                            @endif
                                            
                                                        </div>
        
                                                    </div>
                                            <div>
                                                    
                                            </div>


                                            <table class="w-100 mt-30 mb-20 table table-bordered">
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
                                                    @foreach($subjects as $data)
                                                    <tr style="text-align: center">

                                                        @if ($optional_subject_setup!='' && $student_optional_subject!='')
                                                                @if ($student_optional_subject->subject_id==$data->subject->id)
                                                                <td>
                                                                    {{$data->subject !=""?$data->subject->subject_name:""}} (@lang('lang.optional')) 
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
                                                              
                                                                    @php
                                                                        if($totalSubjectFail > 0){
                                                                           
                                                                        }else{
                                                                            $totalSumSub = $totalSumSub / count($assinged_exam_types);

                                                                            $mark_grade = App\SmMarksGrade::where([['percent_from', '<=', floor($totalSumSub)], ['percent_upto', '>=', floor($totalSumSub)]])->first();


                                                                           
                                                                        }
                                                                    @endphp

                                                                <td>
                                                                    
                                                                    @php
                                                                        if($totalSubjectFail > 0){
                                                                            echo 'F';
                                                                        }else{
                                                                            if ($student_optional_subject!='') {
                                                                                if (@$student_optional_subject->subject_id==$data->subject->id) {
                                                                                $mark_grade = App\SmMarksGrade::where([['percent_from', '<=', floor($totalSumSub)], ['percent_upto', '>=', floor($totalSumSub)]])->first();
                                                                                
                                                                                if ($mark_grade->gpa > $optional_subject_setup->gpa_above) {
                                                                                   echo "GPA Above ".$optional_subject_setup->gpa_above;
                                                                                   echo "<hr>";
                                                                                   echo $mark_grade->gpa - $optional_subject_setup->gpa_above;
                                                                                   $optional_show_gpa=$mark_grade->gpa - $optional_subject_setup->gpa_above;
                                                                                   $gpa_with_optional_count+=$optional_show_gpa;
                                                                                   $gpa_without_optional_count+=$mark_grade->gpa;

                                                                                } else {
                                                                                    echo "GPA Above ".$optional_subject_setup->gpa_above;
                                                                                    echo "<hr>";
                                                                                    echo "0";
                                                                                }
                                                                            } else {
                                                                                
                                                                                $mark_grade = App\SmMarksGrade::where([['percent_from', '<=', floor($totalSumSub)], ['percent_upto', '>=', floor($totalSumSub)]])->first();
                                                                                echo @$mark_grade->gpa;
                                                                                $gpa_with_optional_count+=@$mark_grade->gpa;
                                                                                $gpa_without_optional_count+=@$mark_grade->gpa;
                                                                                
                                                                            }
                                                                            }else{
                                                                             
                                                                                $mark_grade = App\SmMarksGrade::where([['percent_from', '<=', floor($totalSumSub)], ['percent_upto', '>=', floor($totalSumSub)]])->first();
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
                                                        <td colspan="{{$colspan / $col_for_result - 1}}"  class="text-center">@lang('lang.total') @lang('lang.marks')</td>
                                                    <td colspan="{{$colspan / $col_for_result + 5}}" class="text-center" style="padding:10px; font-weight:bold">{{$total_marks}} </td>
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
                                                             <td colspan="4" class="text-center" style="padding:10px; font-weight:bold">
                                                                @php
                                                                    if($total_fail != 0){

                                                                        echo 'F';
                                                                    }else{
                                                                        $average_mark = $gpa_with_optional_count / $op_subject_count;
                                                                        $average_grade = App\SmMarksGrade::where([['from', '<=', floor($average_mark)], ['up', '>=', floor($average_mark)]])->first();
                                                                        echo @$average_grade->grade_name;
                                                                    }
                                                                @endphp

                                                            </td>
                                                             <td colspan="4" class="text-center" style="padding:10px; font-weight:bold">
                                                                @php
                                                                    if($total_fail != 0){

                                                                        echo 'F';
                                                                    }else{
                                                                        $average_mark = $gpa_without_optional_count / count($subjects);
                                                                        $average_grade = App\SmMarksGrade::where([['from', '<=', floor($average_mark)], ['up', '>=', floor($average_mark)]])->first();
                                                                        echo @$average_grade->grade_name;
                                                                    }
                                                                @endphp

                                                            </td>
                                                            
                                                        @else
                                                        <td colspan="{{$colspan / $col_for_result + 5}}" class="text-center" style="padding:10px; font-weight:bold">
                                                            @php
                                                                if($total_fail != 0){

                                                                    echo 'F';
                                                                }else{
                                                                    $total_exam_subject = count($subjects) ;
                                                                    $average_mark = $gpa_without_optional_count / $total_exam_subject;
                                                                    $average_grade = App\SmMarksGrade::where([['from', '<=', floor($average_mark)], ['up', '>=', floor($average_mark)]])->first();
                                                                    echo @$average_grade->grade_name;
                                                                }
                                                            @endphp

                                                        </td>
                                                        @endif

                                                    </tr>
                                                    <tr>
                                                        <td colspan="{{$colspan / $col_for_result - 1}}" class="text-center">@lang('lang.total') @lang('lang.gpa')</td>
                                                        
                                                        @if ($optional_subject_setup!='')
                                                        <td colspan="4" class="text-center" style="padding:10px; font-weight:bold">
                                                           @php
                                                               if($total_fail != 0){

                                                                   echo 'F';
                                                               }else{
                                                                   $average_mark = $gpa_with_optional_count / $op_subject_count;
                                                                   $average_grade = App\SmMarksGrade::where([['from', '<=', floor($average_mark)], ['up', '>=', floor($average_mark)]])->first();
                                                                   echo number_format((float)$average_mark, 2, '.', '');
                                                               }
                                                           @endphp
                                                       </td>
                                                        <td colspan="4" class="text-center" style="padding:10px; font-weight:bold">
                                                           @php
                                                               if($total_fail != 0){

                                                                   echo 'F';
                                                               }else{
                                                                   $average_mark = $gpa_without_optional_count / count($subjects);
                                                                   $average_grade = App\SmMarksGrade::where([['from', '<=', floor($average_mark)], ['up', '>=', floor($average_mark)]])->first();
                                                                   echo round($average_mark,2);
                                                               }
                                                           @endphp
                                                       </td>
                                                       
                                                   @else
                                                        <td colspan="{{$colspan / $col_for_result + 5}}" class="text-center" style="padding:10px; font-weight:bold">
                                                            @php
                                                                if($total_fail != 0){
                                                                    echo '0.00';
                                                                }else{
                                                                    $total_exam_subject = count($subjects);
                                                                    $average_mark = $gpa_without_optional_count / $total_exam_subject;
                                                                    echo number_format((float)$average_mark, 2, '.', '');
                                                                    $average_grade = App\SmMarksGrade::where([['from', '<=', floor($average_mark)], ['up', '>=', floor($average_mark)]])->first();
                                                                }
                                                            @endphp

                                                        </td>
                                                        @endif
                                                       
                                                       
                                                    </tr>
                                                </tbody>
                                            </table>

                                            {{-- Start Test --}}
                                            
                                            {{-- End Test --}}
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
@endif

            

@endsection
