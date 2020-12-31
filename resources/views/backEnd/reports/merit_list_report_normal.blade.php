@extends('backEnd.master')
@section('mainContent')
<style>
    tr{
        border: 1px solid #351681;

    }
    table.meritList{
        width: 100%;
        border: 1px solid #351681;
    }
    table.meritList th{
        padding: 2px;
        text-transform: capitalize !important;
        font-size: 11px !important;  
        text-align: center !important;
        border: 1px solid #351681;
        text-align: center; 

    }
    table.meritList td{
        padding: 2px;
        font-size: 11px !important;
        border: 1px solid #351681;
        text-align: center !important;
    } 
 .single-report-admit table tr td { 
    padding: 5px 5px !important;

        border: 1px solid #351681;
} 
.single-report-admit table tr th { 
    padding: 5px 5px !important;
    vertical-align: middle;
        border: 1px solid #351681;
}
.main-wrapper {
     display: block !important ;  
}
#main-content {
    width: auto !important;
}
hr{
    margin: 0px;
}
.gradeChart tbody td{
        padding: 0;
        border: 1px solid #351681;
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


    #grade_table th{
        border: 1px solid black;
        text-align: center;
        background: #351681;
        color: white;
    }
    #grade_table td{
        color: black;
        text-align: center;
        border: 1px solid black;
    }
</style>
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('lang.merit_list_report') </h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('lang.dashboard')</a>
                <a href="#">@lang('lang.reports')</a>
                <a href="#">@lang('lang.merit_list_report')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area">
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
                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'merit_list_report', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_student']) }}
                        <div class="row">
                            <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                            <div class="col-lg-4 mt-30-md">
                                <select class="w-100 bb niceSelect form-control{{ $errors->has('exam') ? ' is-invalid' : '' }}" name="exam">
                                    <option data-display="@lang('lang.select_exam')*" value="">@lang('lang.select_exam') *</option>
                                    @foreach($exams as $exam)
                                        <option value="{{$exam->id}}" {{isset($exam_id)? ($exam_id == $exam->id? 'selected':''):''}}>{{$exam->title}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('exam'))
                                <span class="invalid-feedback invalid-select" role="alert">
                                    <strong>{{ $errors->first('exam') }}</strong>
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
                                <select class="w-100 bb niceSelect form-control{{ $errors->has('section') ? ' is-invalid' : '' }} select_section" id="select_section" name="section">
                                    <option data-display="@lang('lang.select_section')*" value="">@lang('lang.select_section') *</option>
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
</section>


@if(isset($allresult_data))
@php 
    $generalSetting= App\SmGeneralSettings::find(1); 
    if(!empty($generalSetting)){
        $school_name =$generalSetting->school_name;
        $site_title =$generalSetting->site_title;
        $school_code =$generalSetting->school_code;
        $address =$generalSetting->address;
        $phone =$generalSetting->phone; 
        $email =$generalSetting->email; 
    } 
@endphp

<section class="student-details">
    <div class="container-fluid p-0">
        <div class="row mt-40">
            <div class="col-lg-4 no-gutters">
                <div class="main-title">
                    <h3 class="mb-30 mt-0">@lang('lang.merit_list_report')</h3>
                </div>
            </div>
            <div class="col-lg-8 pull-right">
                <a href="{{route('merit-list/print', [$InputExamId, $InputClassId, $InputSectionId])}}" class="primary-btn small fix-gr-bg pull-right" target="_blank"><i class="ti-printer"> </i> @lang('lang.print')</a>

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
                                            {{-- <div class="offset-2">

                                            </div> --}}
                                            <div class="col-lg-2">
                                            <img class="logo-img" src="{{ generalSetting()->logo }}" alt="">
                                            </div>
                                            <div class="col-lg-6 ml-30">
                                                <h3 class="text-white"> {{isset(generalSetting()->school_name)?generalSetting()->school_name:'Infix School Management ERP'}} </h3> 
                                                <p class="text-white mb-0"> {{isset(generalSetting()->address)?generalSetting()->address:'Infix School Address'}} </p>
                                                <p class="text-white mb-0">@lang('lang.email'):  {{isset($email)?$email:'admin@demo.com'}} ,   @lang('lang.phone'):  {{isset(generalSetting()->phone)?generalSetting()->phone:'admin@demo.com'}} </p> 
                                            </div>
                                            <div class="offset-2">

                                                </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="col-md-12">
                                            <div class="row">
                                                {{-- start col-lg-8 --}}
                                                <div class="col-lg-8"> 
                                                    <div class="row">

                                                        <div class="col-md-6">
                                                            <h3>@lang('lang.order_of_merit_list')</h3> 
                                                            <p class="mb-0">
                                                                @lang('lang.academic_year') : <span class="primary-color fw-500">{{generalSetting()->academic_Year->year}}</span>
                                                            </p>
                                                            <p class="mb-0">
                                                                @lang('lang.exam') : <span class="primary-color fw-500">{{$exam_name}}</span>
                                                            </p>
                                                            <p class="mb-0">
                                                                @lang('lang.class') : <span class="primary-color fw-500">{{$class_name}}</span>
                                                            </p>
                                                            <p class="mb-0">
                                                                @lang('lang.section') : <span class="primary-color fw-500">{{$section->section_name}}</span>
                                                            </p>  
                                                        </div>
                                                        <div class="col-md-6">
                                                            <h3>@lang('lang.subjects')</h3> 
                                                                @foreach($assign_subjects as $subject)
                                                                    <p class="mb-0">
                                                                        <span class="primary-color fw-500">{{$subject->subject->subject_name}}</span>
                                                                    </p>
                                                                @endforeach  
                                                        </div>

                                                    </div>

                                                </div>
                                                {{-- end col-lg-8 --}}



                                                {{-- sm_marks_grades --}}
                                                <div class="col-lg-4 text-black"> 
                                                    @php $marks_grade=DB::table('sm_marks_grades')->where('academic_id', getAcademicId())->get(); @endphp
                                                    @if(@$marks_grade)
                                                        <table class="table  table-bordered table-striped " id="grade_table">
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
                                                {{--end sm_marks_grades --}}

                                            </div> 
                                        </div>
                                        <h3 class="primary-color fw-500 text-center">@lang('lang.merit_list')</h3>
                                        <hr>

                                        <div class="table-responsive">
                                            
                                        <table class="w-100 mt-30 mb-20 table table-bordered meritList">
                                            <thead>
                                                <tr>
                                                    <th>@lang('lang.merit') @lang('lang.position')</th>
                                                    <th>@lang('lang.admission') @lang('lang.no')</th>
                                                    <th>@lang('lang.student')</th>
                                                    @foreach($subjectlist as $subject)
                                                    <th>{{$subject}}</th>
                                                    @endforeach

                                                    <th>@lang('lang.total_mark')</th>
                                                    <th>@lang('lang.average')</th>

                                                   
                                                    @if ($optional_subject_setup!='')
                                                    <th>@lang('lang.gpa')  
                                                        <hr>
                                                      <small>@lang('lang.without_additional')</small>  
                                                    
                                                    </th>
                                                    {{-- <th>@lang('lang.result')</th> --}}
                                                       <th>@lang('lang.gpa')</th>
                                                        <th>@lang('lang.result')</th>
                                                     @else
                                                     <th>@lang('lang.gpa')</th>
                                                     <th>@lang('lang.result')</th>
                                                    @endif
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @php $i=1; $subject_mark = []; $total_student_mark = 0; $total_student_mark_optional = 0; @endphp

                                                    @foreach($allresult_data as $row) 

                                                    @php
 
                                                    $student_detail=App\SmStudent::where('id','=',$row->student_id)->where('academic_id', getAcademicId())->first();
                                                            $optional_subject='';
                                                                   
                                                            $get_optional_subject=App\SmOptionalSubjectAssign::where('student_id','=',$student_detail->id)->where('session_id','=',$student_detail->session_id)->where('academic_id', getAcademicId())->first();
                                                                   
                                                            if ($get_optional_subject!='') {
                                                                        $optional_subject=$get_optional_subject->subject_id;
                                                                    
                                                               } 
                                                        
                                                    @endphp     
                                                <tr>
                                                    <td>{{$row->merit_order}}</td>
                                                    <td>{{$row->admission_no}}</td>
                                                    <td style="text-align:left !important;" nowrap >{{$row->student_name}}</td>

                                                    @php
                                                     $markslist = explode(',',$row->marks_string);
                                                    $get_subject_id = explode(',',$row->subjects_id_string);
                                                    $count=0;
                                                    $subject_mark=[];
                                                    $additioncheck=[];
                                                    // $special_mark=[];
                                                    @endphp 

                                                    @if(!empty($markslist))
                                                        @foreach($markslist as $mark)
                                                            @php 
                                                                $subject_mark[]= $mark;
                                                                $total_student_mark = $total_student_mark + $mark; 
                                                            @endphp 
                                                            <td>  {{!empty($mark)?$mark:0}} 
                                                           
                                                                @if (App\SmOptionalSubjectAssign::is_optional_subject($row->student_id,$get_subject_id[$count]))
                                                                    <hr>
                                                                    @php
                                                                        $additioncheck[] = $mark;
                                                                    @endphp
                                                                    {{-- GPA Above {{ $optional_subject_setup->gpa_above }} --}}
                                                                    <small>(@lang('lang.additional_subject'))</small>
                                                                @endif
                                                            @php
                                                                    if(App\SmOptionalSubjectAssign::is_optional_subject($row->student_id,$get_subject_id[$count])){
                                                                       
                                                                        $special_mark[$row->student_id]=$mark;
                                                                    }
                                                                    $count++;
                                                       
                                                           @endphp
                                                        
                                                        </td> 
                                                        @endforeach
                                                     
                                                    @endif


                                                    <td>{{$total_student_mark}} </td>
                                                    <td>{{!empty($row->average_mark)?$row->average_mark:0}} @php $total_student_mark=0; @endphp </td> 
                                                  
 
                                                      {{-- END GPA with optional --}}
                                                      <td> 
                                                            <?php 
                                                            // dd($additioncheck);
                                                            if($row->result == 'F'){
                                                                echo '0.00';
                                                            }else{
                                                               $total_grade_point = 0;
                                                               $c = 0;
                                                                $number_of_subject = count($subject_mark); 
                                                                foreach ($subject_mark as $mark) {
                                                                    if (@$additioncheck['0'] != $mark) {
                                                                    $grade_gpa = DB::table('sm_marks_grades')->where('percent_from','<=',$mark)->where('percent_upto','>=',$mark)->where('academic_id', getAcademicId())->where('academic_id', getAcademicId())->first();
                                                                    $total_grade_point = $total_grade_point + $grade_gpa->gpa;
                                                                    $c++;
                                                                   }
                                                                }
                                                                if($total_grade_point==0){
                                                                    echo '0.00';
                                                                }else{
                                                                    if($number_of_subject  == 0){
                                                                        echo '0.00';
                                                                    }else{
                                                                        echo number_format((float)$total_grade_point/$c, 2, '.', '');
                                                                    } 
                                                                } 
                                                            }
                                                            ?>
                                                        </td> 

                                                        @if ( $get_optional_subject=='')
                                                        <td> 
                                                            {{$row->result}}
                                           
                                                        </td> 
                                                        @endif
                                                        
                                                        @if ($optional_subject_setup!='' )
                                                           
                                                        
                                                        @if ( $get_optional_subject!='')
                                                            
                                                        
                                                            @php
                                                            
                                                                if(!empty($special_mark[$row->student_id])){
                                                                            $optional_subject_mark=$special_mark[$row->student_id];
                                                                        }else{
                                                                            $optional_subject_mark=0;
                                                                        }
                                                            @endphp

                                                       
                                                        <td> 
                                                           
                                                             <?php 
                                                             if($row->result == 'F'){
                                                                 echo '0.00';
                                                             }else{
                                                                 $optional_grade_gpa = DB::table('sm_marks_grades')->where('percent_from','<=',$optional_subject_mark)->where('percent_upto','>=',$optional_subject_mark)->where('academic_id', getAcademicId())->first();
                                                                 $countable_optional_gpa=0;
                                                                 if ($optional_grade_gpa->gpa > $optional_subject_setup->gpa_above) {
                                                                     $countable_optional_gpa=$optional_grade_gpa->gpa - $optional_subject_setup->gpa_above;
                                                                 } else {
                                                                     $countable_optional_gpa=0;
                                                                 }
                                                                 
                                                                 // echo "op G".$countable_optional_gpa;
                                                                 // dd($subject_mark);
                                                                
                                                                $total_grade_point = 0;
                                                                 $number_of_subject = count($subject_mark); 
                                                                 foreach ($subject_mark as $mark) {
                                                                    
                                                                     // echo $mark;
                                                                     $grade_gpa = DB::table('sm_marks_grades')->where('percent_from','<=',$mark)->where('percent_upto','>=',$mark)->where('academic_id', getAcademicId())->first();
                                                                     $total_grade_point = $total_grade_point + $grade_gpa->gpa;
                                                                 }
                                                                 $gpa_with_optional=$total_grade_point-$optional_grade_gpa->gpa;
                                                                 $gpa_with_optional=$gpa_with_optional+$countable_optional_gpa;
                                                                
                                                                 // echo "Optional GPA".$gpa_with_optional." -Total gpa:".$total_grade_point  ;
                                                                 if($gpa_with_optional==0){
                                                                     echo '0.00';
                                                                 }else{
                                                                     if($number_of_subject  == 0){
                                                                         echo '0.00';
                                                                     }else{
                                                                         $grade=number_format((float)$gpa_with_optional/$number_of_subject, 2, '.', '');
                                                                         if ($grade>5) {
                                                                             echo "5.00";
                                                                            // echo $grade;
                                                                         } else {
                                                                            echo $grade;
                                                                         }
                                                                     } 
                                                                 } 
     
                                                             }                                                                 
     
                                                             ?>  
                                                         </td>      
                                                         <td> 
                                                                @php
                                                                if($row->result == 'F'){
                                                                        echo 'F';
                                                                }else {
                                                                    $optional_grade_gpa = DB::table('sm_marks_grades')->where('from','<=',$grade)->where('up','>=',$grade)->where('academic_id', getAcademicId())->first();
                                                                     echo @$optional_grade_gpa->grade_name;
                                                                }
                                                             @endphp
                                            
                                                         </td>
                                                         @else
                                                         <td> 
                                                            <?php 
    
                                                            if($row->result == 'F'){
                                                                echo '0.00';
                                                            }else{
                                                               $total_grade_point = 0;
                                                                $number_of_subject = count($subject_mark); 
                                                                foreach ($subject_mark as $mark) {
                                                                    $grade_gpa = DB::table('sm_marks_grades')->where('percent_from','<=',$mark)->where('percent_upto','>=',$mark)->where('academic_id', getAcademicId())->first();
                                                                    $total_grade_point = $total_grade_point + $grade_gpa->gpa;
                                                                }
                                                                if($total_grade_point==0){
                                                                    echo '0.00';
                                                                }else{
                                                                    if($number_of_subject  == 0){
                                                                        echo '0.00';
                                                                    }else{
                                                                        echo number_format((float)$total_grade_point/$number_of_subject, 2, '.', '');
                                                                    } 
                                                                } 
                                                            }
                                                            ?> 
                                                        </td> 
                                                        <td> 
                                                            {{$row->result}}
                                           
                                                        </td>
                                                        @endif
                                                         @endif
                                                 </tr> 
                                                 
                                                   
                                                   {{-- START GPA with optional --}}
                                                

                                                @endforeach
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
    </div>
</section>

@endif
            

@endsection
