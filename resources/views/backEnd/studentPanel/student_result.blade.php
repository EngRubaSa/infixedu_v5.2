@extends('backEnd.master')
@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('lang.examinations')</h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">@lang('lang.dashboard')</a>
                    <a href="#">Examinations</a>
                    <a href="{{route('student_result')}}">@lang('lang.result')</a>
                </div>
            </div>
        </div>
    </section>

    <section class="student-details">
        <div class="container-fluid p-0">
            <div class="row">

                <!-- Start Student Details -->
                <div class="col-lg-12">

                <!-- Start Exam Profile view-->
                    @foreach($exam_terms as $exam)

                        @php

                            $get_results = App\SmStudent::getExamResult(@$exam->id, @$student_detail);


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
    </section>






@endsection
