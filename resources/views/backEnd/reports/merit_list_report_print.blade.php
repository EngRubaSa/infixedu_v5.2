<!DOCTYPE html>
<html lang="en">
<head>
  <title>@lang('lang.merit_list') </title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="{{asset('public/backEnd/')}}/vendors/css/print/bootstrap.min.css"/>
  <script type="text/javascript" src="{{asset('public/backEnd/')}}/vendors/js/print/jquery.min.js"></script>
  <script type="text/javascript" src="{{asset('public/backEnd/')}}/vendors/js/print/bootstrap.min.js"></script>
</head>
<style>
    body, table, th, td {
        font-size: 10px;
        font-family: 'Poppins', sans-serif;

    }
    
   .marklist th, .marklist td{
        border: 1px solid #ddd;  
        text-align: center !important;
        font-family: 'Poppins', sans-serif;
        
    }
    .marklist th{
        text-transform: capitalize;
        text-align: center;  
    }
    .marklist td{
        text-align: center; 
    }
 
 
    .container{ 
        padding-bottom: 50px;
        background: white;
        font-family: 'Poppins', sans-serif;
    }
    h1,h2,h3,h4{

        font-family: "Poppins", sans-serif; 
        margin-bottom: 15px;
    }
    hr{
        margin: 0px;
    }
    .mt-10 {
        margin-top:10px;
    }
    .mb-10{
        margin-bottom:10px;
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
<div class="container"> 
        <table style="width:100%; border:0;"> 
                <tr >
                    
                    <th>
                        <img class="logo-img" src="{{ url('/')}}/{{generalSetting()->logo }}" alt=""> 
                    </th>
                    <th colspan="2"> 
                        <h3 class="text-white"> {{isset(generalSetting()->school_name)?generalSetting()->school_name:'Infix School Management ERP'}} </h3> 
                        <p class="text-white mb-0" style="padding-right:10px !important;"> {{isset(generalSetting()->address)?generalSetting()->address:'Infix School Address'}} </p>
                    </th> 

                </tr>  
                <tr>
                    <td colspan="3"><hr></td>
                </tr>
                <tr>
                    <td  style=" padding:10px; vertical-align:top;">
                        
                        <p class="mb-0" style="padding-right:10px !important; font-size:11px;"> @lang('lang.academic_year') : <span class="primary-color fw-500">{{generalSetting()->session_year}}</span> </p>
                        <p class="mb-0" style="padding-right:10px !important; font-size:11px;"> @lang('lang.exam') : <span class="primary-color fw-500">{{$exam_name}}</span> </p>
                        <p class="mb-0" style="padding-right:10px !important; font-size:11px;"> @lang('lang.class') : <span class="primary-color fw-500">{{$class_name}}</span> </p>
                        <p class="mb-0" style="padding-right:10px !important; font-size:11px;"> @lang('lang.section') : <span class="primary-color fw-500">{{$section->section_name}}</span> </p>
                    </td>
                    <td  style="  padding:10px; vertical-align:top;"> 
                        <p style="font-weight: 700;">@lang('lang.subjects') @lang('lang.list')</p> 
                        <div class="row">
                            <div class="col-md-12 w-100" style="columns: 2">
                                @foreach($assign_subjects as $subject)
                                <p class="mb-0" style="padding-right:10px !important; font-size:11px;"> <span class="primary-color fw-500">{{$subject->subject->subject_name}}</span> </p>
                                @endforeach 
                            </div>
                        </div>
                    </td>
                    <td  style=" padding:10px; vertical-align:top;">
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
                    </td>
                </tr> 
        </table>

        <h4 style=" text-align: center;  padding: 10px;">@lang('lang.student') @lang('lang.merit_list')</h4> 

       

                                        <table class="w-100 mt-30 mb-20 table table-bordered marklist"  style="width: 100%; table-layout: fixed">
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
        <table style="width:100%">
            <tr> 
                <td> 
                    <p style="padding-top:10px; text-align:right; float:right; border-top:1px solid #ddd; display:inline-block; margin-top:50px;">( @lang('lang.exam_controller') )</p> 
                </td>
            </tr>

        </table>
</body>
</html>
    
