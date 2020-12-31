@extends('backEnd.master')
@section('mainContent')


<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('lang.exam_result')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('lang.dashboard')</a>
                <a href="#">@lang('lang.examinations')</a>
                <a href="{{route('parent_examination', [$student_detail->id])}}">@lang('lang.exam_result')</a>
            </div>
        </div>
    </div>
</section>

<section class="student-details">
    <div class="container-fluid p-0">
        <div class="row mt-40">
            <div class="col-lg-6 col-md-6">
                <div class="main-title">
                    <h3 class="mb-30">@lang('lang.student_information')</h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                <!-- Start Student Meta Information -->
                <div class="student-meta-box">
                    <div class="student-meta-top"></div>
                    <img class="student-meta-img img-100" src="{{asset($student_detail->student_photo)}}" alt="">
                    <div class="white-box radius-t-y-0">
                        <div class="single-meta mt-10">
                            <div class="d-flex justify-content-between">
                                <div class="name">
                                    @lang('lang.student_name')
                                </div>
                                <div class="value">
                                    {{$student_detail->first_name.' '.$student_detail->last_name}}
                                </div>
                            </div>
                        </div>
                        <div class="single-meta">
                            <div class="d-flex justify-content-between">
                                <div class="name">
                                    @lang('lang.admission_no')
                                </div>
                                <div class="value">
                                    {{$student_detail->admission_no}}
                                </div>
                            </div>
                        </div>
                        <div class="single-meta">
                            <div class="d-flex justify-content-between">
                                <div class="name">
                                    @lang('lang.roll_number')
                                </div>
                                <div class="value">
                                     {{$student_detail->roll_no}}
                                </div>
                            </div>
                        </div>
                        <div class="single-meta">
                            <div class="d-flex justify-content-between">
                                <div class="name">
                                    @lang('lang.class')
                                </div>
                                <div class="value">
                                    @if($student_detail->className !="" && $student_detail->session !="")
                                   {{$student_detail->className->class_name}} ({{$student_detail->session->session}})
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="single-meta">
                            <div class="d-flex justify-content-between">
                                <div class="name">
                                    @lang('lang.section')
                                </div>
                                <div class="value">
                                    {{$student_detail->section !=""?$student_detail->section->section_name:""}}
                                </div>
                            </div>
                        </div>
                        <div class="single-meta">
                            <div class="d-flex justify-content-between">
                                <div class="name">
                                    @lang('lang.gender')
                                </div>
                                <div class="value">
                                    {{$student_detail->gender !=""?$student_detail->gender->base_setup_name:""}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Student Meta Information -->

            </div>

            <!-- Start Student Details -->
            <div class="col-lg-9">


                    <!-- Start Exam Profile view-->
                    @foreach($exam_terms as $exam)

                    @php

                        $get_results = getExamResult(@$exam->id, @$student_detail);


                    @endphp


                    @if($get_results)

                    <div class="main-title">
                        <h3 class="mb-0">{{@$exam->title}}</h3>
                    </div>
                    <table id="table_id" class="display school-table" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>@lang('lang.date')</th>
                            <th>@lang('lang.subject')</th>
                            <th>@lang('lang.full_marks')</th>
                            <th>@lang('lang.obtained_marks')</th>
                            <th>@lang('lang.grade')</th>
                            <!-- <th>@lang('lang.results')</th> -->
                        </tr>
                        </thead>

                        <tbody>
                            
                        @php
                            $grand_total = 0;
                            $grand_total_marks = 0;
                            $result = 0;
                        @endphp

                        @foreach($get_results as $mark)
                            @php
                                $subject_marks = $exam->examsSetup->where('subject_id',$mark->subject_id)->first();

                                $schedule_by_subject = $student_detail->examsSchedule->where('exam_term_id',$exam->id)->where('subject_id',$mark->subject_id)->first();

                                $result_subject = 0;

                                $grand_total_marks += @$subject_marks->exam_mark;

                                if(@$mark->is_absent == 0){
                                    $grand_total += @$mark->total_marks;
                                    if($mark->marks < $subject_marks->pass_mark){
                                       $result_subject++;
                                       $result++;
                                    }
                                }else{
                                    $result_subject++;
                                    $result++;
                                }
                            @endphp
                            <tr>
                                <td>{{ !empty($schedule_by_subject->date)? dateConvert($schedule_by_subject->date):''}}</td>
                                <td>{{@$mark->subject->subject_name}}</td>
                                <td>{{@$subject_marks->exam_mark}}</td>
                                <td>{{@$mark->total_marks}}</td>
                                <td>{{@$mark->total_gpa_grade}}</td>
                                <!-- <td>
                                    @if($result_subject == 0)
                                        <button
                                            class="primary-btn small bg-success text-white border-0">
                                            @lang('lang.pass')
                                        </button>
                                    @else
                                        <button class="primary-btn small bg-danger text-white border-0">
                                            @lang('lang.fail')
                                        </button>
                                    @endif
                                </td> -->
                            </tr>
                        @endforeach
                        </tbody>
                            <tfoot>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th>@lang('lang.grand_total'): {{$grand_total}}/{{$grand_total_marks}}</th>
                                
                                <th>@lang('lang.grade'):
                                    @php
                                        if($result == 0 && $grand_total_marks != 0){
                                            $percent = $grand_total/$grand_total_marks*100;


                                            foreach($grades as $grade){
                                               if(floor($percent) >= $grade->percent_from && floor($percent) <= $grade->percent_upto){
                                                   echo $grade->grade_name;
                                               }
                                           }

                                        }else{
                                            echo "F";
                                        }
                                    @endphp
                                </th>
                            </tr>
                            </tfoot>
                    </table>

                    @endif

                    @endforeach
                </div>
                    <!-- End Exam Profile view-->
                    
            </div>
            <!-- End Student Details -->
        </div>

            
    </div>
</section>


@endsection
